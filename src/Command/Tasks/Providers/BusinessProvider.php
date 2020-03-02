<?php

namespace App\Command\Tasks\Providers;

use App\Entity\Tasks;

class BusinessProvider  extends TaskProvider {

    protected $url = "http://www.mocky.io/v2/5d47f235330000623fa3ebf7";
    protected $method = "GET";
    public $groupName = "Business Tasks";

    public function mappingData($content) : Tasks{

        $task = new Tasks();

        $id = key($content);
        $task->setName($id);
        $task->setGroupName($this->groupName);
        $task->setTime($content[$id]['estimated_duration']);
        $task->setDifficulty($content[$id]['level']);

        return $task;
    }

}