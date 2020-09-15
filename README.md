Geo
-------------------

RPC 2.0 API MICROSERVICE BASED ON [IJSON](https://github.com/lega911/ijson)

## MENU
 - [ENVIRONMENTS](#environments)
 - [ENVIRONMENTS WITHOUT CONTROL PANEL](#environments-without-control-panel)
 - [RUN WITH CODE](#run-with-code)
 - [RUN WITH DOCKER](#run-with-docker)
 - [DOCUMENTATION](#documentation)

--------------

### <a id="environments"></a>ENVIRONMENTS:
 - `IJSON_HOST` - Invert json host and port (with protocol). Default: `http://localhost:8001`
 - `PROJECT_ALIAS` - panel, apple, etc..
 - `APP_ENV` - dev, prod.
 - `PANEL_ALIAS` - Alias for control-panel. Default: `panel`

### <a id="environments-without-control-panel"></a>ENVIRONMENTS without control-panel:
 - `CONTROL_PANEL_DISABLE` - Disable control panel microservice config obtain. Default: no
 - `AUTHORIZATION_DISABLE` - Disable authorization microservice import rules. Default: yes
 - `DATABASE_SEEDER_DISABLE` - Disable auto seeding data. Default: no
 - `MYSQL_HOST` - Mysql host.
 - `MYSQL_PORT` - Mysql port.
 - `MYSQL_DATABASE` - Mysql database.
 - `MYSQL_USER` - Mysql user.
 - `MYSQL_PASSWORD` - Mysql password.

### <a id="run-with-docker"></a>RUN WITH DOCKER:
```bash
docker pull docker.pkg.github.com/kakadu-dev/symfony-microservice-geo/geo:1.0.0-staging
docker run --env IJSON_HOST=http://host.docker.internal:8001 --env PROJECT_ALIAS=example --env APP_ENV=dev docker.pkg.github.com/kakadu-dev/symfony-microservice-geo/geo:1.0.0-staging
```

### <a id="run-with-code"></a>RUN WITH CODE:
 - Get code:
    - `git clone https://github.com/kakadu-dev/symfony-microservice-geo.git`

    - Run docker container `ijson` and `mysql` in `docker-compose.yml`
      ```bash
        docker-compose run mysql
        docker-compose run ijson
      ```
      or
      ```bash
        docker-compose up
      ```
    - Install dependencies `composer install`
    - Create configuration:
        ```bash
            ./bin/console microservice:configure
        ```
        or for manual configuration add the `manual` key
        ```bash
            ./bin/console microservice:configure manual
        ```
    - Create database `php bin/console migrations:db`
    - Make migrations `php bin/console doctrine:migrations:migrate --no-interaction`
    - Set default data for database `php bin/console microservice:seed`
    - Run microservice `php bin/console microservice:start`
    - See `scratches` folder for make requests
    
### <a id="documentation"></a>DOCUMENTATION:
 - Generate docs `composer run-script docs` or [OPEN COMPILED DOCS](https://microservices-docs.kakadu.bz/geo/index.html)
 - Open `apidoc-generated/index.html` in root dir
