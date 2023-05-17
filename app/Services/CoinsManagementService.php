<?php

namespace App\Services;

use App\DTO\CoinDTO;
use App\DTO\PlatformCoinsDTO;
use App\Models\Coin;
use Illuminate\Support\Collection;

class CoinsManagementService
{
    /**
     * @param  PlatformManagementService  $platformManagementService
     */
    public function __construct(
        private readonly PlatformManagementService $platformManagementService
    ) {
    }

    /**
     * Update Coin platform addresses.
     * All contract addresses which are not PlatformCoinDTO will be skipped.
     *
     * @param  Coin  $coin Coin to update
     * @param  array  $contractAddresses array of PlatformCoinDTO
     * @return void
     */
    public function saveContractAddresses(Coin $coin, array $contractAddresses): void
    {
        $platforms = [];

        foreach ($contractAddresses as $contractAddress) {
            if (! $contractAddress instanceof PlatformCoinsDTO) {
                continue;
            }

            $platform = $this->platformManagementService->findOrCreateByExternalId($contractAddress->platformExternalID);
            $platforms[$platform->id] = ['contract_address' => $contractAddress->contractAddress];
        }

        $coin->platforms()->sync($platforms);
    }

    /**
     * Save coin from CoinDTO.
     * Updates platform contract addresses if $coinDTO is not null.
     * If $coinDTO is null it means that platform contract addresses weren't requested.
     *
     * @param  CoinDTO  $coinDTO Coin data
     * @return Coin updated Coin
     */
    public function saveCoin(CoinDTO $coinDTO): Coin
    {
        $coin = Coin::firstOrNew(['external_id' => $coinDTO->externalID]);

        $coin->fill([
            'symbol' => $coinDTO->symbol,
            'name' => $coinDTO->name,
        ]);

        $coin->save();

        if ($coinDTO->platforms !== null) {
            $this->saveContractAddresses($coin, $coinDTO->platforms);
        }

        return $coin;
    }

    /**
     * Save coins from array of CoinsDTO.
     * All array values that are not instances of CoinDTO will be skipped.
     *
     * @param  array  $coins array of CoinDTOs
     * @return Collection collection of Coins
     */
    public function saveCoins(array $coins): Collection
    {
        return collect($coins)->filter(fn ($coin) => $coin instanceof CoinDTO)
            ->map(fn ($coin) => $this->saveCoin($coin));
    }
}
