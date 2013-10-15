<?php

namespace Fishyfish\WebSocketServerBundle\Tests\Command;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

use Fishyfish\WebSocketServerBundle\Command\ListenCommand;
use Fishyfish\WebSocketServerBundle\Tests\Command\AbstractCommandTestCase;
use Sensio\Bundle\GeneratorBundle\Tests\Command;

/**
 * Class ListenCommandTest
 * @package Fishyfish\WebSocketServerBundle\Tests\Command
 */
class ListenCommandTest extends AbstractCommandTestCase
{
    public function testWebSocketListenCommand()
    {
        $client = self::createClient();
        $output = $this->runCommand($client, "help websocket:listen");
        $this->assertRegExp('/Usage/', $output);
    }
}