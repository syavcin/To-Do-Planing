<?php

namespace App\Command\Tasks\Services;

use App\Entity\Developers;
use App\Entity\TaskPlanning;
use App\Entity\Tasks;
use Psr\Container\ContainerInterface;

class WorkPlanService
{

    private $entityManager;

    private $developerRepository;
    private $taskRepository;
    private $avarageTime;


    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->entityManager = $this->container->get('doctrine')->getManager();
        $this->developerRepository = $this->entityManager->getRepository(Developers::class);
        $this->taskRepository = $this->entityManager->getRepository(Tasks::class);

        $connection = $this->entityManager->getConnection();
        $platform   = $connection->getDatabasePlatform();

        $connection->executeUpdate($platform->getTruncateTableSQL('task_planning', true));
    }

    public function genereate()
    {
        $developers = $this->developerRepository->findBy([], ['difficulty' => 'DESC']);
        $tasks = $this->taskRepository->findBy([] , ['difficulty' => 'DESC']);


        $this->avarageTime = round($this->taskRepository->getTotalTime() / count($developers));

        try {
            $this->createTaskPlan($developers , $tasks);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }


        $this->entityManager->flush();
    }

    private function createTaskPlan(array $developers, array $tasks, int $time = 9, int $day = 1, int $week = 1,int $remainingTime = 0 , int $totalTime = 0, int $tidx = 0 , int $didx = 0)
    {

        $plan = new TaskPlanning();
        $task = $tasks[$tidx];
        $developer = $developers[$didx];

        if($totalTime == $this->avarageTime && $task->getDifficulty() <= $developer->getDifficulty()){
            $didx++;
            $totalTime = 0;
            $time = 9;
            $day = 1;
            $week = 1;

            $developer = $developers[$didx];
        } else if($totalTime > $this->avarageTime){

            $diffTime = $totalTime - $this->avarageTime;
            $remainingDevs = ($didx + 1) - count($developers);
            $this->avarageTime = $this->avarageTime - round($diffTime / $remainingDevs);
            if($task->getDifficulty() <= $developer->getDifficulty()){
                $didx++;
                $totalTime = 0;
                $time = 9;
                $day = 1;
                $week = 1;
                $developer = $developers[$didx];
            }
        }

        $plan->setDeveloper($developer);
        $plan->setTask($task);
        $plan->setDay($day);
        $plan->setWeek($week);


        if ($remainingTime == 0) {
            $taskTime = $task->getTime();
        } else {
            $taskTime = $remainingTime;
        }

        if ($time < $taskTime) {
            $remainingTime = $taskTime - $time;
            $plan->setTime($time);
            $totalTime += $time;
            $time = 0;
        } else {
            $remainingTime = 0;
            $plan->setTime($taskTime);
            $tidx++;
            $totalTime += $taskTime;
            $time = $time - $taskTime;
        }

        if ($time == 0) {
            $time = 9;
            $day++;

            if ($day > 5) {
                $day = 1;
                $week++;
            }
        }

        $this->entityManager->persist($plan);
        if (isset($tasks[$tidx])) {
            $this->createTaskPlan($developers, $tasks, $time, $day, $week,$remainingTime , $totalTime ,$tidx,$didx);
        } else {
            return;
        }
    }
}
