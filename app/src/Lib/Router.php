<?php

namespace App\Lib;

use App\Lib\ResultEmitter\ResultEmitter;
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

    /** @var ResultEmitter */
    protected $resultEmitter;

    /**
     * Router constructor.
     * @param Injector $injector
     * @param Request $request
     * @param ResultEmitter|null $resultEmitter
     */
    public function __construct(
        Injector $injector,
        Request $request,
        ResultEmitter $resultEmitter = null
    ) {
        parent::__construct(new RouteParser, new DataGenerator);

        $this->injector = $injector;
        $this->request = $request;
        $this->resultEmitter = $resultEmitter;
    }

    /**
     * Adds GET and POST routes to the collection.
     * @param string $route
     * @param mixed $handler
     */
    public function any($route, $handler)
    {
        $this->addRoute(['GET', 'POST'], $route, $handler);
    }

    /**
     * Performs a dispatch.
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
                $this->echoResult($this->injector->execute($routeInfo[1]));
        }
    }

    /**
     * Echoes result to a user.
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
