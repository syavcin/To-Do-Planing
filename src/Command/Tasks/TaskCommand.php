<?php

namespace App\Command\Tasks;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Psr\Container\ContainerInterface;

class TaskCommand extends Command
{

    protected static $defaultName = 'Task:start';
    protected static $providerList = [
        ITProvider::class => "IT Task List",
        BussinessProvider::class => "Bussiness Task List",
    ];

    protected $io;


    public function __construct(ContainerInterface $container)
    {
        parent::__construct();
        $this->container = $container;
    }


    protected function configure()
    {
        $this
            ->setDescription('Get Task List');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->io = new SymfonyStyle($input, $output);
        $this->io->title("Generate Task List");
        $selectProvider = $this->io->choice("Please Select Task Provider", self::$providerList);

        try {
            $provider = new $selectProvider($this->container);
            $provider->init();
        } catch (\Throwable $th) {
            $this->io->error($th);
        }
        return 0;
    }
}
