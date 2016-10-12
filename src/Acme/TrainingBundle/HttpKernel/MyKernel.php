<?php

namespace Acme\TrainingBundle\HttpKernel;

use Symfony\Component\HttpKernel\Kernel;

abstract class MyKernel extends Kernel
{
    static function createFromEnvironment()
    {
        $_SERVER['SYMFONY_ENV'] = 'dev';
        return new static(
            $_SERVER['SYMFONY_ENV'],
            in_array($_SERVER['SYMFONY_ENV'], ['dev', 'test'])
        );
    }
}
