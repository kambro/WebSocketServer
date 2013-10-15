<?php

namespace Fishyfish\WebSocketServerBundle\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class ComponentCompilerPass
 * @package Fishyfish\WebSocketServerBundle\DependencyInjection\CompilerPass
 */
class ComponentCompilerPass implements CompilerPassInterface
{
    /**
     * @inheritdoc
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('fishyfish.web_socket_server.component_chain')) {
            return;
        }

        $definition = $container->getDefinition(
            'fishyfish.web_socket_server.component_chain'
        );

        $taggedServices = $container->findTaggedServiceIds(
            'fishyfish.web_socket_server.component'
        );

        foreach ($taggedServices as $id => $attributes) {
            $definition->addMethodCall(
                'attach',
                array(new Reference($id), @$attributes[0])
            );
        }
    }
}