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
     * Возвращает имя хоста
     * @return string
     */
    public function getHost()
    {
        return $_SERVER['HTTP_HOST'];
    }

    /**
     * Возвращает метод запроса (GET, POST и т.д.).
     * @return string
     */
    public function getRequestMethod()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    /**
     * Возвращает базовый путь.
     * Для запроса с именем скрипта имя скрипта включено в базовый путь
     * (например, для /subfolder/index.php/hi это будет /subfolder/index.php).
     * Для запроса без имени скрипта имя скрипта не включено в базовый путь.
     *
     * @return string
     */
    public function getBasePath()
    {
        return $this->basePath;
    }

    /**
     * Возвращает путь запроса (для /subfolder/index.php/hi это будет /hi).
     * @return string
     */
    public function getRequestPath()
    {
        return $this->requestPath;
    }

    /**
     * Возвращает параметр из GET и POST.
     * @param string $key Имя параметра.
     * @param mixed $default Значение по умолчанию.
     * @return mixed
     */
    public function getParam($key, $default = null)
    {
        $result = getArrayItem($this->bodyParams, $key);
        if (isset($result)) {
            return $result;
        }

        $result = getArrayItem($_GET, $key);
        if (isset($result)) {
            return $result;
        }

        return $default;
    }

    /**
     * Возвращает комбиниированный массив GET- и POST- (или JSON-) параметров.
     * @param array|null $takeOnly
     * @return array
     */
    public function getParams($takeOnly = null)
    {
        if (!isset($this->allParams)) {
            $this->allParams = array_replace($_GET, $this->bodyParams);
        }

        return $this->filterParams($this->allParams, $takeOnly);
    }

    /**
     * Возвращает GET-параметр.
     * @param string $key Имя параметра.
     * @param mixed $default Значение по умолчанию.
     * @return mixed
     */
    public function getQueryParam($key, $default = null)
    {
        $result = getArrayItem($_GET, $key);

        return isset($result) ? $result : $default;
    }

    /**
     * Возвращает массив всех GET-параметров.
     * @param array|null $takeOnly
     * @return mixed
     */
    public function getQueryParams($takeOnly = null)
    {
        return $this->filterParams($_GET, $takeOnly);
    }

    /**
     * Возвращает POST- или JSON-параметр.
     * @param string $key Имя параметра.
     * @param mixed $default Значение по умолчанию.
     * @return mixed
     */
    public function getBodyParam($key, $default = null)
    {
        $result = getArrayItem($this->bodyParams, $key);

        return isset($result) ? $result : $default;
    }

    /**
     * Возвращает массив всех POST- или JSON-параметров.
     * @param array|null $takeOnly
     * @return array
     */
    public function getBodyParams($takeOnly = null)
    {
        return $this->filterParams($this->bodyParams, $takeOnly);
    }

    /**
     * Возвращает параметр (аргумент) из роута.
     * @param string $key Имя параметра.
     * @param mixed $default Значение по умолчанию.
     * @return mixed
     */
    public function getRouteParam($key, $default = null)
    {
        return isset($this->routeParams[$key]) ? $this->routeParams[$key] : $default;
    }

    public function getRouteParams($takeOnly = null)
    {
        return $this->filterParams($this->routeParams, $takeOnly);
    }

    /**
     * Устанавливает значения аргументов из роута.
     * @param array $routeParams
     */
    public function setRouteParams(array $routeParams)
    {
        $this->routeParams = $routeParams;
    }

    /**
     * Проверяет, соответствует ли текущий запрос заданной строке.
     * @param string $requestPath Строка для проверки.
     * @param bool $exact Exact match.
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
     * Выделяет базовый путь и убирает его из пути запроса.
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
     * Парсим тело запроса (POST- или JSON-данные)
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

    protected function filterParams($params, $takeOnly = null)
    {
        if (is_array($takeOnly)) {
            return array_intersect_key($params, array_flip($takeOnly));
        }

        return $params;
    }
}
