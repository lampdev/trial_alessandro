# Trial task

## Installation
1. Clone project.
2. Create .env file
3. Install packages
```
composer install
```
4. Run migrations:
```
php artisan migrate
```

## Command usage
```
php artisan coin-gecko:get-coins

// with platform contract addresses included
php artisan coin-gecko:get-coins --include-platforms
```
## Using Pro API version
Update ```COIN_GECKO_API_KEY``` in your ```.env``` file with your CoinGecko API key.
