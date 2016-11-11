<?php

namespace ObjectivePHP\Application;

/**
 * Interface ApplicationInterface
 *
 * @package ObjectivePHP\Application
 */
interface ApplicationInterface
{
    /**
     * Run the application
     *
     * @return ApplicationInterface
     */
    public function run() : ApplicationInterface;
}
