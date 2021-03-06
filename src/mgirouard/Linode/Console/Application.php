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
            $requiredParams = [];

            $command->addOption('DATA', null, InputOption::VALUE_REQUIRED, 
                'Arbitrary query data. Must be in the form of valid query string key=value pairs.');

            foreach ($method->PARAMETERS as $param) {
                $command->addOption(
                    $param->NAME,
                    null,
                    InputOption::VALUE_REQUIRED,
                    $param->DESCRIPTION
                );

                if ($param->REQUIRED) $requiredParams[] = $param->NAME;
            }

            $command->setCode(
                function (InputInterface $input, OutputInterface $output) 
                use ($name, $client, $requiredParams) 
                {
                    $request = $client->createRequest('GET');
                    $query = $request->getQuery();
                    $query->set('api_action', $name);
                    $missing = [];

                    foreach ($requiredParams as $required) {
                        if (!$input->hasOption($required) || !$input->getOption($required)) {
                            $missing[] = $required;
                            continue;
                        }

                        $query->set($required, $input->getOption($required));
                    }

                    if (!empty($missing)) throw new \RuntimeException('Missing Parameters: ' . implode(', ', $missing));

                    if ($data = $input->getOption('DATA')) {
                        $request->setUrl($request->getUrl() . '&' . $data);
                    }

                    $response = $client->send($request);
                    $output->writeln((string) $response->getBody());
                }
            );

            $this->add($command);
        }

        return parent::__construct('Linode');
    }

    public function getHelperSet()
    {
        return $this->getDefaultHelperSet();
    }
}
