
----- System requirements - check https://laravel.com/docs/7.x -----
    PHP >= 7.2.5
    BCMath PHP Extension
    Ctype PHP Extension
    Fileinfo PHP extension
    JSON PHP Extension
    Mbstring PHP Extension
    OpenSSL PHP Extension
    PDO PHP Extension
    Tokenizer PHP Extension
    XML PHP Extension

----- System installation -----

- Create database
    touch database/ufirst.sqlite

- Create environment configuration file .env
    cp .env.example .env

- Create database
    php artisan migrate

- Add a crontab to run the scheduled tasks (in this case, download the new EPA file version)
    crontab -e
- Copy the following line in the code (replace path-to-your-project with the project path)  
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1

----- Info -----
Epa file is downloaded in an async way using a command every 5 minutes.
It doesn't overlap, if last command is still working it doesn't run again

- Call the API (limit and offset are params and can be ignored)
    http://localhost:8000/api/json?limit=5&offset=47743

----- Commands -----
- List available artisan commands (Laravel ones, and also the created like epa:get_file) 
    php artisan list

- Exec the command (not necessary, just for testing purposes) 
    php artisan epa:get_file

