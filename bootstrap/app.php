<?php



use App\Http\Middleware\AdminLogin;

use App\Http\Middleware\StaffLogin;

use App\Http\Middleware\InternLogin;

use App\Http\Middleware\Hrlogin;





use Illuminate\Foundation\Application;

use Illuminate\Foundation\Configuration\Exceptions;

use Illuminate\Foundation\Configuration\Middleware;



return Application::configure(basePath: dirname(__DIR__))

    ->withRouting(

        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',

        commands: __DIR__ . '/../routes/console.php',

        health: '/up',

    )

    ->withMiddleware(function (Middleware $middleware) {

        $middleware->alias([

            'AdminLogin' => AdminLogin::class,

            'StaffLogin' => StaffLogin::class,

            'InternLogin' => InternLogin::class,

            'Hrlogin' => Hrlogin::class,



        ]);

    })

    ->withExceptions(function (Exceptions $exceptions) {

        //

    })->create();

