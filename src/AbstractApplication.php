<?php

namespace ObjectivePHP\Application;

use Composer\Autoload\ClassLoader;
use ObjectivePHP\Application\Workflow\Step;
use ObjectivePHP\Application\Workflow\WorkflowAwareInterface;
use ObjectivePHP\Application\Workflow\WorkflowAwareTrait;
use ObjectivePHP\Primitives\Collection\Collection;

/**
 * Class AbstractApplication
 *
 * @package ObjectivePHP\Application
 */
abstract class AbstractApplication implements ApplicationInterface, WorkflowAwareInterface
{
    use WorkflowAwareTrait;

    /**
     * @var ClassLoader
     */
    protected $autoloader;

    /**
     * AbstractApplication constructor.
     *
     * @param ClassLoader|null $autoloader
     */
    public function __construct(ClassLoader $autoloader = null)
    {
        if (!is_null($autoloader)) {
            $this->setAutoloader($autoloader);
        }

        $this->steps = (new Collection())->restrictTo(Step::class);

        $this->init();
    }

    /**
     * Get Autoloader
     *
     * @return ClassLoader
     */
    public function getAutoloader() : ClassLoader
    {
        return $this->autoloader;
    }

    /**
     * Set Autoloader
     *
     * @param ClassLoader $autoloader
     *
     * @return $this
     */
    public function setAutoloader(ClassLoader $autoloader)
    {
        $this->autoloader = $autoloader;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function run() : ApplicationInterface
    {
        try {
            $this->startWorkflow();
        } catch (\Throwable $e) {
            throw $e;
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function startWorkflow()
    {
        /** @var \ObjectivePHP\Application\Workflow\Step $step */
        foreach ($this->getSteps()->getInternalValue() as $step) {
            if (!$step->runFilters($this)) {
                continue;
            }

            /** @var \ObjectivePHP\Application\Workflow\Hook $hook */
            foreach ($step->getInternalValue() as $hook) {
                $result = $hook->run($this);

                if ($this->isBreakWorkflowInstance($result)) {
                    $this->setBreakWorkflowInstance($result);
                    break 2;
                }
            }
        }
    }

    /**
     * Initialize the application
     */
    abstract public function init();
}
