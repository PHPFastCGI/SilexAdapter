<?php

namespace PHPFastCGI\Adapter\Silex;

use PHPFastCGI\FastCGIDaemon\Http\RequestInterface;
use PHPFastCGI\FastCGIDaemon\KernelInterface;
use Silex\Application;

/**
 * Wraps a Silex application object as an implementation of the kernel interface
 */
class ApplicationWrapper implements KernelInterface
{
    /**
     * @var Application
     */
    private $application;

    /**
     * Constructor.
     * 
     * @param Application $application The Silex application object to wrap
     */
    public function __construct(Application $application)
    {
        $this->application = $application;
    }

    /**
     * {@inheritdoc}
     */
    public function handleRequest(RequestInterface $request)
    {
        $symfonyRequest  = $request->getHttpFoundationRequest();
        $symfonyResponse = $this->application->handle($symfonyRequest);

        $this->application->terminate($symfonyRequest, $symfonyResponse);

        return $symfonyResponse;
    }
}
