<?php

class Router
{
    private $urls = [];

    public function register(string $url, string $path)
    {
        $this->urls[$url] = $path;
    }

    public function dispatch()
    {
        $request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $request = rtrim($request, '/') ?: '/';

        if (array_key_exists($request, $this->urls)) {
            require_once $this->urls[$request];
        } else {
            echo "<h1>Failed to find a page to dispatch to!</h1>";
        }
    }
}
