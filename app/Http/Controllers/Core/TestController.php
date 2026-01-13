<?php

namespace App\Http\Controllers\Core;

use App\Http\Controllers\Controller;
use App\Services\HelperService;
use Illuminate\Support\Facades\Http;

class TestController extends Controller
{
    public function requestSocketApi()
    {
        app(HelperService::class)->requestSocket([1,2]);

        return response()->json([
            'message' => 'Sent'
        ]);
    }
}
