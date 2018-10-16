<?php

namespace Randomovies\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Elastica\Processor\Lowercase;

class ETLCommand extends ContainerAwareCommand
{
    protected $batchSize = 500;

    protected function configure()
    {
        $this
            ->setName('randomovies:elasticsearch:populate')
            ->setDescription('Update ElasticSearch index')
            ->addOption('index', null, InputOption::VALUE_OPTIONAL, 'Index to update')
            ->addOption('id', null, InputOption::VALUE_OPTIONAL, 'Id for one shot update')            
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {    	
        $container = $this->getContainer();        
        $container->get('app.model.etl')->launch(ucfirst(strtolower(trim($input->getOption('index')))), $input->getOption('id'));
    }

}
