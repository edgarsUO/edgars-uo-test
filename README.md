# edgars-uo-test

Welcome to test app.

Install instructions:
1. Clone repository                                             ### **git clone git@github.com:edgarsUO/edgars-uo-test.git**
2. Add **.env** file attached in email to                       ### **<project_root>/docker/.env**
3. Add **prod.decrypt.private.php** file attached in email to   ### **<project_root>/src/config/secrets/prod/prod.decrypt.private.php**
4. Free up **localhost** ports                                  ### **80, 5432, 9000**
5. From **<project_root>/docker** execute                       ### **docker-compose up --build**
6. Navigate to                                                  ### **http://localhost:80/**

Troubleshooting:
1. In case there are production environment permissions issues related to cache
update **<project_root>/docker/php-fpm/Dockerfile** at line 19 ### **RUN usermod -u <your_docker_user> www-data**.
