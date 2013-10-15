<?php

namespace Fishyfish\WebSocketServerBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

use Fishyfish\WebSocketServerBundle\DependencyInjection\CompilerPass\ComponentCompilerPass;

/**
 * Class FishyfishWebSocketServerBundle
 * @package Fishyfish\WebSocketServerBundle
 */
class FishyfishWebSocketServerBundle extends Bundle
{
    /**
     * @inheritdoc
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new ComponentCompilerPass());
    }
}
