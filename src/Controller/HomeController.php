<?php

namespace App\Controller;

use App\Entity\TaskPlanning;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="home")
     */
    public function index()
    {
        $data['plan'] = $this->getDoctrine()->getRepository(TaskPlanning::class)->groupByWeekData();
        $data['works'] = $this->getDoctrine()->getRepository(TaskPlanning::class)->getTaskGroups();

        return $this->render('home/index.html.twig', $data);
    }
}
