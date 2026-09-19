<?php

class UrlValidator
{
    public static function validate(array $allowedOrigins)
    {
        $handle = false;
        $origin = $_SERVER['HTTP_ORIGIN'] ?? '';

        //if this url is allowed to send requests, then we allow cross origin
        if (in_array($origin, $allowedOrigins, true)) {
            header("Access-Control-Allow-Origin: $origin");
            header('Vary: Origin');
            $handle = true;
        } else if ($origin === '') {
            $handle = true;
        }

        //allow all request types to be sent
        header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');

        //if this is an options request, we do not need to handle it any more than returning a 204
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(204);
            $handle = false;
        }

        return $handle;
    }
}
