<?php

namespace ObjectivePHP\Application\Tests;

use ObjectivePHP\Application\AbstractHttpApplication;
use ObjectivePHP\Application\HttpApplicationInterface;
use ObjectivePHP\Message\Response\HttpResponse;
use PHPUnit\Framework\TestCase;

/**
 * Class AbstractHttpApplicationTest
 */
class AbstractHttpApplicationTest extends TestCase
{
    public function testEmit()
    {
        $application = new class extends AbstractHttpApplication {
            public function init()
            {
                $this->addSteps('run');
                $this->getStep('run')
                    ->plug(function (HttpApplicationInterface $app) {
                        $response = new HttpResponse();
                        $response->getBody()->write('test');
                        $app->setResponse($response);
                    })
                    ->plug(function (HttpApplicationInterface $app) {
                        $response = $app->getResponse();
                        $response->getBody()->write('test');
                        return  $response;
                    })
                    ->plug(function (HttpApplicationInterface $app) {
                        $response = $app->getResponse();
                        $response->getBody()->write('test');
                    });
            }
        };

        $application->run();
        ob_start();
        $application->emit();
        $content = ob_get_contents();
        ob_end_clean();

        $this->assertEquals('testtest', $content);
    }
}
