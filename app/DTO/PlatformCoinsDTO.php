<?php

namespace App\DTO;

/**
 * @property string $coinExternalID Coin external id
 * @property string $platformExternalID platform external id
 * @property string|null $contractAddress coin contract address for platform
 */
readonly class PlatformCoinsDTO
{
    public function __construct(
        public string $coinExternalID,
        public string $platformExternalID,
        public ?string $contractAddress
    ) {
    }
}
