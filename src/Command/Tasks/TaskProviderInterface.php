<?php

namespace App\Command\Tasks;

use App\Entity\Tasks;
use Psr\Container\ContainerInterface;

interface TaskProviderInterface {

    public function __construct(ContainerInterface $container);
    public function init();
    public function requestTaskList();
    public function mappingData(Array $contents);
    public function createOrReplace(Tasks $task);


}