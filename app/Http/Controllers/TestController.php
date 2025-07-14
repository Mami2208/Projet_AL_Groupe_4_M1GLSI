<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    public function showTestForm()
    {
        return view('test');
    }

    public function testPost(Request $request)
    {
        return response()->json([
            'status' => 'success',
            'data' => $request->all(),
            'session' => session()->all(),
            'token' => csrf_token(),
            'session_id' => session()->getId()
        ]);
    }
}
