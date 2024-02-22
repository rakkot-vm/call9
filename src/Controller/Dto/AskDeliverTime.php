<?php

declare(strict_types=1);

namespace App\Controller\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class AskDeliverTime
{
    #[Assert\NotBlank]
    #[Assert\DateTime]
    private ?\DateTime $initDate = null;

    private ?string $provider = '';

    #[Assert\NotNull]
    #[Assert\Length(min: 2, max: 3)]
    private ?string $shippingAddress = null;

    public function getInitDate(): ?\DateTime
    {
        return $this->initDate;
    }

    public function setInitDate(?\DateTime $value): void
    {
        $this->initDate = $value;
    }

    public function getProvider(): ?string
    {
        return $this->provider;
    }

    public function setProvider(?string $value): void
    {
        $this->provider = $value;
    }

    public function getShippingAddress(): ?string
    {
        return $this->shippingAddress;
    }

    public function setShippingAddress(string $value): void
    {
        $this->shippingAddress = $value;
    }
}
