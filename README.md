# Oauth2

A Template for creating applications with [CakePHP](https://cakephp.org) 4.1

The framework source code can be found here: [cakephp/cakephp](https://github.com/cakephp/cakephp).

### Create a new CakePHP Application

1. Clone and/or copy this project.
1. Copy the `template-app` folder in `xel-config-files` and rename to the name of the new application.
1. Change the Crypto key value in `src/Controller/PagesController.php`
1. Change config values like Name, ShortName and, database config and others in the new config.
1. Set the correct folder name in `$configPath` in `bootstrap.php`.
1. Change the name, description and other fields in `composer.json`.
1. Run `composer update`.
1. Set the application details in `resources/build.properties` and set project name in `build.xml`.
1. Change name and other texts in `templates/Pages/home.php`.
1. Happy coding your new CakePHP application!

### Create a driver

There is no template project for drivers, because it would be completely empty.
Just copy another driver project and remove all code that you do not need.


### Database structure
```
create table access_tokens
(
identifier varchar(100)    not null
primary key,
user_id    bigint unsigned null,
client_id  char(36)        not null,
scopes     text            null,
revoked    tinyint(1)      not null,
expires_at datetime        null
)
collate = utf8mb4_unicode_ci;

create index oauth_access_tokens_user_id_index
on access_tokens (user_id);

create table authorization_codes
(
identifier varchar(100)    not null
primary key,
user_id    bigint unsigned not null,
client_id  char(36)        not null,
scopes     text            null,
revoked    tinyint(1)      not null,
expires_at datetime        null
)
collate = utf8mb4_unicode_ci;

create index oauth_auth_codes_user_id_index
on authorization_codes (user_id);

create table clients
(
identifier            char(36)        not null
primary key,
user_id               bigint unsigned null,
name                  varchar(255)    not null,
secret                varchar(100)    null,
redirect              text            not null,
allow_plain_text_pkce tinyint(1)      not null,
grants                varchar(128)    null,
isConfidential        tinyint(1)      null
)
collate = utf8mb4_unicode_ci;

create index oauth_clients_user_id_index
on clients (user_id);

create table refresh_tokens
(
identifier      varchar(100) not null
primary key,
access_token_id varchar(100) not null,
expires_at      datetime     null,
revoked         tinyint(1)   null
)
collate = utf8mb4_unicode_ci;

create index oauth_refresh_tokens_access_token_id_index
on refresh_tokens (access_token_id);

create table scopes
(
identifier varchar(80) not null
primary key,
is_default tinyint(1)  null
);

create table users
(
identifier bigint unsigned auto_increment
primary key,
username   varchar(255) not null,
password   varchar(255) not null,
constraint users_email_unique
unique (username)
)
collate = utf8mb4_unicode_ci;
```
