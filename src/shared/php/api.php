<?php

class API
{
    public static function call(string $url = '/'): string
    {
        $json = [];
        $ch = curl_init("http://apache{$url}");

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Host: api.localhost',
            ],
        ]);

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
}
