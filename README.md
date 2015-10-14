# Double-opt-in registration for Laravel 5.1

This package provides double-opt-in registration with user activation to Laravel 5.1.

Features:

- Newly registered users are marked "inactive" and can't login into the application
- An email with a verification link is sent to the user after registration
- A form for requesting a new activation email (in case the first activation email was lost)  

## Prerequisites  

The package extends the functionality of the trait Illuminate\Foundation\Auth\AuthenticatesUsers, 
so if you are using a custom Authentication Controller this package might not work for you.
Also the package asumes the User model and user table are used for authentication (may be decoupled in later versions)

## Usage

Install the package via composer:
```
	composer require "m3rten/laravel-double-opt-in"
```

Add the Service Provider to **config/app.php**
```php
    'providers' => [
        /* ... */
        M3rten\DoubleOptIn\DoubleOptInServiceProvider::class,
    ],
```

Replace the used traits in **app/Http/Controllers/Auth/AuthController.php** with:
```php
    use AuthenticatesUsers, RegisterAndActivateUsers, ThrottlesLogins {
        RegisterAndActivateUsers::getCredentials insteadof AuthenticatesUsers;
    }
```

Publish the packages assets and run the migration.
```
    php artisan vendor:publish
	php artisan migrate
```
If you'd like to alter the provided blade templates you may edit the files in **/resources/views/vendor/doubleoptin**. 
If you'd like to alter the provided language files you may edit the files in **/resources/lang/vendor/doubleoptin**. 

Add the activation an verification routes to your **app/Http/routes.php**
```php
    Route::get('/verify/{token}', ['as' => 'activation.verify','uses' => 'Auth\AuthController@verify',]);
    Route::get('/activate', ['as' => 'activation.edit','uses' => 'Auth\AuthController@editActivation',]);
    Route::post('/activate', ['as' => 'activation.update','uses' => 'Auth\AuthController@postActivation',]);
```

Error and success messages are output via Laravels flash messaging using the variables "message" and "message-type". 
You may include the message output in your login and registration forms:
```php
    @include('doubleoptin::partials.message')    
```
