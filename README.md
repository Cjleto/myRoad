
# myRoad test

Here my assumptions for the test
- the permission to update a travel is only for admin user
- the travel code is unique
- the travel name is not unique
> :memo: **NOTE:** *change conf by config('myconstants.travels.unique_name')*
- the tour name is unique and it is composed by a country code IT, the firsts three letters of the travel name and the starting tour date in YYYYMMDD format
 
## Installation

Follow these steps to install the project:
1. Clone the repository: `git clone https://github.com/Cjleto/myRoad`
2. Install dependencies: `composer update` *(see warning details below )*
3. Copy the `.env.example` file to a new file named `.env` and modify the environment variables as needed
4. Start the container using sail: `./vendor/bin/sail up`
5. Create symbolic link for storage `sail artisan storage:link`
6. Run database migrations and initial seeders: `./vendor/bin/sail artisan migrate:fresh --seed`
  
> :warning: If you don't have the correct php version on your host, run this command in the project root directory:
```
docker run --rm \
	-u "$(id -u):$(id -g)" \
	-v "$(pwd):/var/www/html" \
	-w /var/www/html \
	laravelsail/php83-composer:latest \
	composer install --ignore-platform-reqs
```

## Initial data

What seeders insert:
- Role and Permission models
- Admin user with email admin@example.com and editor user with email editor@example.com
- Travels from the sample file
- Tours from the sample file
> :bulb: Use one of the two usernames above to log in, password = 'password'.
> :memo: Or create your own by artisan command ```sail artisan myroad:user-create```

## Utility 
- Generate API documentation using the command: `./vendor/bin/sail artisan scribe:generate`
- Access the generated API documentation at `http://localhost:${APP_PORT}/docs`
- Run static code analysis: `./vendor/bin/sail ./vendor/bin/phpstan analyse --memory-limit=2G`
- Check code style: `./vendor/bin/sail ./vendor/bin/pint --test`
- Run tests `./vendor/bin/sail artisan test`

## Usage
When app is running, go to `${APP_URL}\docs` to see the api documentation.

You can use its own interface to run api request, download the postman collection or openApi yaml
