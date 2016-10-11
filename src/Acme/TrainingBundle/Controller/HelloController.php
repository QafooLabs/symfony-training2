<?php

namespace Acme\TrainingBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class HelloController
{
    public function helloAction(Request $request)
    {
        return ['name' => $request->query->get('name')];
    }

    public function rpcAction($name)
    {
        return "Hello {$name}";
    }
}
