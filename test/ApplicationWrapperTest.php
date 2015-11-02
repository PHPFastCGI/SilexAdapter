<?php

namespace PHPFastCGI\Adapter\Silex\Tests;

use PHPFastCGI\Adapter\Silex\ApplicationWrapper;
use PHPFastCGI\FastCGIDaemon\Http\Request;
use Silex\Application;

class ApplicationWrapperTest extends \PHPUnit_Framework_TestCase
{
    private function getApplication()
    {
        $application = new Application;

        $application->get('/hello/{name}', function ($name) {
            return 'Hello ' . $name;
        });

        return $application;
    }

    public function testWrapper()
    {
        $stream = fopen('php://temp', 'r');

        $request = new Request(['REQUEST_URI' => '/hello/World'], $stream);

        $application        = $this->getApplication();
        $applicationWrapper = new ApplicationWrapper($application);

        $response = $applicationWrapper->handleRequest($request);

        $this->assertEquals('Hello World', $response->getContent());

        fclose($stream);
    }
}
