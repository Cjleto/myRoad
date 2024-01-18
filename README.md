

# myRoad test

Here my assumptions for the test

- the permission to update a travel is only for admin user

- the travel code is unique
- the travel name is not unique
    > *change conf by config('myconstants.travels.unique_name')*

- the tour name is unique and it is composed by a country code IT, the firsts three letters of the travel name and the starting tour date in YYYYMMDD format

## Installation

Follow these steps to install the project:

1. Clone the repository: `git clone https://github.com/Cjleto/myRoad`
2. Install dependencies: `composer update`
3. Copy the `.env.example` file to a new file named `.env` and modify the environment variables as needed
4. Start the container using sail: `./vendor/bin/sail up`
5. Run database migrations and initial seeders: `./vendor/bin/sail artisan migrate:fresh --seed`
6. Generate API documentation using the command: `./vendor/bin/sail artisan scribe:generate`
7. Access the generated API documentation at `http://localhost:${APP_PORT}/docs`
8. Run static code analysis: `sail php ./vendor/bin/phpstan analyse --memory-limit=2G`
9. Check code style: `sail php ./vendor/bin/pint --test`

## Usage

When app is running, go to `${APP_URL}\docs` to see the api documentation.

You can use its own interface to run api request, download the postman collection or openApi yaml

