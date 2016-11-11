<?php

namespace ObjectivePHP\Application\Workflow;

use ObjectivePHP\Application\Exception;
use ObjectivePHP\Primitives\Collection\Collection;

/**
 * Class WorkflowAwareTrait
 *
 * @package ObjectivePHP\Application\Workflow
 */
trait WorkflowAwareTrait
{
    /**
     * @var Collection
     */
    protected $steps;

    /**
     * Get a Step instance by its name
     *
     * @param string $step
     *
     * @return Step
     *
     * @throws Exception
     */
    public function getStep(string $step) : Step
    {
        $stepInstance = $this->steps->get($step);

        if (!$stepInstance) {
            throw new Exception(
                sprintf('Unknown step "%s". Please add this step before trying to plug middleware on it', $step)
            );
        }

        return $stepInstance;
    }

    /**
     * Returns the collection of registered Step instance
     *
     * @return Collection
     */
    public function getSteps() : Collection
    {
        return $this->steps;
    }

    /**
     * Add a step
     *
     * @param \string[] ...$steps
     *
     * @return $this
     */
    public function addSteps(string ...$steps)
    {
        foreach ($steps as $step) {
            $this->steps->set($step, new Step($step));
        }

        return $this;
    }

    /**
     * Tell if the return value of a registered middleware must break the workflow
     *
     * @param mixed $instance
     *
     * @return bool
     */
    public function isBreakWorkflowInstance($instance) : bool
    {
        if (is_object($instance)
            && in_array($this->getBreakWorkflowInterfaceName(), class_implements($instance))
        ) {
            return true;
        }

        return false;
    }

    /**
     * Get the class name which will break the workflow
     *
     * @return string
     */
    public abstract function getBreakWorkflowInterfaceName() : string;
}
