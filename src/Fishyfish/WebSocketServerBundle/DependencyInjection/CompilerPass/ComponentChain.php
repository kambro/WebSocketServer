<?php

namespace Fishyfish\WebSocketServerBundle\DependencyInjection\CompilerPass;

use Ratchet\MessageComponentInterface;

/**
 * Class ComponentChain
 * @package Fishyfish\WebSocketServerBundle\DependencyInjection\CompilerPass
 */
class ComponentChain extends \SplObjectStorage
{
    /**
     * @inheritdoc
     */
    public function attach($component, $data = null)
    {
        if (false == $component instanceof MessageComponentInterface) {
            throw new \DomainException('Object '.get_class($component).' should implements Ratchet\MessageComponentInterface.');
        }

        return parent::attach($component, $data);
    }
}