# Oauth2-SSO

The League library can be found here: [OAuth 2.0 Server/LEAGUE](https://oauth2.thephpleague.com)

The framework source code can be found here: [cakephp/cakephp](https://github.com/cakephp/cakephp).

![alt text](oauth.png?raw=true)

### Create a new CakePHP SSO according to Oauth2 architecture

1. use  composer to download the league library `composer require league/oauth2-server`.
2. Generate a private key and put it in text file.
3. Generate a public key (encryption key), or you can create a file and write what you want.
4. you have to use the previous key files to build the "AuthorizationServerProvider".
5. build de oauth2 database use that database script (mysql) below.
6. you have to build the Entities according to the entities of League library use the entity interfaces of the league library, you can use symfony bundle or laravel passport as example.
7. you have to build the tables with the right columns according to the database.
8. Build the ORM layer according to the Repositories of the League library.
9. You can now build the post token endpoint using the authorization server, you can use symfony bundle or laravel passport as example.
10. You can now build the get authorize endpoint using the authorization server, you can use symfony bundle or laravel passport as example.
11. you can test the endpoints with postman. Attention the type of the body is 'x-ww-form-urlencoded'.
12. you have to build a login/registration endpoints to prove the user and the client.

![alt text](oauthproces.png?raw=true)

## Authorize endpoint



## Token endpoint

The token endpoint has som of grant types, if you want to use one of this grant,
you have to put it in the AuthorizationServerProvider. and then if you create a client in the database you have to save which grants is the client allowed to use.

I have access alle grant types except the implicit grant. I will explain per grant type how this is works.

### Authorization_code grant type

The most important grant type. With this grant can the client get the user data by the code from the authorize endpoint.

![alt text](authorization_code.png?raw=true)


### Password grant type

the client can use this type and get access token and refresh token by username and password.

![alt text](password.png?raw=true)

### Refresh_token grant type

After that the access token expires the client still have the refresh token. The client can exchange this refresh token with a new access token and refresh token.

![alt text](refresh_token.png?raw=true)

### Client_credentials

This grant is suitable for machine-to-machine authentication, for example would be a client making requests to an API that don’t require user’s permission.

![alt text](client_credentials.png?raw=true)






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
