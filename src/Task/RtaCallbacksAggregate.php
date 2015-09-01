<?php

    namespace ObjectivePHP\Application\Task;
    
    
    use ObjectivePHP\Application\Workflow\Event\WorkflowEvent;

    class RtaCallbacksAggregate
    {

        public function __invoke(WorkflowEvent $event)
        {
            $application = $event->getApplication();

            // Main workflow
            $workflow = $application->getWorkflow();

            $workflow->bind('bootstrap', Common\WrapRequest::class);

            // load initial services
            $workflow->bind('bootstrap', ['services-loader' => Common\LoadServices::class]);

            // bind packages to packages.load
            $workflow->bind('packages.pre', Common\BootstrapPackages::class);

            // load packages services
            $workflow->bind('packages.post', Common\LoadServices::class);

            // define what action to execute
            $workflow->bind('route.resolve', ['action-resolver' => Rta\RouteRequestToAction::class]);

            // handle rendering
            $workflow->bind('response.generate', ['view-resolver' => Rta\ResolveView::class]);
            $workflow->bind('response.generate', ['view-renderer' => Common\RenderView::class]);
            $workflow->bind('response.generate', ['layout-renderer' => Common\RenderLayout::class]);

        }

    }