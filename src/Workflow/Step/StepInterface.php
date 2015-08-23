<?php

    namespace ObjectivePHP\Application\Workflow\Step;
    
    
    interface StepInterface
    {
        /**
         * @return string
         */
        public function getName();


        public function getDescription();

        public function getDocumentation();
    }