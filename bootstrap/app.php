<?php

require_once __DIR__.'/../vendor/autoload.php';

(new Laravel\Lumen\Bootstrap\LoadEnvironmentVariables(
    dirname(__DIR__)
))->bootstrap();

date_default_timezone_set(env('APP_TIMEZONE', 'UTC'));

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| Here we will load the environment and create the application instance
| that serves as the central piece of this framework. We'll use this
| application as an "IoC" container and router for this framework.
|
*/

$app = new Laravel\Lumen\Application(
    dirname(__DIR__)
);

$app->withFacades();
$app->withEloquent();

/*
|--------------------------------------------------------------------------
| Register Container Bindings
|--------------------------------------------------------------------------
|
| Now we will register a few bindings in the service container. We will
| register the exception handler and the console kernel. You may add
| your own bindings here if you like or you can make another file.
|
*/

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

/*
|--------------------------------------------------------------------------
| Register Config Files
|--------------------------------------------------------------------------
|
| Now we will register the "app" configuration file. If the file exists in
| your configuration directory it will be loaded; otherwise, we'll load
| the default version. You may register other files below as needed.
|
*/

$app->configure('app');
$app->configure('auth');
$app->configure('jwt'); 
$app->configure('mail');  // Carrega o arquivo de configuração para o Mail

/*
|--------------------------------------------------------------------------
| Register Facades
|--------------------------------------------------------------------------
|
| We will register the facades which allow easy usage of static proxies.
| For example, you may use the "Mail" facade for sending email easily.
|
*/

class_alias(Illuminate\Support\Facades\Mail::class, 'Mail'); // Habilita o Facade de Mail

/*
|--------------------------------------------------------------------------
| Register Middleware
|--------------------------------------------------------------------------
|
| Here we register the middleware with the application. These can
| be global middleware that run before and after each request into a
| route or middleware that'll be assigned to some specific routes.
|
*/

// Define JWT middleware
$app->routeMiddleware([
    'auth' => App\Http\Middleware\Authenticate::class,
    'jwt.auth' => Tymon\JWTAuth\Middleware\GetUserFromToken::class,
    'jwt.refresh' => Tymon\JWTAuth\Middleware\RefreshToken::class,
]);

/*
|--------------------------------------------------------------------------
| Register Service Providers
|--------------------------------------------------------------------------
|
| Here we will register all of the application's service providers which
| are used to bind services into the container. Service providers are
| totally optional, so you are not required to uncomment this line.
|
*/

// Register JWTAuth provider
$app->register(Tymon\JWTAuth\Providers\LumenServiceProvider::class);

// Register the mail service provider
$app->register(Illuminate\Mail\MailServiceProvider::class);

/*
|--------------------------------------------------------------------------
| Enable Facades
|--------------------------------------------------------------------------
|
| Facades allow for a "static" interface to classes that are available
| in the application's service container.
|
*/

/*
|--------------------------------------------------------------------------
| Load The Application Routes
|--------------------------------------------------------------------------
|
| Next we will include the routes file so that they can all be added to
| the application. This will provide all of the URLs the application
| can respond to, as well as the controllers that may handle them.
|
*/

$app->router->group([
    'namespace' => 'App\Http\Controllers',
], function ($router) {
    require __DIR__.'/../routes/web.php';
});

return $app;
