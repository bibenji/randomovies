<?php

namespace Randomovies\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ETLCommand extends ContainerAwareCommand
{
    protected $batchSize = 500;

    protected function configure()
    {
        $this
            ->setName('app:etl')
            ->setDescription('Update ElasticSearch index')
            ->addOption('id', null, InputOption::VALUE_OPTIONAL, 'Id for one shot update')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();
        $container->get('app.model.etl')->launch($input->getOption('id'));
    }

}
