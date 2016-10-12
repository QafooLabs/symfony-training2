<?php

namespace Acme\TrainingBundle\HttpKernel;

abstract class MyKernel
{
    static function createFromEnvironment()
    {
        return new static(
            $_SERVER['SYMFONY_ENV'],
            in_array($_SERVER['SYMFONY_ENV'], ['dev', 'test'])
        );
    }
}
