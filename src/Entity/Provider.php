<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ProviderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: ProviderRepository::class)]
#[UniqueEntity(fields: "name")]
class Provider
{
    public const bool WORLD_SHIPING = true;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: "provider", targetEntity: TimeRule::class)]
    private Collection $timeRules;

    #[ORM\OneToMany(mappedBy: "provider", targetEntity: InitiateTimeRule::class)]
    private Collection $initiateTimeRules;

    public function __construct()
    {
        $this->timeRules = new ArrayCollection();
        $this->initiateTimeRules = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getTimeRules(): Collection
    {
        return $this->timeRules;
    }

    public function getInitiateTimeRules(): Collection
    {
        return $this->initiateTimeRules;
    }
}
