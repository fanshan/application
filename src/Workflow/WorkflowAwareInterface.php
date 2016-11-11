<?php

namespace ObjectivePHP\Application\Workflow;

use ObjectivePHP\Application\ApplicationInterface;
use ObjectivePHP\Primitives\Collection\Collection;

/**
 * Interface WorkflowAwareInterface
 *
 * @package ObjectivePHP\Application\Workflow
 */
interface WorkflowAwareInterface
{
    /**
     * Get a registered Step instance
     *
     * @param string $step
     *
     * @return Step
     */
    public function getStep(string $step) : Step;

    /**
     * Returns the collection of registered Step instance
     *
     * @return Collection
     */
    public function getSteps() : Collection;

    /**
     * Set in motion the workflow
     */
    public function startWorkflow();

    /**
     * Tell if the return value of a registered middleware will break the workflow
     *
     * @param mixed $instance
     *
     * @return bool
     */
    public function isBreakWorkflowInstance($instance) : bool;

    /**
     * @param mixed $instance
     *
     * @return ApplicationInterface
     */
    public function setBreakWorkflowInstance($instance) : ApplicationInterface;
}
