<?php

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Routing\RouteCollectionBuilder;
use Acme\TrainingBundle\Controller\HelloController;
use Acme\TrainingBundle\HttpKernel\Kernel;

class AppKernel extends MyKernel
{
    use MicroKernelTrait;

    public function registerBundles()
    {
        return array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Acme\TrainingBundle\AcmeTrainingBundle(),
            new Lexik\Bundle\JWTAuthenticationBundle\LexikJWTAuthenticationBundle(),

            new Symfony\Bundle\DebugBundle\DebugBundle(),
            new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle(),
        );
    }

    protected function configureContainer(ContainerBuilder $container, LoaderInterface $loader)
    {
        $loader->load(__DIR__ . '/config/services.yml');
        $loader->load(__DIR__ . '/config/services_' . $this->getEnvironment() . '.yml');
    }

    protected function configureRoutes(RouteCollectionBuilder $routes)
    {
        $routes->add('/', 'AcmeTrainingBundle:Hello:hello');
        $routes->add('/api/token', 'AcmeTrainingBundle:Hello:tokenAuthentication');
        $routes->add('/api/secure', 'AcmeTrainingBundle:Hello:secure');

        $routes->import('@WebProfilerBundle/Resources/config/routing/profiler.xml', '/_profiler');
    }
}
