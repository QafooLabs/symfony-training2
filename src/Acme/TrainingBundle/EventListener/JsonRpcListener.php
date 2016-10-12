<?php

namespace Acme\TrainingBundle\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Acme\TrainingBundle\Controller\HelloController;

/*
class Context
{
    public $jwtToken;
    public $traceId;
}

$insect->callAll([
    $insect->createcall('service://product/get', ['id' = 1234]),
    $insect->createcall('GET', 'http://....', [], [], []),
]);
*/

class JsonRpcListener
{
    // This would better be configured with DIC tag
    // <tag name="rpc.service" path="/foo" />
    private $services = [
        "/hello" => HelloController::class,
        "/vehicle" => 'app.vehicle_controller',
    ];

    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function onKernelRequest($event)
    {
        $request = $event->getRequest();

        if ($request->headers->get('Content-Type') !== "application/json") {
            return;
        }

        $content = json_decode($request->getContent(), true);

        if (!isset($content["method"])) {
            return;
        }

        if (!isset($this->services[$request->getPathInfo()])) {
            throw new NotFoundHttpException();
        }

        $service = $this->container->get($this->services[$request->getPathInfo()]);

        $request->attributes->set('_controller', [$service, $content["method"] . "Action"]);

        if (isset($content["params"])) {
            // Only supports JsonRpc v2.0 with named arguments for simplicity
            $request->attributes->add($content["params"]);
            $request->request->add($content["params"]);
        }

        if (isset($content["id"])) {
            $request->attributes->set('_json_rpc_id', $content["id"]);
        }
    }

    public function onKernelView($event)
    {
        $request = $event->getRequest();

        if (!$request->attributes->has('_json_rpc_id')) {
            return;
        }

        $result = $event->getControllerResult();

        // assume $result is just array, scalar or
        // object with public properties/JsonSerializable
        // optional: add Serializer component or JMS Serializer here
        $event->setResponse(new JsonResponse([
            'result' => $result,
            'id' => $request->attributes->get('_json_rpc_id')
        ]));
    }

    public function onKernelException($event)
    {
        $request = $event->getRequest();

        if (!$request->attributes->has('_json_rpc_id')) {
            return;
        }

        $exception = $event->getException();

        $event->setResponse(new JsonResponse([
            'error' => [
                'code' => $exception->getCode(),
                'message' => $exception->getMessage(),
            ],
            'id' => $request->attributes->get('_json_rpc_id')
        ]));
    }
}
