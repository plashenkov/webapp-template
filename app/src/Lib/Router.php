<?php

namespace App\Lib;

use Auryn\Injector;
use FastRoute\DataGenerator\GroupCountBased as DataGenerator;
use FastRoute\Dispatcher\GroupCountBased as Dispatcher;
use FastRoute\RouteCollector;
use FastRoute\RouteParser\Std as RouteParser;

class Router extends RouteCollector
{
    /** @var Injector */
    protected $injector;

    /** @var Request */
    protected $request;

    /** @var string */
    protected $controllersNamespace;

    /** @var ResultEmitter */
    protected $resultEmitter;

    /**
     * Router constructor.
     * @param Injector $injector
     * @param Request $request
     * @param string $controllersNamespace
     * @param ResultEmitter|null $resultEmitter
     */
    public function __construct(
        Injector $injector,
        Request $request,
        $controllersNamespace = 'App\Controllers',
        ResultEmitter $resultEmitter = null
    ) {
        parent::__construct(new RouteParser, new DataGenerator);

        $this->injector = $injector;
        $this->request = $request;
        $this->controllersNamespace = rtrim($controllersNamespace, '\\');
        $this->resultEmitter = $resultEmitter;
    }

    /**
     * Добавляем GET и POST роут в коллекцию
     * @param string $route
     * @param mixed $handler
     */
    public function any($route, $handler)
    {
        $this->addRoute(['GET', 'POST'], $route, $handler);
    }

    /**
     * Ищет подходящий роут, запускает его, возвращает результат пользователю.
     * @throws HttpException
     * @throws \Auryn\InjectionException
     */
    public function dispatch()
    {
        $dispatcher = new Dispatcher($this->getData());

        $routeInfo = $dispatcher->dispatch(
            $this->request->getRequestMethod(),
            $this->request->getRequestPath()
        );

        switch ($routeInfo[0]) {
            case Dispatcher::NOT_FOUND:
                throw HttpException::notFound();

            case Dispatcher::METHOD_NOT_ALLOWED:
                throw HttpException::methodNotAllowed();

            case Dispatcher::FOUND:
                $this->request->setRouteParams($routeInfo[2]);
                $this->echoResult(
                    $this->injector->execute($this->preprocessHandler($routeInfo[1]))
                );
        }
    }

    /**
     * Предварительно подготавливает обработчик (добавляет namespace и пр.).
     * @param $handler
     * @return string
     */
    protected function preprocessHandler($handler)
    {
        if ($this->controllersNamespace
            && is_string($handler)
            && strpos($handler, '\\') !== 0
            && !is_callable($handler)
        ) {
            $handler = $this->controllersNamespace . '\\' . $handler;
        }

        return $handler;
    }

    /**
     * Выводит результат в браузер.
     * @param mixed $result
     */
    protected function echoResult($result)
    {
        if ($this->resultEmitter) {
            $this->resultEmitter->emit($result);
        } else {
            var_dump($result);
        }
    }
}
