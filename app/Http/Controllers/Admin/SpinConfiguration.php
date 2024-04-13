<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CatPrize;
use App\Models\LogAdmin;
use App\Models\PrizeItem;
use Illuminate\Support\Facades\Auth;

class SpinConfiguration extends Controller
{
    public function index()
    {
        $prize = PrizeItem::orderBy('value', 'asc')->get();
        $first_prob_total = PrizeItem::sum('first_prob');
        $first_percent_left = 100 - $first_prob_total;
        $second_prob_total = PrizeItem::sum('second_prob');
        $second_percent_left = 100 - $second_prob_total;
        $cat_prize = CatPrize::orderBy('id', 'asc')->get();
        return view('admin.spinconfig.index', [
            'prize' => $prize,
            'cat_prize' => $cat_prize,
            'first_percent_left' => $first_percent_left,
            'second_percent_left' => $second_percent_left,
        ]);
    }

    public function updateCat(Request $request)
    {
        $error = null;
        try {
            $this->validate($request, [
                'id' => 'required',
                'title' => 'required',
                'unit' => 'required',
            ]);

            $data = CatPrize::find($request->id);
            $data->title = $request->title;
            $data->unit = $request->unit;
            $data->save();

            $log = new LogAdmin();
            $log->uid = Auth::user()->id;
            $log->act_on = "Spin Configuration";
            $log->activity = "Category configuration update";
            $log->detail = "Change category configuration for data id = " . $request->id;
            $log->save();

            session()->flash('success', 'Category saved!');
        } catch (\Throwable $th) {
            $error = $th->getMessage();
        }

        return redirect('/system/spin-config')->with('error', $error);
    }

    public function updatePrize(Request $request)
    {
        $error = null;
        try {
            $this->validate($request, [
                'id' => 'required',
                'title' => 'required',
                'value' => 'required',
                'first_prob' => 'required',
                'second_prob' => 'required',
                'cat_id' => 'required',
            ]);

            $current_first_prob_total = PrizeItem::where('id', '!=', $request->id)->sum('first_prob');
            $current_second_prob_total = PrizeItem::where('id', '!=', $request->id)->sum('second_prob');

            $updated_total_first_prob = $current_first_prob_total + $request->first_prob;
            $allow_first_prob = 100 - $current_first_prob_total;

            $updated_total_second_prob = $current_second_prob_total + $request->second_prob;
            $allow_second_prob = 100 - $current_second_prob_total;

            if ($updated_total_first_prob <= 100 && $updated_total_second_prob <= 100) {
                $data = PrizeItem::find($request->id);
                $data->title = $request->title;
                $data->value = $request->value;
                $data->first_prob = $request->first_prob;
                $data->second_prob = $request->second_prob;
                $data->cat_id = $request->cat_id;
                $data->save();

                $log = new LogAdmin();
                $log->uid = Auth::user()->id;
                $log->act_on = "Spin Configuration";
                $log->activity = "Spin configuration update";
                $log->detail = "Change configuration for data id = " . $request->id;
                $log->save();
            } else {
                if($updated_total_first_prob > 100) {
                    $error = "The maximum first probability you can input for this data {$allow_first_prob}%.";
                } else {
                    $error = "The maximum second probability you can input for this data {$allow_second_prob}%.";
                }
            }

            session()->flash('success', 'Configuration saved!');
        } catch (\Throwable $th) {
            $error = $th->getMessage();
        }

        return redirect('/system/spin-config')->with('error', $error);
    }
}
