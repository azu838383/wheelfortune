<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LogAdmin;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Validation\Rule;

class UserManagement extends Controller
{
    public function index()
    {
        $form = null;
        $users = User::orderBy('id', 'asc')->get();
        $logs = LogAdmin::orderBy('id', 'desc')->take(100)->paginate(10);
        return view('admin.management.index', [
            'users' => $users,
            'logs' => $logs,
            'form' => $form,
        ]);
    }

    public function destroy(User $user)
    {
        $user->delete();
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'id' => 'required',
            'name' => 'required',
            'username' => [
                'required',
                'string',
                'max:16',
                'regex:/^[a-zA-Z0-9]+$/',
                Rule::unique('users')->ignore($request->id),
            ],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique('users')->ignore($request->id),
            ],
            'level' => 'required',
        ]);

        $data = User::find($request->id);
        $data->name = $request->name;
        $data->username = $request->username;
        $data->email = $request->email;
        $data->level = $request->level;

        if ($request->has('password')) {
            $data->password = bcrypt($request->password);
        }

        $data->save();
        return redirect('/system/admin/management');
    }
}
