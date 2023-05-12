<?php

namespace App\Entity;

use App\Repository\CompetitionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CompetitionRepository::class)]
class Competition
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['competition'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['competition'])]
    private ?string $name = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['competition'])]
    private ?bool $is_active = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Wod $wod_in_progress = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function isIsActive(): ?bool
    {
        return $this->is_active;
    }

    public function setIsActive(?bool $is_active): self
    {
        $this->is_active = $is_active;

        return $this;
    }

    public function getWodInProgress(): ?Wod
    {
        return $this->wod_in_progress;
    }

    public function setWodInProgress(?Wod $wod_in_progress): self
    {
        $this->wod_in_progress = $wod_in_progress;

        return $this;
    }
}
