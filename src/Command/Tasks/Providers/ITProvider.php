<?php

namespace App\Command\Tasks\Providers;

use App\Entity\Tasks;

class ITProvider extends TaskProvider
{

    protected $url = "http://www.mocky.io/v2/5d47f24c330000623fa3ebfa";
    protected $method = "GET";
    public $groupName = "IT Tasks";


    public function mappingData($content) : Tasks
    {
        $task = new Tasks();

        $task->setName($content['id']);
        $task->setTime($content['sure']);
        $task->setGroupName($this->groupName);
        $task->setDifficulty($content['zorluk']);

        return $task;
    }
}
