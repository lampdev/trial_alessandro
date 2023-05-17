<?php

namespace App\Console\Commands;

use App\DTO\CoinDTO;
use App\Services\API\CoinGeckoAPIService;
use App\Services\CoinsManagementService;
use Exception;
use Illuminate\Console\Command;

class FetchCoinsList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'coins:list {--include_platforms : Include platform contract addresses}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Requests coins list from CoinGecko and saves to database';

    /**
     * @param  CoinGeckoAPIService  $coinGeckoAPIService CoinGecko API service
     * @param  CoinsManagementService  $coinsManagementService Coin management service
     * @return void
     */
    public function handle(
        CoinGeckoAPIService $coinGeckoAPIService,
        CoinsManagementService $coinsManagementService
    ): void {
        $this->info('Requesting coins.');

        try {
            $coins = $coinGeckoAPIService->getCoins($this->option('include_platforms'));
        } catch (Exception $exception) {
            $this->error($exception->getMessage());

            return;
        }

        $this->info('Transforming response data to Data Transfer Objects.');

        $coins = CoinDTO::fromCoinsRequest($coins);

        $this->info('Saving data.');

        $coinsManagementService->saveCoins($coins);

        $this->info('Success.');
    }
}
