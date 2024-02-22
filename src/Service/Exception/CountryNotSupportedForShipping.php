<?php

declare(strict_types=1);

namespace App\Service\Exception;


class CountryNotSupportedForShipping extends \RuntimeException
{
    public function __construct(?string $country)
    {
        if ($country === null) {
            parent::__construct('Delivery service does not support delivery to the selected country');
        }

        parent::__construct(sprintf('Delivery service does not support delivery to "%s".', $country));
    }
}
