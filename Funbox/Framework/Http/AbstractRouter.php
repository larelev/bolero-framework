<?php

namespace Funbox\Framework\Http;

use Funbox\Framework\Registry\Registry;

class AbstractRouter
{

    protected $translation = '';
    protected $routes = [];
    protected $requestType = 'web';
    protected $appRoot = "";
    protected $documentRoot = "";

    private bool $_isFound;

    public function __construct(
        public readonly Request $request,
        public readonly Response $response
    ) {
        $this->documentRoot = $this->request->getServer('DOCUMENT_ROOT');
        $this->appRoot = APP_PATH;
        $this->isFound = false;
    }

    public function getTranslation(): string
    {
        return $this->translation;
    }

    public function getRequestType(): string
    {
        return $this->requestType;
    }

    public function isFound(): bool
    {
        return $this->_isFound;
    }

    public function match(): string
    {
        $result = 'web';

        if ($this->routes()) {
            foreach ($this->routes as $key => $value) {
                $result = $key;
                $this->requestType = $key;

                $methods = $value;
                $method = strtolower($this->request->getServer('REQUEST_METHOD'));

                if (!isset($methods[$method])) {
                    continue;
                }

                $routes = $methods[$method];
                $url = $this->request->getServer('REQUEST_URI');
                foreach ($routes as $key => $value) {
                    $matches = \preg_replace('@' . $key . '@', $value, $url);

                    if ($matches === $url) {
                        continue;
                    }

                    $this->_isFound = true;
                    $this->requestType = $key;
                    $this->translation = $matches;

                    $this->componentIsInternal = substr($this->translation, 0, 1) == '@';
                    $baseurl = parse_url($this->translation);
                    $this->dirName = pathinfo($this->translation, PATHINFO_DIRNAME);

                    $this->path = $this->appRoot . $baseurl['path'];

                    $this->parameters = [];
                    if (isset($baseurl['query'])) {
                        parse_str($baseurl['query'], $this->parameters);
                    }

                    return $result;

                }

            }
        }

        if ($this->translation === '') {
            $this->requestType = 'web';
            $result = 'web';
        }

        return $result;
    }

    public function routes(): array
    {
        $routesArray = Registry::item('routes');

        if (count($routesArray) === 0 && file_exists($this->documentRoot . 'routes.json')) {
            $routesFile = file_get_contents($this->documentRoot . 'routes.json');

            if (strlen($routesFile) === 0) {
                return false;
            }

            $routesArray = json_decode($routesFile, true);
        } elseif (count($routesArray) === 0 && !file_exists($this->documentRoot . 'routes.json')) {
            $routesArray = [];
            $routesArray['web'] = [];
            $routesArray['web']['get'] = [];
            $routesArray['web']['post'] = [];
            $routesArray['web']['get']["^/$"] = "@/welcome/app/views/home.phtml";
        }

        foreach ($routesArray as $key => $value) {
            Registry::write('routes', $key, $value);
        }

        $this->routes = $routesArray;

        return $routesArray;
    }

    public function translate()
    {}

    public function dispatch()
    {}
}
