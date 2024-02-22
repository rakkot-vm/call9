<?php

declare(strict_types=1);

namespace App\Service\Exception;


class ProviderNotFound extends \RuntimeException
{
    public function __construct(?string $providerName)
    {
        if ($providerName === null) {
            parent::__construct('Provider not found.');
        }

        parent::__construct(sprintf('Provider "%s" not found.', $providerName));
    }
}
