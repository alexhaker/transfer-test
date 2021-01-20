### Transfer between wallets solution

#### How to run the solution?
1. Run `docker-compose up`. It will build and run mysql, nginx and php-fpm containers.
It also should recreate the database and fill it by test data.
**_Note! Ports 80 and 3307 should not bind to any service on your local host machine._**
2. Open in browser `http://localhost`. The page provides you to submit simple form with json to money transfer endpoint.

#### How to run tests?
1. Enter into php container `docker-compose exec php sh`.
2. Run `./bin/phpunit`.
It will execute two tests.
The first one is positive, which tries to transfer from one wallet to another twice.
The first attempt should be successful and the second one should fail due to insufficient amount of money on source wallet.
The second negative test makes different requests with missing input data.

#### How to change transfer commission?
`config/services.yaml` contain `$transferCommissionPercent` setting that is responsible for transfer commission.

#### Solution details
The solution contains:
- `TransferController` which handles POST requests to `/transfer` endpoint.
For simplification of test usage it accept form data with json field that should contain valid json for creating transfer resource.
The responsibilities of controller are to handle and validate input data, call `MoneyTranferService` and return response from it.
Input json should contain:
````
{
    "sourceWallet": 1,
    "destinationWallet": 3,
    "amount": 100
}
````
where `sourceWallet` should contain id of the wallet to transfer from,
`destinationWallet `is an id of wallet to transfer to, `amount` is how much to tranfer in copecks.
- Two entities: `Wallet` and `MoneyTransfer` with Many-to-one relations.
`Wallet` keeps data about wallet amount and its owner.
`MoneyTransfer` records data about money transfer between wallet, their amount, commission and date.
- Two repositories: `WalletRepository` and `TranferRepository`.
`WalletRepository` has implementation of atomic increment/decrement of wallet amount.
- `MoneyTranferService` handles all business logic according transfer between wallets.
It perform validation on existence of both wallets, validate the transfer amount that should be greater than 0, check the amount on the source wallet that should be equal or greater then transfer amount + commission.
After validation it creates `MoneyTransfer` record, decrement the source wallet by amount with commission and increment the destination wallet by amount without commission.
All operations are performed under one transaction that make pessimistic lock of both wallets and avoid balance inconsistency due to parallel transactions.
The response will return info about transfer including amount, commission, data and updated wallet balances.

#### What could be improved?
Current solution doesn't contain any asynchronous execution due to the fast execution and non-blocking operations.
If the transfer process could take long time we should upgrade th solution with:
- add status attribute to `MoneyTransfer` entity that will handle statues like created, in_progress, completed, failed.
- on transfer request: just create `MoneyTransfer` entity with status created and initiate job message and pass it to queue server (RabbitMQ) for async execution.
- return to client the id of `MoneyTranfer` with location(endpoint) where the status of transfer could be tracked. 
- implement worker that will handle the transfer job and change the status of `MoneyTransfer`.
- implement the `GET /tranfer/{id}/status` endpoint that will return info (including status) of transfer transaction. 