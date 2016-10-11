<?php

namespace Acme\TrainingBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class HelloController
{
    public function helloAction(Request $request)
    {
        return new Response('Hello World!');
    }

    public function rpcAction($name)
    {
        return "Hello {$name}";
    }
}
