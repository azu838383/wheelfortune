<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileUpdateRequest;
use App\Models\LogAdmin;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('admin.profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());
        $notif_state = 'warning';
        $notif = 'No changes.';

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }



        $check = User::where('id', Auth::user()->id)->first();

        if ($check->name === $request->user()->name && $check->username === $request->user()->username && $check->email === $request->user()->email) {
            $notif_state = 'warning';
            $notif = 'No changes.';
        } else {
            $request->user()->save();
            $notif_state = 'success';
            $notif = "Profile update success";
            
            $log = new LogAdmin();
            $log->uid = Auth::user()->id;
            $log->act_on = "Profile";
            $log->activity = "Update profile";
            $log->detail = "Profile update success";
            $log->save();
        }

        return Redirect::route('profile.edit')->with($notif_state, $notif);
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
