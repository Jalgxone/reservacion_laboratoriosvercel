<?php
class App
{
    protected $controller = 'AuthController';
    protected $method = 'index';
    protected $params = [];

    public function __construct()
    {
        $url = $this->parseUrl();

        if (isset($url[0]) && $url[0] !== '') {
            $controllerName = ucwords($url[0]) . 'Controller';
            $controllerFile = __DIR__ . '/../app/Controllers/' . $controllerName . '.php';
            if (file_exists($controllerFile)) {
                require_once $controllerFile;
                $this->controller = new $controllerName();
                unset($url[0]);
            }
        } else {
            $defaultFile = __DIR__ . '/../app/Controllers/' . $this->controller . '.php';
            if (file_exists($defaultFile)) {
                require_once $defaultFile;
                $this->controller = new $this->controller();
            }
        }

        if (isset($url[1])) {
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            }
        }

        $this->params = $url ? array_values($url) : [];

        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    public function parseUrl()
    {
        if (isset($_GET['url'])) {
            return explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
        return [];
    }
}
