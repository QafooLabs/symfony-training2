<?php

namespace Acme\TrainingBundle\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Psr\Log\LoggerInterface;

interface PublicExceptionInterface
{
}

class ExceptionListener
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function onKernelException($event)
    {
        $exception = $event->getException();

        $statusCode = 500;

        if ($exception instanceof \Symfony\Component\HttpKernel\Exception\HttpException) {
            $statusCode = $exception->getStatusCode();
        }

        $this->logger->err((string)$exception);

        $whitelistException = ['DomainException'];

        $message = $exception->getMessage();

        $event->setResponse(new JsonResponse(
            ['error' => [
                'message' => $message,
                'type' => get_class($exception)
            ]],
            $statusCode
        ));

        $event->stopPropagation();
    }

    public function onKernelView($event)
    {
        $return = $event->getControllerResult();

        $event->setResponse(new JsonResponse($return));
    }
}
