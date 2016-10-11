<?php

namespace Acme\TrainingBundle\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;

class ExceptionListener
{
    public function onKernelException($event)
    {
        $exception = $event->getException();

        $event->setResponse(new JsonResponse(
            ['error' => [
                'message' => $exception->getMessage(),
                'type' => get_class($exception)
            ]]
        ));
    }

    public function onKernelView($event)
    {
        $return = $event->getControllerResult();

        $event->setResponse(new JsonResponse(
            json_encode($return)
        ));
    }
}
