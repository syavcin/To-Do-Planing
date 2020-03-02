<?php

namespace App\Command\Tasks\Providers;


use Symfony\Component\HttpClient\HttpClient;
use Psr\Container\ContainerInterface;
use App\Entity\Tasks;


abstract class TaskProvider implements TaskProviderInterface
{

    protected $client;
    protected $entityManager;
    protected $repository;

    protected $groupName;
    protected $url;
    protected $method;

    public $result;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->entityManager = $this->container->get('doctrine')->getManager();
        $this->repository = $this->entityManager->getRepository(Tasks::class);
        $this->result = [
            "total" => 0,
            "insert" => 0,
            "update" => 0
        ];

    }

    public function init()
    {
        $this->client = HttpClient::create();
        $this->requestTaskList();
        $this->entityManager->flush();
    }


    public function requestTaskList()
    {
        $response = $this->client->request($this->method, $this->url);

        $statusCode = $response->getStatusCode();

        if ($statusCode != 200) {
            throw new \Exception("HttpClient Error : Status Code => " . $statusCode);
        } else {

            $contents = $response->toArray();


            if (!is_array($contents)) {
                throw new \Exception("Undefined Response Type : ". $this->groupName);
            } else {

                $this->result['total'] = count($contents);

                foreach ($contents as $content) :
                   $task = $this->mappingData($content);
                   $this->createOrReplace($task);
                endforeach;
            }
        }
    }

    public function mappingData($content) : Tasks
    {

        throw new \Exception("MappingData method is not created");
    }

    public function createOrReplace(Tasks $data)
    {
        try {
            $task = $this->repository->findOneBy(['name' => $data->getName()]);
            if (!$task) {
                $this->result['insert']++;
                $this->entityManager->persist($data);
            } else {
                $task->setTime($data->getTime());
                $task->setDifficulty($data->getDifficulty());
                $this->result['update']++;
            }
        } catch (\Throwable $th) {
            throw new \Exception("Task could not be created : " . $th);
        }
    }
}
