<?php

namespace Acme\TrainingBundle\Controller;

use Symfony\Component\HttpFoundation\Response;

class HelloController
{
    public function helloAction()
    {
        return new Response('Hello World!');
    }

    public function rpcAction($name)
    {
        return "Hello {$name}";
    }
}
