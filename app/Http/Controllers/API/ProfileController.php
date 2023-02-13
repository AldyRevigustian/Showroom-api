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
        $age = $request->age;
        $birthday = $request->birthday;
        $residence = $request->residence;
        $description = $request->description;
        $gender = $request->gender;
        $image = $request->image;
        $is_ng_nick_name = $request->is_ng_nick_name;

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
}
