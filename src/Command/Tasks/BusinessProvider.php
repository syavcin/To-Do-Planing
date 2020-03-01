<?php

namespace App\Command\Tasks;

use Symfony\Component\HttpClient\HttpClient;
use Psr\Container\ContainerInterface;
use App\Entity\Tasks;

class BussinessProvider implements TaskProviderInterface {

    protected static $name = "IT Task List";
    protected $url = "http://www.mocky.io/v2/5d47f235330000623fa3ebf7";
    protected $method = "GET";

    private $client;
    private $entityManager;
    private $repository;
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function init(){

        $this->entityManager = $this->container->get('doctrine')->getManager();
        $this->repository = $this->entityManager->getRepository(Tasks::class);
        $this->client = HttpClient::create();
        $this->requestTaskList();
        $this->entityManager->flush();
    }


    public function requestTaskList(){
        $response = $this->client->request($this->method,$this->url);

        $statusCode = $response->getStatusCode();

        if($statusCode != 200){
            throw new \Exception("HttpClient Error : Status Code => " . $statusCode);
        } else {

            $contents = $response->toArray();

            if(!is_array($contents)){
                throw new \Exception("Undefined Response Type");
            } else {
                $this->mappingData($contents);
            }
        }
    }


    public function mappingData(Array $contents){

        foreach($contents  as $content):

            $id = key($content);

            $task = new Tasks();

            $task->setName($id);
            $task->setTime($content[$id]['estimated_duration']);
            $task->setDifficulty($content[$id]['level']);

            $this->createOrReplace($task);
        endforeach;

    }

    public function createOrReplace(Tasks $data)
    {
        try {

            $task = $this->repository->findOneBy(['name' => $data->getName()]);

            if(!$task){
                $this->entityManager->persist($data);
            } else {
                $task->setTime($data->getTime());
                $task->setDifficulty($data->getDifficulty());
            }


        } catch (\Throwable $th) {
            throw new \Exception("Task could not be created : " . $th);
        }
    }
}