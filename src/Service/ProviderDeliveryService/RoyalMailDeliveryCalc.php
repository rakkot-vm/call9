<?php

declare(strict_types=1);

namespace App\Service\ProviderDeliveryService;

use App\Controller\Dto\AskDeliverTime;
use App\Entity\InitiateTimeRule;
use App\Entity\Provider;
use App\Repository\ProviderRepository;
use App\Repository\TimeRuleRepository;
use App\Service\Exception\CountryNotSupportedForShipping;
use App\Service\Exception\ProviderNotFound;

class RoyalMailDeliveryCalc
{
    public function __construct(
        private TimeRuleRepository $timeRuleRepository,
        private ProviderRepository $providerRepository
    )
    {

    }

    public function calculate(AskDeliverTime $askDeliverTime): int
    {
        $provider = $this->resolveProvider($askDeliverTime->getProvider());

        $deliverOffset = $this->getDeliverOffsetDays($provider, $askDeliverTime->getInitDate());

        $timeDeliver = $this->getDeliverTime($provider, $askDeliverTime->getShippingAddress());



        return $deliverOffset + $timeDeliver;
    }

    public function getDeliverOffsetDays(Provider $provider, ?\DateTime $deliverStartTime): int
    {
        $deliverStartTime = $this->resolveDeliverStartTime($deliverStartTime);

        /** @var InitiateTimeRule[] $initiateTimeRules */
        $initiateTimeRules = $provider->getInitiateTimeRules()->getValues();
        $closestRule = null;

        foreach($initiateTimeRules as $rule){
            if($rule->getUntiltime() >= $deliverStartTime) {
                if($closestRule !== null){
                    if($rule->getUntiltime() < $closestRule->getUntiltime()) {
                        $closestRule = $rule;
                    }
                } else {
                    $closestRule = $rule;
                }
            }
        }

        return (int) $closestRule?->getStartToday();
    }

    private function getDeliverTime(Provider $provider, string $shippingAddress): int
    {
        $timeRules = $this->timeRuleRepository->getRuleByProviderAndCountry($provider, $shippingAddress);

        if($timeRules === null && $provider::WORLD_SHIPING) {
            $timeRules = $this->timeRuleRepository->getDefaultRuleByProvider($provider);
        }

        if($timeRules === null) {
            throw new CountryNotSupportedForShipping($shippingAddress);
        }

        return $timeRules->getTime();
    }

    private function resolveProvider(?string $providerId ): Provider
    {
        $provider = null;

        if ($providerId !== null) {
            $provider = $this->providerRepository->findOneBy(['id' => $providerId]);
        }

        if ($provider === null) {
            $provider = $this->providerRepository->findAll()[0];
        }

        if ($provider === null) {
            throw new ProviderNotFound($providerId);
        }

        return $provider;
    }

    private function resolveDeliverStartTime(?\DateTime $deliverStartTime): int
    {
        if($deliverStartTime === null) {
            $deliverStartTime = (new \DateTime())->format('G');
        }else {
            $deliverStartTime = $deliverStartTime->format('G');
        }

        return (int) $deliverStartTime;
    }

}
