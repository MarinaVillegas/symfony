<?php

namespace App\Entity;

use App\Repository\PuntuacionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PuntuacionRepository::class)]
class Puntuacion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $score = null;

    #[ORM\Column]
    private ?int $time = null;

    #[ORM\Column]
    private ?int $tries = null;

    #[ORM\Column]
    private ?int $levelReached = null;

    #[ORM\Column]
    private ?int $lives = null;

    #[ORM\Column]
    private ?bool $sucess = null;

    #[ORM\Column]
    private ?\DateTime $endAt = null;

    #[ORM\ManyToOne(inversedBy: 'puntuacions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function setScore(int $score): static
    {
        $this->score = $score;

        return $this;
    }

    public function getTime(): ?int
    {
        return $this->time;
    }

    public function setTime(int $time): static
    {
        $this->time = $time;

        return $this;
    }

    public function getTries(): ?int
    {
        return $this->tries;
    }

    public function setTries(int $tries): static
    {
        $this->tries = $tries;

        return $this;
    }

    public function getLevelReached(): ?int
    {
        return $this->levelReached;
    }

    public function setLevelReached(int $levelReached): static
    {
        $this->levelReached = $levelReached;

        return $this;
    }

    public function getLives(): ?int
    {
        return $this->lives;
    }

    public function setLives(int $lives): static
    {
        $this->lives = $lives;

        return $this;
    }

    public function isSucess(): ?bool
    {
        return $this->sucess;
    }

    public function setSucess(bool $sucess): static
    {
        $this->sucess = $sucess;

        return $this;
    }

    public function getEndAt(): ?\DateTime
    {
        return $this->endAt;
    }

    public function setEndAt(\DateTime $endAt): static
    {
        $this->endAt = $endAt;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
}
