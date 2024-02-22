<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\DeliveryInitiateTimeRulesRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;

#[ORM\Entity(repositoryClass: InitiateTimeRulesRepository::class)]
class InitiateTimeRule
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'initiateTimeRules')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Provider $provider = null;

    #[ORM\Column(length: 2)]
    private ?int $untilTime = null;

    #[ORM\Column]
    private bool $startToday = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProvider(): ?Provider
    {
        return $this->provider;
    }

    public function setProvider(?Provider $provider): static
    {
        $this->provider = $provider;

        return $this;
    }

    public function getUntiltime(): ?int
    {
        return $this->untilTime;
    }

    public function setUntiltime(int $untilTime): static
    {
        $this->untilTime = $untilTime;

        return $this;
    }

    public function getStartToday(): ?bool
    {
        return $this->startToday;
    }

    public function setStartToday(bool $startToday): static
    {
        $this->startToday = $startToday;

        return $this;
    }
}
