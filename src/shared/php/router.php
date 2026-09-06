<?php

class Router
{
    private $urls = [];

    public function register(string $url, string|callable $action)
    {
        $this->urls[$url] = $action;
    }

    public function dispatch()
    {
        $request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $request = rtrim($request, '/') ?: '/';

        if (array_key_exists($request, $this->urls)) {

            $action = $this->urls[$request];

            if (is_string($action)) {
                require_once $action;
            } else if (is_callable($action)) {
                $action();
            } else {
                throw new Exception("Failed to find a valid type for action");
            }
        } else {
            echo "<h1>Failed to find a page to dispatch to!</h1>";
        }
    }
}
