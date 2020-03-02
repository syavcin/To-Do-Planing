<?php

namespace App\Command\Tasks;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Psr\Container\ContainerInterface;
use App\Command\Tasks\Providers\ITProvider;
use App\Command\Tasks\Providers\BusinessProvider;
use App\Command\Tasks\Services\WorkPlanService;

class TaskCommand extends Command
{

    protected static $defaultName = 'Task:start';
    protected $container;
    protected $io;

    private $providerList = [
        ITProvider::class,
        BusinessProvider::class
    ];


    public function __construct(ContainerInterface $container)
    {
        parent::__construct();
        $this->container = $container;
    }


    protected function configure()
    {
        $this
            ->setDescription('Generate Work Plan');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->io = new SymfonyStyle($input, $output);
        $this->io->title("-- Generate Work Plan -- ");


        try {
            foreach ($this->providerList as $class) {
                $provider = new $class($this->container);
                $provider->init();

                $this->io->section("\r\nUpdating Task List : " . $provider->groupName);
                $this->io->table(["Total Data", "Insert", "Update"], [[$provider->result['total'], $provider->result['insert'], $provider->result['update']]]);
                $this->io->success("Complete " . $provider->groupName);
            }


            $this->io->section("Creating a Work Plan");
            $service = new WorkPlanService($this->container);
            $service->genereate();

            $this->io->success("Complete Work Plan " );

        } catch (\Exception $e) {
            $this->io->error($e->getMessage());
        }

        return 0;
    }
}
