<?php

declare(strict_types=1);

namespace App\Service\DeliverTime;

use App\Controller\Dto\AskDeliverTime;
use App\Entity\InitiateTimeRule;
use App\Entity\Provider;
use App\Repository\ProviderRepository;
use App\Repository\TimeRuleRepository;
use App\Service\Exception\CountryNotSupportedForShipping;
use App\Service\Exception\ProviderNotFound;

class DeliverTimeCalc
{
    private const array NOT_BUSINESS_DAYS = [6, 0];

    public function __construct(
        private TimeRuleRepository $timeRuleRepository,
        private ProviderRepository $providerRepository
    )
    {
    }

    public function calculate(AskDeliverTime $askDeliverTime): int
    {
        $provider = $this->resolveProvider($askDeliverTime->getProvider());

        $timeDelivery = $this->getDeliveryTime($provider, $askDeliverTime->getShippingAddress());

        $deliverOffset = $this->getDeliverOffsetDays($provider, $askDeliverTime->getInitDate(), $timeDelivery);

        return $deliverOffset + $timeDelivery;
    }

    public function getDeliverOffsetDays(Provider $provider, ?\DateTime $deliverStartTime, int $timeDelivery): int
    {
        $deliverStartTime = $this->resolveDeliverStartTime($deliverStartTime);

        $startToday = $this->startToday($provider, $deliverStartTime);

        if($startToday === false) {
            $deliverStartTime = $deliverStartTime->modify('+ 1 day');
        }

        return $this->getDeliveryOffsetNonWorkingDays($timeDelivery, $deliverStartTime);
    }

    private function getDeliveryTime(Provider $provider, string $shippingAddress): int
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

    private function resolveDeliverStartTime(?\DateTime $deliverStartTime): \DateTime
    {
        if($deliverStartTime === null) {
            $deliverStartTime = new \DateTime();
        }

        return $deliverStartTime;
    }

    private function startToday(Provider $provider, \DateTime $deliverStartTime): bool
    {
        /** @var InitiateTimeRule[] $initiateTimeRules */
        $initiateTimeRules = $provider->getInitiateTimeRules()->getValues();
        $closestRule = null;
        $deliverStartHour = $deliverStartTime->format('G');

        foreach($initiateTimeRules as $rule){
            if($rule->getUntiltime() >= $deliverStartHour) {
                if($closestRule !== null){
                    if($rule->getUntiltime() < $closestRule->getUntiltime()) {
                        $closestRule = $rule;
                    }
                } else {
                    $closestRule = $rule;
                }
            }
        }

        return $closestRule?->getStartToday();
    }

    private function getDeliveryOffsetNonWorkingDays(int $timeDelivery,\DateTime $deliverStartTime): int
    {
        $deliveryOffset = 0;

        for($i = $timeDelivery; $i > 0; $i--) {
            $nonWorkDay = in_array((int)$deliverStartTime->format('w'), self::NOT_BUSINESS_DAYS);

            if($nonWorkDay) {
                $deliveryOffset++;
                $i++;
            }

            $deliverStartTime->modify('+ 1 day');
        }

        return $deliveryOffset;
    }
}
