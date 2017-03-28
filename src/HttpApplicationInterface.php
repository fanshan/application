<?php

namespace ObjectivePHP\Application;

use ObjectivePHP\Message\Request\RequestInterface;
use ObjectivePHP\Message\Response\ResponseInterface;

/**
 * Interface HttpApplicationInterface
 *
 * @package ObjectivePHP\Application
 */
interface HttpApplicationInterface extends ApplicationInterface
{
    /**
     * Get a instance of RequestInterface
     *
     * @return RequestInterface
     */
    public function getRequest() : RequestInterface;

    /**
     * Set the RequestInterfaceInstance
     *
     * @param RequestInterface $request
     */
    public function setRequest(RequestInterface $request);

    /**
     * Get the ResponseInterface instance
     *
     * @return ResponseInterface
     */
    public function getResponse() : ResponseInterface;

    /**
     * Set the ResponseInterface instance
     *
     * @param ResponseInterface $response
     */
    public function setResponse(ResponseInterface $response);

    /**
     * Emit the response
     */
    public function emit();
}
