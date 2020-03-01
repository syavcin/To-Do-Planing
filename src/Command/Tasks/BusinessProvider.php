<?php

namespace App\Command\Tasks;

use App\Entity\Tasks;

class BussinessProvider  extends TaskProvider {

    protected $url = "http://www.mocky.io/v2/5d47f235330000623fa3ebf7";
    protected $method = "GET";
    protected static $name = "Bussiness Task List";

    public function mappingData($content) : Tasks{

        $task = new Tasks();

        $id = key($content);
        $task->setName($id);
        $task->setTime($content[$id]['estimated_duration']);
        $task->setDifficulty($content[$id]['level']);

        return $task;
    }

}