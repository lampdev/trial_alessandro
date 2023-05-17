<?php

namespace App\DTO;

/**
 * @property string $externalID id from external service
 * @property string $symbol Coin symbol
 * @property string $name Coin name
 * @property array|null $platforms platform contract addresses(null if update of platform contract addresses isn't needed)
 */
readonly class CoinDTO
{
    public function __construct(
        public string $externalID,
        public string $symbol,
        public string $name,
        public ?array $platforms = null
    ) {
    }

    /**
     * Returns CoinDTO from coin data.
     *
     * @param  array  $attributes coin attributes
     * @return CoinDTO
     */
    public static function singleFromCoinsRequest(array $attributes): static
    {
        $platforms = null;

        // Map contract addresses for coins if platform addresses exists
        if (isset($attributes['platforms']) && is_array($attributes['platforms'])) {
            $platforms = array_map(
                fn ($platformExternalId, $contractAddress) => new PlatformCoinsDTO(
                    $attributes['id'],
                    $platformExternalId,
                    $contractAddress
                ),
                array_keys($attributes['platforms']),
                array_values($attributes['platforms'])
            );
        }

        return new CoinDTO(
            $attributes['id'],
            $attributes['symbol'],
            $attributes['name'],
            $platforms
        );
    }

    /**
     * Returns array of CoinDTO from array of coins data.
     *
     * @param  array  $coins array of coins data
     * @return array array of CoinDTO
     */
    public static function fromCoinsRequest(array $coins): array
    {
        return array_map(
            fn ($coin) => self::singleFromCoinsRequest($coin),
            $coins
        );
    }
}
