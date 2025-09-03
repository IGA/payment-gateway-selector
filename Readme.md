## About Project
This project is used to identify the most cost-effective provider.

## Algorithm
### Cost Formula
- `TRY` Cost = max(amount * commission_rate, min_fee)
- `USD` Cost = max(amount * commission_rate * 1.01, min_fee)

### Priority
1. Lowest Cost
2. Higher Priority
3. Lower commission_rate
4. Alphabetical pos_name

`In case of a tie, the decision will be made based on the next step.`

## Setup
```
git clone https://github.com/IGA/payment-gateway-selector
cd payment-gateway-selector
cp .env.example .env
docker compose up -d --build
docker compose exec app composer install
docker compose exec app php artisan migrate
docker compose exec app php artisan queue:work

# To retrieve POS rates via MockAPI, open the commented-out code in the routes/console.php file and run the following command:
docker compose exec app php artisan schedule:run
```

## Test
```
docker compose exec app php artisan test
```

## Debug Tool
```
http://localhost:8080/telescope
```

## Tech Stack
- Laravel 12
- PHP 8.3
- PostgreSQL 16
- Docker