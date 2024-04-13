<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\ErrorLog;
use App\Models\PrizeItem;
use App\Models\RewardDelivery;
use App\Models\Setting;
use App\Models\UserVoucher;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index()
    {
        $text_win = Setting::where('set_for', 'win_text')->first();
        $text_lose = Setting::where('set_for', 'lose_text')->first();
        $guide = Setting::where('set_for', 'guide_text')->first();
        $welcome = Setting::where('set_for', 'wellcome_text')->first();
        $list_prize = PrizeItem::select('id', 'title', 'value', 'cat_id')->get();
        return view('landing.index', [
            'list_prize' => $list_prize,
            'text_win' => $text_win,
            'text_lose' => $text_lose,
            'guide' => $guide,
            'welcome' => $welcome,
        ]);
    }

    public function verify(Request $request): JsonResponse
    {
        $form = $request->voucher;
        try {
            $verify = UserVoucher::whereRaw("code_voucher LIKE ?", [$form])->firstOrFail();
            if ($verify->is_available === 'available') {
                $rewardList = PrizeItem::get()->toArray();
                if (!is_array($rewardList)) {
                    throw new \Exception("Reward list is not an array.");
                }

                $totalFirstProb = array_reduce($rewardList, function ($carry, $item) {
                    return $carry + $item['first_prob'];
                });

                $totalSecondProb = array_reduce($rewardList, function ($carry, $item) {
                    return $carry + $item['second_prob'];
                });

                $randomNumber = 0;
                if ($verify->set_prob === "prob_one") {
                    $randomNumber = mt_rand(0, $totalFirstProb);
                } else {
                    $randomNumber = mt_rand(0, $totalSecondProb);
                }

                $cumulativeProbability = 0;
                $rewardObj = null;
                foreach ($rewardList as $reward) {
                    if ($verify->set_prob === "prob_one") {
                        $cumulativeProbability += $reward['first_prob'];
                    } else {
                        $cumulativeProbability += $reward['second_prob'];
                    }
                    if ($randomNumber <= $cumulativeProbability) {
                        $obj = new \StdClass;
                        $obj->id = $reward['id'];
                        $obj->title = $reward['title'];
                        $obj->username = $verify->username;
                        $obj->platform = $verify->Platform->name;
                        $obj->value = $reward['value'];
                        $obj->cat_id = $reward['cat_id'];
                        $rewardObj = $obj;
                        break;
                    }
                }

                if ($rewardObj) {
                    $data = new RewardDelivery();
                    $data->username = $verify->username;
                    $data->prize_id = $rewardObj->id;
                    $data->prize_title = $rewardObj->title;
                    $data->prize_value = $rewardObj->value;
                    $data->prize_cat = $rewardObj->cat_id;
                    $data->platform_id = $verify->platform_id;
                    $data->voucher_id = $verify->id;
                    $data->save();

                    $verify->is_available = 'used';
                    $verify->save();
                    return response()->json([
                        'status' => "success",
                        'data' => $rewardObj,
                        'message' => 'Spinner berhasil diputar.',
                    ], 200);
                } else {
                    $security = ErrorLog::where('from', "Error code 500 " . $request->getClientIp())->get();

                    if ($security->count() < 5) {
                        $log = new ErrorLog();
                        $log->activity = "User try to spin using voucher code = " . $request->voucher;
                        $log->from = "Error code 500 " . $request->getClientIp();
                        $log->error_detail = "Error code 500, you must be contact Developer to fix this.";
                        $log->save();

                        return response()->json([
                            'status' => "error",
                            'data' => $form,
                            'message' => 'Oppss.. Ada kesalahan pada server, mohon beri tahu admin!',
                        ], 500);
                    } else {
                        return response()->json([
                            'status' => "error",
                            'data' => $form,
                            'message' => 'Segera hubungi admin untuk menyelesaikan masalah ini!',
                        ], 500);
                    }
                }
            } elseif($verify->is_available === 'expire') {
                $security = ErrorLog::where('from', $request->getClientIp())
                    ->where('activity', "User tried to spin using voucher code = " . $request->voucher)
                    ->where('error_detail', 'Voucher code already expired')
                    ->get();

                if ($security->count() < 3) {
                    $log = new ErrorLog();
                    $log->activity = "User tried to spin using voucher code = " . $request->voucher;
                    $log->from = $request->getClientIp();
                    $log->error_detail = "Voucher code already expired";
                    $log->save();

                    return response()->json([
                        'status' => "warning",
                        'data' => $form,
                        'message' => 'Maaf, kode voucher yang Anda masukkan sudah kedaluwarsa.',
                    ], 403);
                } else {
                    return response()->json([
                        'status' => "error",
                        'data' => $form,
                        'message' => 'Kode voucher ini ' . $request->voucher . ' sudah kedaluwarsa, tindakan ini tidak akan kami proses.',
                    ], 403);
                }
            } else {
                $security = ErrorLog::where('from', $request->getClientIp())
                    ->where('activity', "User tried to spin using voucher code = " . $request->voucher)
                    ->where('error_detail', 'Voucher code already use before')
                    ->get();

                if ($security->count() < 3) {
                    $log = new ErrorLog();
                    $log->activity = "User tried to spin using voucher code = " . $request->voucher;
                    $log->from = $request->getClientIp();
                    $log->error_detail = "Voucher code already use before";
                    $log->save();

                    return response()->json([
                        'status' => "warning",
                        'data' => $form,
                        'message' => 'Maaf, kode voucher yang Anda masukkan sudah digunakan sebelumnya.',
                    ], 403);
                } else {
                    return response()->json([
                        'status' => "error",
                        'data' => $form,
                        'message' => 'Kode voucher ini ' . $request->voucher . ' sudah lebih dari 3 kali anda gunakan, tindakan ini tidak akan kami proses.',
                    ], 403);
                }
            }
        } catch (\Exception $th) {
            $errorMessage = $th->getMessage() === "No query results for model [App\Models\UserVoucher]."
                ? "Voucher code is not registered in system"
                : $th->getMessage();

            return response()->json([
                'status' => "error",
                'message' => $errorMessage,
            ], 400);

            // $security = ErrorLog::where('from', $request->getClientIp())
            //     ->where('error_detail', $errorMessage)
            //     ->where('created_at', 'like', '%' . $date . '%')
            //     ->get();

            // if ($security->count() < 5) {
            //     $log = new ErrorLog();
            //     $log->activity = "User tried to spin using voucher code = " . $request->voucher;
            //     $log->from = $request->getClientIp();
            //     $log->error_detail = $errorMessage;
            //     $log->save();

            //     return response()->json([
            //         'status' => "error",
            //         'message' => 'Maaf, kode voucher yang Anda masukkan sepertinya tidak valid. Mohon periksa kembali kode voucher Anda.',
            //     ], 400);
            // } else {
            //     return response()->json([
            //         'status' => "error",
            //         'message' => 'IP address anda kami record karena percobaan brute force, dan percobaan anda berikutnya sudah tidak kami proses.',
            //     ], 403);
            // }
        }
    }
}
