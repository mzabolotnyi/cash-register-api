Symfony Cash Register API
========================

The "Symfony Cash Register API" provide API for cash registers.

Requirements
------------

* PHP 7.2+;
* MySQL 5.6+/MariaDB 10+;
* and the [usual Symfony application requirements][1]

Installation
------------

Create .env.local file and set DATABASE_URL:

```bash
$ cp .env .env.local
```

Create database manually or run command:

```bash
$ bin/console doctrine:database:create
```

Run deploy file:

```bash
$ sh deploy.sh
```

[Configure a web server][3] like Nginx or Apache to run the application.

Usage
-----

If everything was installed correctly - Swagger ApiDoc will be available on:

    http://{your_host}/api/doc

For creating new admin or cash register user use command:

```bash
$ bin/console user:create
```

Project uses [OAuth2][3] for authorizing users 

For creating new oAuth2 client use command:

```bash
$ bin/console oauth:create-client
```

For first getting access token need use Get access token API (see Authorization section in Api Doc) with grant type "password" and provide oAuth2 client's and user's credentials. In response access and refresh tokens would be returned. Access token's lifetime is 1 hour.

Then access token could be refresh using same API, but with grant type "refresh_token" and provided refresh token to it. If you lost refresh token, use grant type "password" again.

For each request to server need to provide Authorization header with access token:

    Authorization: Bearer {access_token}

Tests
-----

Execute this command to run tests:

```bash
$ ./bin/phpunit
```

[1]: https://symfony.com/doc/4.4/setup.html
[2]: https://symfony.com/doc/current/cookbook/configuration/web_server_configuration.html
[3]: https://github.com/FriendsOfSymfony/FOSOAuthServerBundle