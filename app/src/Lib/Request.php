<?php

namespace App\Lib;

class Request
{
    /** @var string */
    protected $basePath;

    /** @var string */
    protected $requestPath;

    /** @var array */
    protected $bodyParams = [];

    /** @var array */
    protected $routeParams = [];

    /** @var array */
    protected $allParams;

    /**
     * Request constructor.
     */
    public function __construct()
    {
        $this->processPath();
        $this->parseBody();
    }

    /**
     * Returns host name.
     * @return string
     */
    public function getHost()
    {
        return $_SERVER['HTTP_HOST'];
    }

    /**
     * Returns request method.
     * @return string
     */
    public function getRequestMethod()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    /**
     * Returns base path.
     * For a request with explicit script name, this script name is included in base path:
     *   request: /subfolder/index.php/hi
     *   base path: /subfolder/index.php
     * If script name is omitted, it will not be included.
     * @return string
     */
    public function getBasePath()
    {
        return $this->basePath;
    }

    /**
     * Returns request path.
     * Example:
     *   request: /subfolder/index.php/hi
     *   path: /hi
     * @return string
     */
    public function getRequestPath()
    {
        return $this->requestPath;
    }

    /**
     * Searches and returns parameter from both query (GET) and body (POST or JSON).
     * A dot syntax can be used to get to a nested value.
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function getParam($key, $default = null)
    {
        $result = arrayGetItem($this->bodyParams, $key);
        if (isset($result)) {
            return $result;
        }

        $result = arrayGetItem($_GET, $key);
        if (isset($result)) {
            return $result;
        }

        return $default;
    }

    /**
     * Returns a combined array of parameters from query (GET) and body (POST or JSON).
     * @param array|null $takeOnly
     * @return array
     */
    public function getParams($takeOnly = null)
    {
        if (!isset($this->allParams)) {
            $this->allParams = array_replace($_GET, $this->bodyParams);
        }

        return arrayTakeOnly($this->allParams, $takeOnly);
    }

    /**
     * Returns query (GET) parameter.
     * A dot syntax can be used to get to a nested value.
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function getQueryParam($key, $default = null)
    {
        $result = arrayGetItem($_GET, $key);

        return isset($result) ? $result : $default;
    }

    /**
     * Returns an array of all query (GET) parameters.
     * @param array|null $takeOnly
     * @return mixed
     */
    public function getQueryParams($takeOnly = null)
    {
        return arrayTakeOnly($_GET, $takeOnly);
    }

    /**
     * Returns POST or JSON body parameter.
     * A dot syntax can be used to get to a nested value.
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function getBodyParam($key, $default = null)
    {
        $result = arrayGetItem($this->bodyParams, $key);

        return isset($result) ? $result : $default;
    }

    /**
     * Returns an array of all body (POST or JSON) parameters.
     * @param array|null $takeOnly
     * @return array
     */
    public function getBodyParams($takeOnly = null)
    {
        return arrayTakeOnly($this->bodyParams, $takeOnly);
    }

    /**
     * Returns a parameter (an argument) from a route.
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function getRouteParam($key, $default = null)
    {
        return isset($this->routeParams[$key]) ? $this->routeParams[$key] : $default;
    }

    /**
     * Returns all route parameters.
     * @param array|null $takeOnly
     * @return array
     */
    public function getRouteParams($takeOnly = null)
    {
        return arrayTakeOnly($this->routeParams, $takeOnly);
    }

    /**
     * Inject route parameters.
     * @param array $routeParams
     */
    public function setRouteParams(array $routeParams)
    {
        $this->routeParams = $routeParams;
    }

    /**
     * Checks if current request matches a specified string.
     * @param string $requestPath A string to compare.
     * @param bool $exact Exact match is expected.
     * @return bool
     */
    public function match($requestPath, $exact = false)
    {
        $currentPath = ltrim($this->getRequestPath(), '/');
        $requestPath = ltrim($requestPath, '/');

        if ($exact) {
            return $currentPath === $requestPath;
        }

        return $currentPath === $requestPath || strpos($currentPath, rtrim($requestPath, '/') . '/') === 0;
    }

    /**
     * Extract base path and remove it from the request path.
     */
    private function processPath()
    {
        $path = rawurldecode(strtok($_SERVER['REQUEST_URI'], '?'));

        $basePath = $_SERVER['SCRIPT_NAME'];
        if (stripos($path, $basePath) !== 0) {
            $basePath = dirname($basePath);
            if (stripos($path, $basePath) !== 0 || $basePath === '/') {
                $basePath = '';
            }
        }

        $this->basePath = $basePath;
        $this->requestPath = substr($path, strlen($basePath));
    }

    /**
     * Parses request body and extracts POST and JSON data.
     */
    protected function parseBody()
    {
        if (isset($_SERVER['CONTENT_TYPE']) &&
            stripos($_SERVER['CONTENT_TYPE'], 'application/json') === 0
        ) {
            $this->bodyParams = json_decode(file_get_contents('php://input'), true);
        } else {
            $this->bodyParams = $_POST;
        }
    }
}
