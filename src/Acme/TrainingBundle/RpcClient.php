<?php

namespace Acme\TrainingBundle;

abstract class RpcClient
{
    public function call($method, array $args)
    {
    }

    public function __call($method, $args)
    {
    }
}

class HelloService extends RpcClient
{
    public function rpc($name, $language = 'de')
    {
    }
}

$helloService = new RpcClient('http://localhost:8080/hello');
$result = $helloService->rpc(['name' => 'Benjamin', 'language' => 'de']);


