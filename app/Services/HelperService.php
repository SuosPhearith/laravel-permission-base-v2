<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class HelperService
{
    // SENT SOCKET TRIGGER TO USER BASE ON USER ID [1,2,3]
    public function requestSocket(array $userIds): void
    {
        $url = config('services.sever_socket.url') . '/send-signal';
        Http::withBasicAuth(
            config('services.sever_socket.basic_user'),
            config('services.sever_socket.basic_pass')
        )->post($url, [
            'user_ids' => $userIds,
        ]);
    }
}
