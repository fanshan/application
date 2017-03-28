<?php

namespace ObjectivePHP\Application;

/**
 * Interface ApplicationAwareInterface
 *
 * @package ObjectivePHP\Application
 */
interface ApplicationAwareInterface
{
    /**
     * Set the ApplicationInterface instance
     *
     * @param ApplicationInterface $application
     *
     * @return ApplicationInterface
     */
    public function setApplication(ApplicationInterface $application) : ApplicationInterface;
}
