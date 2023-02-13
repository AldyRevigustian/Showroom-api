<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function update_profile(Request $request)
    {
        $cookies_id = $request->cookies_id;
        $csrf_token = $request->csrf_token;

        $name = $request->name;
        $user_id = $request->user_id;
        $residence = $request->residence;
        $description = $request->description;

        $client = new Client();

        $update = $client->post('https://www.showroom-live.com/api/user/detail/update', [
            'headers' => [
                'Cookie' => $cookies_id,
            ],
            'form_params' => [
                'csrf_token' => $csrf_token,
                'name' => $name,
                'user_id' => $user_id,
                'residence' => $residence,
                'description' => $description,
            ],
        ]);

        if ($update->getStatusCode() == '200') {

            $updateJson = json_decode($update->getBody()->getContents());

            if (isset($updateJson->ok)) {
                return response()->json(
                    [
                        'message' => 'Berhasil Mengupdate Profile'
                    ]
                );
            }

            return response()->json(
                [
                    'message' => $updateJson->error_user_msg
                ]
            );
        }
    }

    public function update_avatar(Request $request)
    {
        $cookies_id = $request->cookies_id;
        $csrf_token = $request->csrf_token;
        $avatar_id = $request->avatar_id;

        $client = new Client();
        $update = $client->post('https://www.showroom-live.com/api/user/update_user_avatar', [
            'headers' => [
                'Cookie' => $cookies_id,
            ],
            'form_params' => [
                'csrf_token' => $csrf_token,
                'avatar_id' => $avatar_id,
            ],
        ]);

        if ($update->getStatusCode() == '200') {
            $updateJson = json_decode($update->getBody()->getContents());

            if (isset($updateJson->ok)) {
                return response()->json(
                    [
                        'message' => 'Berhasil Mengupdate Avatar'
                    ]
                );
            }
            return response()->json(
                [
                    'message' => 'Gagal Mengupdate Avatar'
                ]
            );
        }
    }
}
