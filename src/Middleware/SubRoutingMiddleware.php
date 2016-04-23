<?php
/**
 * Created by PhpStorm.
 * User: gauthier
 * Date: 23/04/2016
 * Time: 09:17
 */

namespace ObjectivePHP\Application\Middleware;


use ObjectivePHP\Application\ApplicationInterface;
use ObjectivePHP\Primitives\Collection\Collection;

abstract class SubRoutingMiddleware extends AbstractMiddleware
{

    protected $middlewareStack;

    public function __invoke(...$args)
    {
        // FIXME this should not be necessary!
        $this->setApplication($args[0][0]);
        return $this->run($args[0][0]);
    }

    public function run(ApplicationInterface $app)
    {
        $middlewareReference = $this->route();

        $middleware = $this->getMiddleware($middlewareReference);

        if(!is_callable($middleware))
        {
            throw new Exception(sprintf('No middleware matching routed reference "%s" has been registered', $middlewareReference));
        }

        return $middleware($app);

    }


    abstract public function route();

    public function registerMiddleware($reference, $middleware)
    {


        $middleware = ($middleware instanceof MiddlewareInterface) ? $middleware  : new EmbeddedMiddleware($middleware);

        $this->getMiddlewareStack()[$reference] = $middleware;

        return $this;

    }

    public function getMiddleware($reference)
    {
        return $this->getMiddlewareStack()->get($reference);
    }

    /**
     * @return mixed
     */
    public function getMiddlewareStack()
    {
        if(is_null($this->middlewareStack))
        {
            $this->middlewareStack = (new Collection())->restrictTo(MiddlewareInterface::class);
        }

        return $this->middlewareStack;
    }


}