# Tracy

[![Build Status](https://travis-ci.com/daniel-werner/tracy.svg?branch=master)](https://travis-ci.com/daniel-werner/tracy)

## Track Running And Cycling

### Introduction
Tracy is an open source fitness tracking application with the main focus on simplicity.
It has only the basic features like visualization on map and basic analytics.
The main goal of this application is to learn the Laravel framework.

### Key features
1. Import workout from GPX file
2. Import workouts from [endomondo.com]
3. Visualize workouts on the map
4. Analyze workouts: speed, heart rate, and altitude

### Installation
1. Clone the source code
2. Install the dependencies using composer `composer install`
3. Make a copy of the `.env.example` file and rename it to `.env`
4. Create a database and set up database parameters in `.env`

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=homestead
DB_USERNAME=homestead
DB_PASSWORD=secret
```

5. (optional) Set up the parameters in `.env` for seeding the database with an initial
user

```
SEED_USER_EMAIL="john@email.com"
SEED_USER_NAME="John Doe"
SEED_USER_PASSWORD="secret"
```

6. (optional) Set up you endomondo.com login parameters

```
ENDOMONDO_LOGIN="your@email.com"
ENDOMONDO_PASSWORD="secret"
```

7. Generate the application key
 
 ```
 php artisan key:generate
 ```
 
8. Run the migrations

```
php artisan migrate
```

9. (optional) Seed the database

```
php artisan db:seed
```


For any additional information on laravel installation please see 
[https://laravel.com/docs/5.6/installation]

### Usage
#### Import workouts from [endomondo.com]
Tracy uses an unofficial api ([https://github.com/fabulator/endomondo-api])
to import the workouts from endomondo. The login details for endomondo 
are stored in the `.env` file which is not ideal, but endomondo does not
provide any public api. Import all workouts using the following command:
```
    php artisan import:endomondo
```

By default the import command only creates the new workouts, and skips the
 existing (already imported) ones. If you want to delete all existing workouts
 use the `--clear` option. Please note that the --clear deletes all workouts
 associated with your user. The email address of the Tracy user should be the same
 as the endomondo login email.
 
 #### Import from GPX file
 Use the Workouts/import menu to import a workout from a GPX file.
 
 #### Import from TCX file
 Coming soon...
 
 
### Demo
Demo application can be found on [tracy.wernerd.info]
A demo GPX file for testing could be found here: [https://www.mapbox.com/help/data/run.gpx]
 
### Lincence
MIT Licence



