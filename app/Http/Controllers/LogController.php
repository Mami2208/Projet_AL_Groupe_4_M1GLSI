<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class LogController extends Controller
{
    public function show()
    {
        $logFile = storage_path('logs/laravel.log');
        
        if (!File::exists($logFile)) {
            return response('Log file not found', 404);
        }
        
        return response()->file($logFile, ['Content-Type' => 'text/plain']);
    }
}
