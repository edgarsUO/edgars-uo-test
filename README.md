# edgars-uo-test

Welcome to test app.

Install instructions:
1. Clone repository to your desired dir **git clone git@github.com:edgarsUO/edgars-uo-test.git .**
2. Add **.env** file attached in email to **/'project_root'/docker/** dir
3. Add **prod.decrypt.private.php** file to **/'project_root'/src/config/secrets/prod/** dir
4. Free up localhost ports **80, 5432, 9000**
5. From **/'project_root'/docker/** execute **docker-compose up --build**
6. Navigate to **http://localhost:80/**

Troubleshooting:
1. In case there are permissions issues to /'project_root'/var/cache/ update
**/'project_root'/docker/php-fpm/Dockerfile** at line 19 -> **RUN usermod -u <docker_user> www-data**.
Correct user can be retrieved from php8.0-fpm container
