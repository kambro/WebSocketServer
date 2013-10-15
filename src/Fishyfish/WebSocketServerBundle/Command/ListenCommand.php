<?php

namespace Fishyfish\WebSocketServerBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;

use Ratchet\App;

/**
 * Class ListenCommand
 * @package Fishyfish\WebSocketServerBundle\Command
 */
class ListenCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('websocket:listen')
            ->setDescription('Listen for websocket requests (blocks indefinitely)')
            ->addOption('port', 'p', InputOption::VALUE_REQUIRED, 'The port to listen on', 8080)
            ->addOption('httpHost', 'o', InputOption::VALUE_REQUIRED, 'The host name to listen on(must be matching with host name used by client), default: *', '*')
            ->addOption('address', 'a', InputOption::VALUE_REQUIRED, 'The interface to listen on, default: 0.0.0.0', '0.0.0.0');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $serviceContainer = $this->getContainer();

        $componentChain = $serviceContainer->get('fishyfish.web_socket_server.component_chain');
        $serverLoop = $serviceContainer->get('fishyfish.web_socket_server.server.loop');

        $httpHost = $input->getOption('httpHost');
        $address = $input->getOption('address');
        $port = (int) $input->getOption('port');

        $app = new App($httpHost, $port, $address, $serverLoop);

        $componentChain->rewind();
        while ($componentChain->valid()) {
            $data = $componentChain->getInfo();

            if (true == empty($data['route'])) {
                throw new LogicException('Route path cannot be empty!');
            }

            $allowedOrigins = array_key_exists('allowedOrigins', $data) ? explode(',', $data['allowedOrigins']) : [];
            $httpHost = array_key_exists('httpHost', $data) ? $data['httpHost'] : $httpHost.':'.$port;

            foreach ((array) $data['route'] as $route) {
                $currentComponenet = $componentChain->current();
                $output->writeln("<info>Route ws://$httpHost:$port$route [".implode(', ', $allowedOrigins)."] attached to " . get_class($currentComponenet).'<info>');

                $app->route($route, $currentComponenet, $allowedOrigins, $httpHost);
            }

            $componentChain->next();
        }

        $output->writeln("");
        $output->writeln("<info>Server is running on $httpHost:$port.<info>");
        $app->run();
    }
}