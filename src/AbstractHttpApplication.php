<?php

namespace ObjectivePHP\Application;

use ObjectivePHP\Message\Response\ResponseInterface;

/**
 * Class AbstractHttpApplication
 *
 * @package ObjectivePHP\Application
 */
abstract class AbstractHttpApplication extends AbstractApplication implements HttpApplicationInterface
{
    use HttpAwareTrait;

    /**
     * {@inheritdoc}
     */
    public function emit()
    {
        echo $this->getResponse()->getBody();
    }

    /**
     * {@inheritdoc}
     */
    public function setBreakWorkflowInstance($instance) : ApplicationInterface
    {
        $this->setResponse($instance);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getBreakWorkflowInterfaceName() : string
    {
        return ResponseInterface::class;
    }
}
