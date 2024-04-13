<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\ErrorLog;
use App\Models\Platform;
use App\Models\PrizeItem;
use App\Models\RewardDelivery;
use App\Models\UserVoucher;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index()
    {
        $platform = Platform::select('id', 'name', 'logo')->where('is_active', true)->get();
        $list_prize = PrizeItem::select('id', 'title', 'value', 'cat_id')->get();
        return view('landing.index', [
            'list_prize' => $list_prize,
            'platform' => $platform,
        ]);
    }

    public function verify(Request $request): JsonResponse
    {
        $form = $request->voucher;
        $date = Carbon::today()->toDateString();
        try {
            $verify = UserVoucher::whereRaw("code_voucher LIKE ?", [$form])->firstOrFail();
            if ($verify->is_available) {
                return response()->json([
                    'status' => "success",
                    'data' => null,
                    'message' => 'Voucher valid',
                ], 200);
            } else {
                $security = ErrorLog::where('from', $request->getClientIp())
                    ->where('activity', "User verify voucher = " . $request->voucher)
                    ->where('error_detail', 'Voucher code already use before')
                    ->get();

                if ($security->count() < 3) {
                    $log = new ErrorLog();
                    $log->activity = "User verify voucher = " . $request->voucher;
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

            $security = ErrorLog::where('from', $request->getClientIp())
                ->where('error_detail', $errorMessage)
                ->where('created_at', 'like', '%' . $date . '%')
                ->get();

            if ($security->count() < 5) {
                $log = new ErrorLog();
                $log->activity = "Voucher code is not registered in system = " . $request->voucher;
                $log->from = $request->getClientIp();
                $log->error_detail = $errorMessage;
                $log->save();

                return response()->json([
                    'status' => "error",
                    'message' => 'Maaf, kode voucher yang Anda masukkan sepertinya tidak valid. Mohon periksa kembali kode voucher Anda.',
                ], 400);
            } else {
                return response()->json([
                    'status' => "error",
                    'message' => 'IP address anda kami record karena percobaan brute force, dan percobaan anda berikutnya sudah tidak kami proses.',
                ], 403);
            }
        }
    }

    public function spin(Request $request): JsonResponse
    {
        $form = $request->voucher;
        $date = Carbon::today()->toDateString();
        try {
            $verify = UserVoucher::whereRaw("code_voucher LIKE ?", [$form])->firstOrFail();
            if ($verify->is_available) {
                $rewardList = PrizeItem::get()->toArray();
                if (!is_array($rewardList)) {
                    throw new \Exception("Reward list is not an array.");
                }
                $totalProbability = array_reduce($rewardList, function ($carry, $item) {
                    return $carry + $item['probability'];
                });
                $randomNumber = mt_rand(0, $totalProbability);
                $cumulativeProbability = 0;
                $rewardObj = null;
                foreach ($rewardList as $reward) {
                    $cumulativeProbability += $reward['probability'];
                    if ($randomNumber <= $cumulativeProbability) {
                        $obj = new \StdClass;
                        $obj->id = $reward['id'];
                        $obj->title = $reward['title'];
                        $obj->username = $verify->username;
                        $obj->value = $reward['value'];
                        $obj->cat_id = $reward['cat_id'];
                        $rewardObj = $obj;
                        break;
                    }
                }

                if ($rewardObj) {
                    try {
                        $data = new RewardDelivery();
                        $data->username = $verify->username;
                        $data->prize_id = $rewardObj->id;
                        $data->prize_title = $rewardObj->title;
                        $data->prize_value = $rewardObj->value;
                        $data->prize_cat = $rewardObj->cat_id;
                        $data->platform_id = $request->platform_id;
                        $data->voucher_id = $verify->id;
                        $data->save();

                        $verify->is_available = false;
                        $verify->save();

                        return response()->json([
                            'status' => "success",
                            'data' => $rewardObj,
                            'message' => 'Selamat reward anda berhasil didapatkan.',
                        ], 200);
                    } catch (\Throwable $th) {
                        return response()->json([
                            'status' => "warning",
                            'data' => null,
                            'message' => 'Opps, pilih platform yang anda mainkan, sebelum memutar hadiah anda.',
                        ], 500);
                    }
                } else {
                    $security = ErrorLog::where('from', "Error code 500 " . $request->getClientIp())->get();
                }
            } else {
                return response()->json([
                    'status' => "success",
                    'data' => null,
                    'message' => 'Voucher sudah digunakan.',
                ], 403);
            }
        } catch (\Exception $th) {
            $errorMessage = $th->getMessage();

            $security = ErrorLog::where('from', $request->getClientIp())
                ->where('error_detail', $errorMessage)
                ->where('created_at', 'like', '%' . $date . '%')
                ->get();

            if ($security->count() < 5) {
                $log = new ErrorLog();
                $log->activity = "User tried to spin using voucher code = " . $request->voucher;
                $log->from = $request->getClientIp();
                $log->error_detail = $errorMessage;
                $log->save();

                return response()->json([
                    'status' => "error",
                    'message' => 'Maaf, ada kesalahan di server, mohon hubungi admin untuk mengatasi masalah ini.',
                ], 400);
            } else {
                return response()->json([
                    'status' => "error",
                    'message' => 'IP address anda kami record karena percobaan brute force, dan percobaan anda berikutnya sudah tidak kami proses.',
                ], 403);
            }
        }
    }
}
