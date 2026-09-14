<?php

class Router
{
    /**
     * urls
     * 
     * An array of URLs to match against and an action to perform if the match is successful
     *
     * @var array
     */
    private array $urls = [];

    /**
     * get
     * 
     * A function that registers a given URL to a given GET request
     *
     * @param string $url The URL that is being registered
     * @param string|callable $action The action that should be performed when matched with
     * @return void
     */
    public function get(string $url, string|callable $action): void
    {
        $this->urls['GET'][$url] = $action;
    }

    /**
     * get
     * 
     * A function that registers a given URL to a given POST request
     *
     * @param string $url The URL that is being registered
     * @param string|callable $action The action that should be performed when matched with
     * @return void
     */
    public function post(string $url, string|callable $action): void
    {
        $this->urls['POST'][$url] = $action;
    }

    /**
     * get
     * 
     * A function that registers a given URL to a given PUT request
     *
     * @param string $url The URL that is being registered
     * @param string|callable $action The action that should be performed when matched with
     * @return void
     */
    public function put(string $url, string|callable $action): void
    {
        $this->urls['PUT'][$url] = $action;
    }

    /**
     * delete
     * 
     * A function that registers a given URL to a given DELETE request
     *
     * @param string $url The URL that is being registered
     * @param string|callable $action The action that should be performed when matched with
     * @return void
     */
    public function delete(string $url, string|callable $action): void
    {
        $this->urls['DELETE'][$url] = $action;
    }

    /**
     * dispatch
     * 
     * This function matches the current URL against the set routes and dispatches if a route can be found
     *
     * @return void
     */
    public function dispatch()
    {
        $request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $method = $_SERVER['REQUEST_METHOD'];
        $request = rtrim($request, '/') ?: '/';

        //iterate through each URL
        foreach ($this->urls[$method] as $url => $action) {

            $pattern = preg_quote($url, '#');

            $pattern = preg_replace(
                '/\\\\\{[^}]+\\\\\}/',
                '([^/]+)',
                $pattern
            );

            $pattern = '#^' . $pattern . '$#';

            if (preg_match($pattern, $request, $matches)) {

                array_shift($matches);

                // URL-decode route parameters
                $matches = array_map('rawurldecode', $matches);

                //action is a string (i.e. another file to load)
                if (is_string($action)) {
                    $params = $matches;
                    require $action;
                }
                //action is a callable (i.e. a function to call)
                elseif (is_callable($action)) {
                    $action(...$matches);
                }
                //we could not find a valid route to use
                //in the future, this should be a 404 page
                else {
                    throw new Exception(
                        'Failed to find a valid type for action'
                    );
                }

                return;
            }
        }

        echo 'Error: 404 Not Found';
    }
}
