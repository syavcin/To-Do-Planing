<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TaskPlanningRepository")
 */
class TaskPlanning
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\developers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $developer;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\tasks")
     * @ORM\JoinColumn(nullable=false)
     */
    private $task;

    /**
     * @ORM\Column(type="integer")
     */
    private $time;

    /**
     * @ORM\Column(type="integer")
     */
    private $day;

    /**
     * @ORM\Column(type="integer")
     */
    private $week;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDeveloper(): ?developers
    {
        return $this->developer;
    }

    public function setDeveloper(?developers $developer): self
    {
        $this->developer = $developer;

        return $this;
    }

    public function getTask(): ?tasks
    {
        return $this->task;
    }

    public function setTask(?tasks $task): self
    {
        $this->task = $task;

        return $this;
    }

    public function getTime(): ?int
    {
        return $this->time;
    }

    public function setTime(int $time): self
    {
        $this->time = $time;

        return $this;
    }

    public function getDay(): ?int
    {
        return $this->day;
    }

    public function setDay(int $day): self
    {
        $this->day = $day;

        return $this;
    }

    public function getWeek(): ?int
    {
        return $this->week;
    }

    public function setWeek(int $week): self
    {
        $this->week = $week;

        return $this;
    }
}
