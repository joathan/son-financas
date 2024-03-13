<?php

namespace SONFin\Plugins;

use Aura\Router\RouterContainer;
use SONFin\ServiceContainerInterface;

class RoutePlugin implements PluginInterface
{
    public function register(ServiceContainerInterface $container)
    {
        $routerContainer = new RouterContainer();
        $map = $routerContainer->getMap();
        $matcher = $routerContainer->getMatcher();
        $generator = $routerContainer->getGenerator();

        $container->add('routing', $map);
        $container->add('routing.matcher', $matcher);
        $container->add('routing.generator', $generator);
    } 
}
