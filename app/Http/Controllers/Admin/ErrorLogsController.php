<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ErrorLog;
use Illuminate\Http\Request;

class ErrorLogsController extends Controller
{
    public function index()
    {
        $form = null;
        $logs = ErrorLog::orderBy('id', 'desc')->take(100)->paginate(20);
        return view('admin.error.index', [
            'logs' => $logs,
            'form' => $form,
        ]);
    }
}
