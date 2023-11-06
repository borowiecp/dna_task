# How should I start
1. install required dependencies by running the `composer install` command
2. run tests by executing the `./vendor/bin/phpunit` command
3. for general simplicity the app uses the same sqlite file as the tests. 
As a result there's no need to migrate the database once the tests have been executed.
However, in case of any difficulties, the `php artisan migrate` and `php artisan migrate:rollback`
commands could be executed.
4. run the `php artisan serve` command to see whether the server starts

## Scenario
Let’s imagine you are working on API for backend of an online payment system.
The actors are: users (festival participants), merchants, event organiser.
We want to enable backoffice operations to the event organiser.

Currently implemented:
- Users can make a payment (with a virtual currency) to a specific merchant.
- Our system has a payment log.
- It means that information about payments are stored in a database.
- This data can be used for reporting.

# Tasks
## Task 1:
Please make a code review of the currently implemented solution.
## Task 2:
Add new endpoint which give total income for payments for selected time period for given merchant.


## Code Review
Notes:
* /transactions params naming convention is different for this endpoint that the other ones, we should stick to one of it, one is snake_case second is camelCase - I would use snake_case for model's vars and DB columns naming
* not sure why there is merchant_id in users table, the user should not be related to merchant
* on model creation we can use Model::create([]); instead of new Model(), $model->save();
* there is no request validation at all
* don't really get a point of using ModelDto instead of actual model, I feel Java influence here - to easily get json format we can use Laravel Resources class
* instead of checks like this:
  if (is_null($account)) {
  throw new \Exception("Account with id " . $accountId . " not found");
  }

you can simply query:
Account::query()->where('accountId', $accountId)->firstOrFail();
* also I would recommend to refactor the code based on PSR-12 coding standard
* unique on columns accountId (instead of userId) and merchantId is incorrect, those columns will be duplicated for sure
* wanted to keep the work < 1,5h, so I didn't have time add tests - fixed existing one
* had to add created_at and updated_at columns to be able to identify when records for all of those tables were created
