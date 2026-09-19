<?php

class API
{
    private static function send(string $url, array $options)
    {
        $json = [];
        $ch = curl_init("http://apache{$url}");

        curl_setopt_array($ch, $options);

        $response = curl_exec($ch);

        if ($response === false) {
            $json['error'] = curl_error($ch);
            $json['success'] = false;
        } else {
            $json['success'] = true;
            $json['data'] = $response;
        }

        return json_encode($json);
    }

    public static function get(string $url): string
    {
        return API::send($url, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Host: api.localhost',
            ],
        ]);
    }

    public static function post(string $url, array $data): string
    {
        return API::send($url, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => [
                'Host: api.localhost',
                'Content-Type: application/json',
                'Accept: application/json',
            ],
            CURLOPT_RETURNTRANSFER => true,
        ]);
    }
}
