<?php

namespace PHPFastCGI\Speedex;

use PHPFastCGI\FastCGIDaemon\KernelInterface;
use Psr\Http\Message\ServerRequestInterface;
use Silex\Application;
use Symfony\Bridge\PsrHttpMessage\Factory\DiactorosFactory;
use Symfony\Bridge\PsrHttpMessage\Factory\HttpFoundationFactory;
use Symfony\Bridge\PsrHttpMessage\HttpFoundationFactoryInterface;
use Symfony\Bridge\PsrHttpMessage\HttpMessageFactoryInterface;

/**
 * Wraps a Silex application object as an implementation of the kernel interface
 */
class ApplicationWrapper implements KernelInterface
{
    /**
     * @var Application
     */
    protected $application;

    /**
     * @var HttpFoundationFactoryInterface
     */
    protected $symfonyMessageFactory;

    /**
     * @var HttpMessageFactoryInterface
     */
    protected $psrMessageFactory;

    /**
     * Constructor.
     * 
     * @param Application $application The Silex application object to wrap
     */
    public function __construct(Application $application, HttpFoundationFactoryInterface $symfonyMessageFactory = null, HttpMessageFactoryInterface $psrMessageFactory = null)
    {
        $this->application = $application;

        if (null === $symfonyMessageFactory) {
            $this->symfonyMessageFactory = new HttpFoundationFactory();
        } else {
            $this->symfonyMessageFactory = $symfonyMessageFactory;
        }

        if (null === $psrMessageFactory) {
            $this->psrMessageFactory = new DiactorosFactory();
        } else {
            $this->psrMessageFactory = $psrMessageFactory;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function handleRequest(ServerRequestInterface $request)
    {
        $symfonyRequest = $this->symfonyMessageFactory->createRequest($request);

        $symfonyResponse = $this->application->handle($symfonyRequest);
        $this->application->terminate($symfonyRequest, $symfonyResponse);

        return $this->psrMessageFactory->createResponse($symfonyResponse);
    }
}
