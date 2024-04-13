<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ImageUploadHelpers;
use App\Http\Controllers\Controller;
use App\Models\LogAdmin;
use App\Models\Setting;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $setting_title = Setting::where('set_for', 'seo_title')->first();
        $setting_seo_key_word = Setting::where('set_for', 'seo_key_words')->first();
        $setting_seo_description = Setting::where('set_for', 'seo_description')->first();
        $setting_hyperlink = Setting::where('set_for', 'hyperlink')->first();
        $setting_ip_white_list = Setting::where('set_for', 'ip_white_list')->first();
        $setting_favicon = Setting::where('set_for', 'favicon')->first();
        $setting_win_modal = Setting::where('set_for', 'win_text')->first();
        $setting_lose_modal = Setting::where('set_for', 'lose_text')->first();
        $setting_guide_modal = Setting::where('set_for', 'guide_text')->first();
        $setting_wellcome_modal = Setting::where('set_for', 'wellcome_text')->first();
        return view('admin.setting.index', [
            'setting_title' => $setting_title,
            'setting_seo_key_word' => $setting_seo_key_word,
            'setting_seo_description' => $setting_seo_description,
            'setting_hyperlink' => $setting_hyperlink,
            'setting_ip_white_list' => $setting_ip_white_list,
            'setting_favicon' => $setting_favicon,
            'setting_win_modal' => $setting_win_modal,
            'setting_lose_modal' => $setting_lose_modal,
            'setting_guide_modal' => $setting_guide_modal,
            'setting_wellcome_modal' => $setting_wellcome_modal,
        ]);
    }

    public function update(Request $request)
    {
        $settingsData = $request->only(['seo_title', 'seo_key_words', 'seo_description', 'hyperlink', 'ip_white_list', 'favicon', 'logo', 'win_text', 'lose_text', 'guide_text', 'wellcome_text']);
        $notif_state = 'warning';
        $notif = 'No changes.';
        // dd($request->guide_text);
        try {
            foreach ($settingsData as $key => $value) {
                $setting = Setting::where('set_for', $key)->firstOrFail();
                if ($setting->value !== $value) {
                    $setting->value = $value;

                    $notif_state = 'success';
                    $notif = 'Settings updated successfully.';

                    $log = new LogAdmin();
                    $log->uid = Auth::user()->id;
                    $log->act_on = "Setting Apps";
                    $log->activity = "Update setting website";
                    $log->detail = "Update on = " . $key;
                    $log->save();

                    $setting->updated_by = Auth::user()->id;
                    $setting->save();
                }
            }

            if ($request->hasFile('favicon')) {
                $faviconPath = ImageUploadHelpers::upload($request->file('favicon'), 'site/');
                $setting = Setting::where('set_for', 'favicon')->firstOrFail();
                $setting->value = $faviconPath;
                $setting->save();

                $notif = 'Settings updated successfully.';
            }

            return redirect()->back()->with($notif_state, $notif);
        } catch (\Exception $error) {
            return redirect()->back()->with('error', 'Sorry, input value is required and cannot be empty.');
        }
    }
}
