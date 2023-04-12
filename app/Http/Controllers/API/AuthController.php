<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Session;
use Symfony\Component\DomCrawler\Crawler;

class AuthController extends Controller
{

    public function newSession()
    {
        // Cookies
        $client = new Client();
        $response = $client->get('https://www.showroom-live.com');

        $cook = $response->getHeader('set-cookie');
        $cookies_id = explode('; ', $cook[0])[0];

        // Csrf
        $html = $response->getBody()->getContents();
        $crawler = new Crawler($html);

        $csrfs  = [];
        $crawler->filter('input[name="csrf_token"]')->each(function (Crawler $node) use (&$csrfs) {
            $csrfs[] = $node->attr('value');
        });
        $csrf = $csrfs[0];

        return ['cookies_id' => $cookies_id, 'csrf' => $csrf];
    }

    public function accountProfile($user_id)
    {
        $client = new Client();
        $profile = $client->get("https://www.showroom-live.com/api/user/profile?user_id={$user_id}");
        return  json_decode($profile->getBody()->getContents());
    }

    public function login(Request $request)
    {
        $client = new Client();

        $cookies_id = $request->cookies_sr_id;
        $csrf = $request->csrf_token;

        if (!$cookies_id) {
            $sess = $this->newSession();
            $cookies_id = $sess['cookies_id'];
            $csrf = $sess['csrf'];
        }

        $login = $client->post('https://www.showroom-live.com/user/login', [
            'headers' => [
                'Cookie' => $cookies_id,
            ],
            'form_params' => [
                'csrf_token' => $csrf,
                'account_id' => $request->account_id,
                'password' => $request->password,
                'captcha_word' => $request->captcha_word,
            ],
        ]);

        if ($login->getStatusCode() == '200') {

            $cook = $login->getHeader('Set-Cookie');
            $cookies_login = explode('; ', $cook[0])[0];
            $loginJson = json_decode($login->getBody()->getContents());

            if ($loginJson->error ?? '') {
                return response()->json(
                    [
                        'session' => [
                            'cookies sr_id' => $cookies_id,
                            'cookie_login_id' => $cookies_login,
                            'csrf_token' => $csrf,
                        ],
                        'user' => $loginJson,
                    ]
                );
            }

            return response()->json(
                [
                    'session' => [
                        'cookies sr_id' => $cookies_id,
                        'cookie_login_id' => $cookies_login,
                        'csrf_token' => $csrf,
                    ],
                    'user' => $loginJson,
                    'profile' => $this->accountProfile($loginJson->user_id)
                ]
            );
        }
    }
    public function register(Request $request)
    {
        $client = new Client();

        $cookies_id = $request->cookies_sr_id;
        $csrf = $request->csrf_token;

        if (!$cookies_id) {
            $sess = $this->newSession();
            $cookies_id = $sess['cookies_id'];
            $csrf = $sess['csrf'];
        }

        $register = $client->post('https://www.showroom-live.com/user/sign_up', [
            'headers' => [
                'Cookie' => $cookies_id,
            ],
            'form_params' => [
                'csrf_token' => $csrf,
                'account_id' => $request->account_id,
                'name' => $request->name,
                'password' => $request->password,
                'password_confirm' => $request->password_confirm,
                'captcha_word' => $request->captcha_word,
                'avatar_id' => $request->avatar_id,
            ],
        ]);

        if ($register->getStatusCode() == '200') {
            $registerJson = json_decode($register->getBody()->getContents());

            if ($registerJson->error ?? '') {
                return response()->json(
                    $registerJson,
                );
            }

            return response()->json([
                "status" => $registerJson,
                "profile" => $this->accountProfile($registerJson->user_id)
            ]);
        }
    }
}
