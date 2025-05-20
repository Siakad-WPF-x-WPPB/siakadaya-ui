<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\LocaleMiddleware;
use App\Http\Middleware\RedirectIfAuthenticated; // <-- TAMBAHKAN IMPORT INI

return Application::configure(basePath: dirname(__DIR__))
  ->withRouting(
    web: __DIR__ . '/../routes/web.php',
    commands: __DIR__ . '/../routes/console.php',
    api: __DIR__ . '/../routes/api.php',
    apiPrefix: '/api', // Pastikan ini sesuai jika Anda menggunakan API
    health: '/up',
  )
  ->withMiddleware(function (Middleware $middleware) {
    // Middleware yang ditambahkan ke grup 'web'
    $middleware->web(LocaleMiddleware::class);

    // Override alias middleware
    $middleware->alias([
      'guest' => RedirectIfAuthenticated::class, // <-- OVERRIDE ALIAS 'guest'
      // Anda bisa menambahkan atau meng-override alias lain di sini jika perlu
      'auth' => \App\Http\Middleware\Authenticate::class, // Contoh jika Anda juga meng-override 'auth'
    ]);

    // Jika Anda perlu menambahkan middleware global:
    // $middleware->append(SomeGlobalMiddleware::class);
    // $middleware->prepend(AnotherGlobalMiddleware::class);

  })
  ->withExceptions(function (Exceptions $exceptions) {
    //
  })->create();
