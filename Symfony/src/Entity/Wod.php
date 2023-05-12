<?php

namespace App\Entity;

use App\Repository\WodRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: WodRepository::class)]
class Wod
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['competition'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['competition'])]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['competition'])]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $type = null;

    #[ORM\Column]
    #[Groups(['competition'])]
    private ?int $repetions_max = null;

    #[ORM\Column]
    #[Groups(['competition'])]
    private ?int $nb_of_lines = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Competition $Competition = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['competition'])]
    private ?int $nb_of_participants = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['competition'])]
    private ?int $duration = null;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getRepetionsMax(): ?int
    {
        return $this->repetions_max;
    }

    public function setRepetionsMax(int $repetions_max): self
    {
        $this->repetions_max = $repetions_max;

        return $this;
    }

    public function getNbOfLines(): ?int
    {
        return $this->nb_of_lines;
    }

    public function setNbOfLines(int $nb_of_lines): self
    {
        $this->nb_of_lines = $nb_of_lines;

        return $this;
    }

    public function getCompetition(): ?Competition
    {
        return $this->Competition;
    }

    public function setCompetition(?Competition $Competition): self
    {
        $this->Competition = $Competition;

        return $this;
    }

    public function getNbOfParticipants(): ?int
    {
        return $this->nb_of_participants;
    }

    public function setNbOfParticipants(?int $nb_of_participants): self
    {
        $this->nb_of_participants = $nb_of_participants;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(?int $duration): self
    {
        $this->duration = $duration;

        return $this;
    }
}
