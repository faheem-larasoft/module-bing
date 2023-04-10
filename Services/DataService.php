<?php

namespace Modules\Bing\Services;

use Firebase\JWT\JWT;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class DataService
{
    public function post($endpoint, $form = [])
    {
        try {
            $client = new Client();
            $response = $client->post(config('service.bing.url') . '/' . $endpoint, [
                'form_params' => $form,
                'headers'     => [
                    'Authorization' => 'Bearer ' . $this->jwt()
                ]
            ]);

            return $this->format($response);

        } catch (\Exception $e) {
            $json_error = json_decode($e->getResponse()->getBody()->getContents());
            $error = $json_error->message ?? 'An error occurred while fetching data from Bing service.';
            Log::info($error);
        }
    }

    public function get($endpoint, $collect = true)
    {
        try {
            $client = new Client();
            $response = $client->get(config('service.bing.url') . '/' . $endpoint, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->jwt()
                ]
            ]);

            return $this->format($response, $collect);

        } catch (\Exception $e) {
            $json_error = json_decode($e->getResponse()->getBody()->getContents());
            $error = $json_error->message ?? 'An error occurred while fetching data from Bing service.';
            Log::info($error);
        }
    }

    private function format($response, $collect = true)
    {
        $json = json_decode($response->getBody()->getContents());

        return $collect ? collect($json) : $json;
    }

    public function jwt()
    {
        $privateKey = config('service.bing.public');

        $token = [
            "iss" => "myjobquote.co.uk",
            "aud" => "bing.serices.myjobquote.co.uk",
            "iat" => time(),
            "nbf" => time(),
            "exp" => time() + 120
        ];

        return JWT::encode($token, $privateKey, 'RS256');
    }
}