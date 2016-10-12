<?php

namespace Acme\TrainingBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\Config\FileLocator;

class AcmeTrainingExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = $this->getConfiguration($configs, $container);
        $config = $this->processConfiguration($configuration, $configs);

        if ($config['use_eu_soap']) {
            $definition = new Definition('Acme\TrainingBundle\Services\EuSoapValidator');
        } else {
            $definition = new Definition('Acme\TrainingBundle\Services\AlwaysValidValidator');
        }
        $container->setDefinition('acme.soap_validator', $definition);
    }
}
