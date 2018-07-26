# Tracy

[![Build Status](https://travis-ci.com/daniel-werner/tracy.svg?branch=master)](https://travis-ci.com/daniel-werner/tracy)

## Track Running And Cycling

### Introduction
Tracy is an open source fitness tracking application with focus on simplicity 
inspired by the awesome Endomondo application. 
It has only the basic features like visualization on map and basic analytics.
Users need to register an account to use the application: Tracy supports form
based and Oauth2 authentication (Google and GitHub).
Workouts can be imported from standard gps xml files (GPX or TCX), 
or can be imported from the endomondo.com.

The backend is written in Laravel framework, the frontend uses vue.js, and the analytics
are created with open street maps, leafletjs, and Highcharts.
The main goal of this application is to learn the Laravel framework.

### Demo
Demo application can be found on [tracy.wernerd.info](http://tracy.wernerd.info),
for demo purposes please use the following credentials:


Email: demo@email.com

Password: 123123

or sign it using a Google or GitHub account

A demo GPX file for testing can be found [here](https://www.mapbox.com/help/data/run.gpx)

### Screenshots
#### Workout List
![tracy-workouts](https://user-images.githubusercontent.com/38726367/43262320-026995ee-90e0-11e8-9952-3383c7cfbb5f.png)
#### Workout details
![tracy-workout-details](https://user-images.githubusercontent.com/38726367/43262422-5af3a394-90e0-11e8-957c-94d0c8014e93.png)

### Key features
1. Form based login/registration and Oauth2 (Google and GitHub)
2. Import workouts from GPX or TCX file
3. Import workouts from [Endomondo](http://endomondo.com)
4. Visualize workouts on the map
5. Analyze workout parameters (speed, heart rate, and altitude) on chart

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
https://laravel.com/docs/5.6/installation

### Usage
#### Import workouts from [endomondo.com](http://endomondo.com)
Tracy uses an unofficial api (https://github.com/fabulator/endomondo-api)
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

 #### Import from GPX or TCX file
 Use the Workouts/import menu to import a workout from a GPX or TCX file.
 The system automatically recognises the file type (gpx or tcx).


### Lincence
MIT Licence
