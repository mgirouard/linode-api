<?php

namespace mgirouard\Linode\Console;

use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Formatter\OutputFormatter;

class Application extends BaseApplication
{
    public function __construct($spec)
    {
        if (empty($spec->DATA->METHODS)) throw new \InvalidArgumentException('Bad spec supplied');

        foreach ($spec->DATA->METHODS as $name => $method) {
            $command = new Command(str_replace('.', ':', $name));
            $command->setDescription($method->DESCRIPTION);
            $this->add($command);
        }

        return parent::__construct('Linode');
    }

    public function getHelperSet()
    {
        return $this->getDefaultHelperSet();
    }
}
