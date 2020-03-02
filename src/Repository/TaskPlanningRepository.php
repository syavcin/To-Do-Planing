<?php

namespace App\Repository;

use App\Entity\TaskPlanning;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method TaskPlanning|null find($id, $lockMode = null, $lockVersion = null)
 * @method TaskPlanning|null findOneBy(array $criteria, array $orderBy = null)
 * @method TaskPlanning[]    findAll()
 * @method TaskPlanning[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TaskPlanningRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TaskPlanning::class);
    }


    public function getTaskGroups(){
        $data = $this->createQueryBuilder("tp")
        ->join("tp.task","t")
        ->groupBy("t.groupName")
        ->select("t.groupName as name ,MAX(tp.week) week")
        ->getQuery()->getResult();

        return $data;
    }

    public function groupByWeekData(){
        $result = [];

        $data = $this->createQueryBuilder('t')
            ->orderBy('t.week,t.day', 'ASC')
            ->getQuery()
            ->getResult();
        foreach($data as $d){
            $week = $d->getWeek();
            $day = $d->getDay();
            $dev_id = $d->getDeveloper()->getId();

            if(!isset($result[$week][$dev_id])){
                $result[$week][$dev_id] = [
                    'developer' => $d->getDeveloper()->getDeveloper(),
                    'days' => [
                        1 => [],
                        2 => [],
                        3 => [],
                        4 => [],
                        5 => [],
                    ]
                ];
            }

            $result[$week][$dev_id]['days'][$day][] = [
                'name' => $d->getTask()->getName()
            ];
        }

        return $result;
    }

    // /**
    //  * @return TaskPlanning[] Returns an array of TaskPlanning objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TaskPlanning
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
