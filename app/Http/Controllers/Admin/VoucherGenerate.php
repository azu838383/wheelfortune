<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ImageUploadHelpers;
use App\Http\Controllers\Controller;
use App\Models\LogAdmin;
use App\Models\Platform;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\UserVoucher;

class VoucherGenerate extends Controller
{
    public function index()
    {
        $form = null;
        $start_date = null;
        $end_date = null;
        $bydatemode = false;
        $platform = Platform::orderBy('id', 'desc')->paginate(10);
        $voucher = UserVoucher::orderBy('id', 'desc')->paginate(20);
        return view('admin.voucher.index', [
            'voucher' => $voucher,
            'platform' => $platform,
            'form' => $form,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'bydatemode' => $bydatemode,
        ]);
    }

    public function search(Request $request)
    {
        $error = null;
        $form = $request->search;
        $start_date = null;
        $end_date = null;
        $bydatemode = false;
        try {
            $this->validate($request, [
                'search' => 'required',
            ]);
            $voucher = UserVoucher::where('username', 'LIKE', '%' . $form . '%')->orWhere('code_voucher', 'LIKE', '%' . $form . '%')->orderBy('id', 'desc')->paginate(20);
        } catch (\Throwable $th) {
            $error = $th->getMessage();
            return redirect('/system/voucher');
        }
        $platform = Platform::orderBy('id', 'desc')->paginate(10);

        return view('admin.voucher.index', [
            'voucher' => $voucher,
            'error' => $error,
            'form' => $form,
            'platform' => $platform,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'bydatemode' => $bydatemode,
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
        $bydatemode = true;

        try {
            $voucher = UserVoucher::where('created_at', '>=', $start_date_time)->where('created_at', '<=', $end_date_time)->orderBy('id', 'desc')->get();
        } catch (\Throwable $th) {
            $error = $th->getMessage();
            return redirect('/system/voucher');
        }
        $platform = Platform::orderBy('id', 'desc')->paginate(10);

        return view('admin.voucher.index', [
            'voucher' => $voucher,
            'error' => $error,
            'form' => $form,
            'platform' => $platform,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'bydatemode' => $bydatemode,
        ]);
    }

    public function store(Request $request)
    {
        $error = null;
        try {
            $this->validate($request, [
                'username' => ['required', 'regex:/^[a-zA-Z0-9_]+$/'],
                'platform_id' => 'required',
                'code_voucher' => 'required',
                'set_prob' => 'required',
            ]);

            $data = new UserVoucher();
            $data->username = $request->username;
            $data->platform_id = $request->platform_id;
            $data->set_prob = $request->set_prob;
            $data->code_voucher = $request->code_voucher;
            $data->issued_by = Auth::user()->id;
            $data->save();

            $log = new LogAdmin();
            $log->uid = Auth::user()->id;
            $log->act_on = "Voucher Generate";
            $log->activity = "Voucher issued";
            $log->detail = "Voucher issued with code = " . $request->code_voucher;
            $log->save();

            session()->flash('success', 'Voucher issued successfully!');
        } catch (\Throwable $th) {
            $error = $th->getMessage();
        }

        return redirect('/system/voucher')->with('error', $error);
    }

    public function update(Request $request)
    {
        $error = null;
        try {
            $this->validate($request, [
                'id' => 'required',
                'username' => ['required', 'regex:/^[a-zA-Z0-9_]+$/'],
                'platform_id' => 'required',
                'code_voucher' => 'required',
                'set_prob' => 'required',
            ]);

            $data = UserVoucher::find($request->id);
            $data->username = $request->username;
            $data->platform_id = $request->platform_id;
            $data->set_prob = $request->set_prob;
            $data->code_voucher = $request->code_voucher;
            $data->updated_by = Auth::user()->id;
            $data->save();

            $log = new LogAdmin();
            $log->uid = Auth::user()->id;
            $log->act_on = "Voucher Generate";
            $log->activity = "Voucher updated";
            $log->detail = "Updated voucher with data id = " . $request->id;
            $log->save();

            session()->flash('success', 'Voucher update successfully!');
        } catch (\Throwable $th) {
            $error = $th->getMessage();
        }

        return redirect('/system/voucher')->with('error', $error);
    }

    public function destroy(UserVoucher $voucher)
    {
        $error = null;
        try {
            $voucher->delete();

            $log = new LogAdmin();
            $log->uid = Auth::user()->id;
            $log->act_on = "Voucher Generate";
            $log->activity = "Voucher deleted";
            $log->detail = "Delete voucher data id = " . $voucher->id;
            $log->save();

            session()->flash('success', 'Voucher deleted successfully!');
        } catch (\Throwable $th) {
            $error = $th->getMessage();
            session()->flash('error', $error);
        }
    }

    public function store_platform(Request $request)
    {
        $error = null;
        try {
            $this->validate($request, [
                'name' => 'required',
            ]);

            $data = new Platform();
            $data->name = $request->name;
            $data->is_active = $request->is_active ? true : false;

            if ($request->hasFile('logo')) {
                $path = ImageUploadHelpers::upload($request->file('logo'), 'platform/');
                $data->logo = $path;
            }
            $data->save();

            $log = new LogAdmin();
            $log->uid = Auth::user()->id;
            $log->act_on = "Platform";
            $log->activity = "Register Platform";
            $log->detail = "New platform registered with name = " . $request->name;
            $log->save();

            session()->flash('success', 'Platform registered!');
        } catch (\Throwable $th) {
            $error = $th->getMessage();
        }

        return redirect('/system/voucher')->with('error', $error);
    }

    public function update_platform(Request $request)
    {
        $error = null;
        try {
            $this->validate($request, [
                'id' => 'required',
                'name' => 'required',
            ]);

            $data = Platform::find($request->id);
            $data->name = $request->name;
            $data->is_active = $request->is_active ? true : false;
            $data->save();

            $log = new LogAdmin();
            $log->uid = Auth::user()->id;
            $log->act_on = "Platform";
            $log->activity = "Update Platform";
            $log->detail = "Update platform with name = " . $request->name;
            $log->save();

            session()->flash('success', 'Platform updated!');
        } catch (\Throwable $th) {
            $error = $th->getMessage();
        }

        return redirect('/system/voucher')->with('error', $error);
    }

    public function destroy_platform(Platform $platform)
    {
        $error = null;
        try {
            $platform->delete();

            $log = new LogAdmin();
            $log->uid = Auth::user()->id;
            $log->act_on = "Platform";
            $log->activity = "Platform deleted";
            $log->detail = "Delete platform " . $platform->name;
            $log->save();

            session()->flash('success', 'Platform deleted successfully!');
        } catch (\Throwable $th) {
            $error = $th->getMessage();
            session()->flash('error', $error);
        }
    }
}
