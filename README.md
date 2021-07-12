
### Preparation:

0. Install `docker` and `docker-compose`
1. Run
    ```bash
    docker-compose build && docker-compose up -d
   ```
2. Run
    ```bash
   docker-compose exec php composer install
    ```
3. Run
    ```bash
   docker-compose exec php bin/console doctrine:migration:migrate --no-interaction
    ```

### Running command for ticker data update:

```bash
docker-compose exec php bin/console app:ticker-data tBTCUSD -vvv
```

### Get SMA data from API:

```bash
curl "http://localhost:8080/ticker/sma?period=5"
```
