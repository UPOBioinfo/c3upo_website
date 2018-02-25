<?php

namespace AppBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class Redirect404ToHomepageListener
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var RouterInterface $router
     */
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * @var GetResponseForExceptionEvent $event
     * @return null
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        // If not a HttpNotFoundException ignore
        if (!$event->getException() instanceof NotFoundHttpException && !$event->getException() instanceof MethodNotAllowedHttpException) {
            return;
        }

        // Create redirect response with url for the home page
        $response = new RedirectResponse($this->router->generate('index'));

        // Set the response to be processed
        $event->setResponse($response);
    }
}