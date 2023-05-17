<?php

namespace App\Services\API;

use Exception;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class CoinGeckoAPIService
{
    /**
     * @var string header attribute name used for authorization for CoinGecko API
     */
    protected string $headerAttributeName;

    /**
     * @var string|null CoinGecko API key
     */
    protected ?string $apiKey;

    /**
     * @var string URL for CoinGecko requests
     */
    protected string $apiUrl;

    /**
     * @var string default message for errors
     */
    protected const DEFAULT_ERROR_MESSAGE = 'Unknown error while processing CoinGecko request.';

    public function __construct()
    {
        $this->headerAttributeName = config('services.coin_gecko.api.header_attribute');

        // Skip empty string from api_key possible variants
        $this->apiKey = config('services.coin_gecko.api.key') === ''
            ? null
            : config('services.coin_gecko.api.key');

        $this->apiUrl = $this->apiKey !== null
            ? config('services.coin_gecko.api.url.pro')
            : config('services.coin_gecko.api.url.usual');
    }

    /**
     * Returns PendingRequest with authorization for future request.
     *
     * @return PendingRequest PendingRequest with auth headers
     */
    public function getRequestWithAuth(): PendingRequest
    {
        return ($this->apiKey ?? false)
            ? Http::acceptJson()
                ->withHeaders([$this->headerAttributeName => $this->apiKey])
            : Http::acceptJson();
    }

    /**
     * Gets coins list from CoinGecko.
     *
     * @param  bool  $includePlatforms include platform contract address flag
     *
     * @throws Exception
     */
    public function getCoins(bool $includePlatforms = false): array
    {
        $response = $this->getRequestWithAuth()
            ->get($this->apiUrl.'/coins/list', [
                'include_platform' => $includePlatforms ? 'true' : 'false',
            ]);

        if (! $response->successful()) {
            throw new Exception($this->getResponseErrorMessage($response));
        }

        return $response->json();
    }

    /**
     * Returns error message from response or default one.
     *
     * @param  Response  $response Response from CoinGecko.
     * @return string error message
     */
    public function getResponseErrorMessage(Response $response): string
    {
        return 'CoinGecko error response: '.($response->json('status.error_message' ?? self::DEFAULT_ERROR_MESSAGE));
    }
}
