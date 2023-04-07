<?php

namespace Modules\Adwords\Services;

use Firebase\JWT\JWT;
use GuzzleHttp\Client;

class DataService
{
    public function post($endpoint, $form = [])
    {
        $client = new Client();
        $response = $client->post(config('service.adwords.url') . '/' . $endpoint, [
            'form_params' => $form,
            'headers'     => [
                'Authorization' => 'Bearer ' . $this->jwt()
            ]
        ]);

        return $this->format($response);
    }

    public function get($endpoint, $collect = true)
    {
        $client = new Client();
        $response = $client->get(config('service.adwords.url') . '/' . $endpoint, [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->jwt()
            ]
        ]);

        return $this->format($response, $collect);
    }

    private function format($response, $collect = true)
    {
        $json = json_decode($response->getBody()->getContents());

        return $collect ? collect($json) : $json;
    }

    public function jwt()
    {
        $privateKey = config('service.adwords.public');

        $token = [
            "iss" => "myjobquote.co.uk",
            "aud" => "adwords.serices.myjobquote.co.uk",
            "iat" => time(),
            "nbf" => time(),
            "exp" => time() + 120
        ];

        return JWT::encode($token, $privateKey, 'RS256');
    }
}