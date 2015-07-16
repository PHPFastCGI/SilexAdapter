<?php

namespace PHPFastCGI\Speedex\Tests;

use PHPFastCGI\Speedex\ApplicationWrapper;
use Silex\Application;
use Symfony\Bridge\PsrHttpMessage\Factory\DiactorosFactory;
use Symfony\Bridge\PsrHttpMessage\Factory\HttpFoundationFactory;
use Zend\Diactoros\ServerRequestFactory;

class ApplicationWrapperTest extends \PHPUnit_Framework_TestCase
{
    private function getPsrRequest()
    {
        return ServerRequestFactory::fromGlobals(['REQUEST_URI' => '/hello/World']);
    }

    private function getApplication()
    {
        $application = new Application;

        $application->get('/hello/{name}', function ($name) {
            return 'Hello ' . $name;
        });

        return $application;
    }

    public function testWrapperWithDefaultFactory()
    {
        $psrRequest  = $this->getPsrRequest();
        $application = $this->getApplication();

        $applicationWrapper = new ApplicationWrapper($application);

        $psrResponse = $applicationWrapper->handleRequest($psrRequest);

        $this->assertEquals('Hello World', (string) $psrResponse->getBody());
    }

    public function testWrapperWithCustomFactory()
    {
        $psrRequest  = $this->getPsrRequest();
        $application = $this->getApplication();

        $applicationWrapper = new ApplicationWrapper($application, new HttpFoundationFactory, new DiactorosFactory);

        $psrResponse = $applicationWrapper->handleRequest($psrRequest);

        $this->assertEquals('Hello World', (string) $psrResponse->getBody());
    }
}
