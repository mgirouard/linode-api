<?php

namespace mgirouard\Linode\Console;

use GuzzleHttp\Client;

use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Formatter\OutputFormatter;

class Application extends BaseApplication
{
    public function __construct(Client $client, $spec)
    {
        if (empty($spec->DATA->METHODS)) throw new \InvalidArgumentException('Bad spec supplied');

        foreach ($spec->DATA->METHODS as $name => $method) {
            $command = new Command($name);
            $command->setDescription($method->DESCRIPTION);

            foreach ($method->PARAMETERS as $param) {
                $command->addOption(
                    $param->NAME,
                    null,
                    InputOption::VALUE_REQUIRED,
                    $param->DESCRIPTION
                );
            }

            $command->setCode(function (InputInterface $input, OutputInterface $output) use ($name, $client) {
                $request = $client->createRequest('GET');
                $request->getQuery()->set('api_action', $name);
                $response = $client->send($request);

                $output->writeln((string) $response->getBody());
            });

            $this->add($command);
        }

        return parent::__construct('Linode');
    }

    public function getHelperSet()
    {
        return $this->getDefaultHelperSet();
    }
}
