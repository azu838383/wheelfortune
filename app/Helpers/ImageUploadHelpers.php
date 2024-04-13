<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageUploadHelpers {

    public static function upload($data, $location)
    {
        $fileName = Str::random(20).'.'.$data->getClientOriginalExtension();
        $path = 'uploads/'.$location.$fileName;
        $process = Storage::disk('public')->put($path, file_get_contents($data),'public');

        return $path;
    }

}