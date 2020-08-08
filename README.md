# Technical Challenge
### Server requirements
- PHP 7.4
- MySQL 8.0

### Server configurations (Nginx)
- client_max_body_size 2048M
- memory_limit 256M
- upload_max_filesize 64M
- post_max_size 256M

## Installation
Install this application with composer using the following command:

`composer install`

After installation, you have to copy the .env.example file and rename it into .env,
where you have to add the database credentials, and the mail credentials
(I'm using mailhog for send emails - they are used just for the password reset).

Now, run the following command for creating the tables and populating them with predefined data:

`php artisan migrate --seed`

## Usage
For authenticate in the application, use the following credentials:
 
##### Admin
 email: admin@challenge.local <br>
 pass: admin
 
##### Pilot
 email: pilot@challenge.local <br>
 pass: pilot
  
##### User
 email: user@challenge.local <br>
 pass: user

For generating dynamically trainings, use the following command, where _n_ is number of trainings 
(by default it will be generated 20 trainings, without videos):

`php artisan generate:trainings n`

## Testing
Copy the .env.example file and rename it into .env.testing,
where you have to add the database credentials, with new database name, ex. _technical_challenge_testing_.

Run the following command:

`$ phpunit`


