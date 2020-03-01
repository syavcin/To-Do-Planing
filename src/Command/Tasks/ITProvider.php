<?php

namespace App\Command\Tasks;

use App\Entity\Tasks;

class ITProvider extends TaskProvider
{

    protected $url = "http://www.mocky.io/v2/5d47f24c330000623fa3ebfa";
    protected $method = "GET";
    protected static $name = "IT Task List";


    public function mappingData($content) : Tasks
    {
        $task = new Tasks();

        $task->setName($content['id']);
        $task->setTime($content['sure']);
        $task->setDifficulty($content['zorluk']);

        return $task;
    }
}
