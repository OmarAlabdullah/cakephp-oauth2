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
