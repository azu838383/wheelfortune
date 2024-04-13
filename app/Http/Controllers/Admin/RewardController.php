<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LogAdmin;
use App\Models\RewardDelivery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RewardController extends Controller
{
    public function index()
    {
        $form = null;
        $start_date = null;
        $end_date = null;
        $rewards = RewardDelivery::orderBy('id', 'desc')->paginate(20);
        return view('admin.reward.index', [
            'rewards' => $rewards,
            'form' => $form,
            'start_date' => $start_date,
            'end_date' => $end_date,
        ]);
    }

    public function search(Request $request)
    {
        $error = null;
        $form = $request->search;
        $start_date = null;
        $end_date = null;
        try {
            $this->validate($request, [
                'search' => 'required',
            ]);
            $rewards = RewardDelivery::join('user_voucher', 'reward_delivery.voucher_id', '=', 'user_voucher.id')
                ->where('user_voucher.username', 'LIKE', '%' . $form . '%')
                ->orWhere('user_voucher.code_voucher', 'LIKE', '%' . $form . '%')
                ->orderBy('reward_delivery.id', 'desc') // Specify the table alias for orderBy
                ->get();
        } catch (\Throwable $th) {
            $error = $th->getMessage();
            return redirect('/system/reward');
        }

        return view('admin.reward.index', [
            'rewards' => $rewards,
            'error' => $error,
            'form' => $form,
            'start_date' => $start_date,
            'end_date' => $end_date,
        ]);
    }

    public function search_bydate(Request $request)
    {
        $error = null;
        $form = null;
        $start_date = $request->start_date;
        $end_date = $request->to_date;
        $start_date_time = $start_date . ' 00:00:00';
        $end_date_time = $end_date . ' 23:59:59';

        try {
            $rewards = RewardDelivery::where('created_at', '>=', $start_date_time)->where('created_at', '<=', $end_date_time)->orderBy('id', 'desc')->paginate(20);
        } catch (\Throwable $th) {
            $error = $th->getMessage();
            return redirect('/system/reward');
        }

        return view('admin.reward.index', [
            'rewards' => $rewards,
            'error' => $error,
            'form' => $form,
            'start_date' => $start_date,
            'end_date' => $end_date,
        ]);
    }

    public function delivery(Request $request)
    {
        $error = null;
        try {
            $this->validate($request, [
                'id' => 'required',
            ]);

            $data = RewardDelivery::find($request->id);
            $data->proced_by = Auth::user()->id;
            $data->delivery_status = true;
            $data->count_changes = $data->count_changes + 1;
            $data->save();

            $log = new LogAdmin();
            $log->uid = Auth::user()->id;
            $log->act_on = "Reward & Delivery";
            $log->activity = "Processed reward delivery";
            $log->detail = "Delivery reward to player, reward id = " . $request->id;
            $log->save();

            session()->flash('success', 'Reward delivery successfully!');
        } catch (\Throwable $th) {
            $error = $th->getMessage();
        }

        return redirect('/system/reward')->with('error', $error);
    }

    public function cancel_delivery(Request $request)
    {
        $error = null;
        try {
            $this->validate($request, [
                'id' => 'required',
            ]);

            $data = RewardDelivery::find($request->id);
            $data->proced_by = Auth::user()->id;
            $data->delivery_status = false;
            $data->count_changes = $data->count_changes + 1;
            $data->save();

            $log = new LogAdmin();
            $log->uid = Auth::user()->id;
            $log->act_on = "Reward & Delivery";
            $log->activity = "Reward delivery cancelled";
            $log->detail = "Cancel delivery for data id = " . $request->id;
            $log->save();

            session()->flash('success', 'Cancel delivery successfully!');
        } catch (\Throwable $th) {
            $error = $th->getMessage();
        }

        return redirect('/system/reward')->with('error', $error);
    }
}
