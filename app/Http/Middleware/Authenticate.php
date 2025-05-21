<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    protected function redirectTo($request)
    {
        if ($request->expectsJson()) {
            return null;
        }

        if ($request->is('admin/*')) {
            return route('login-admin-view');
        }

        return route('login-dosen-view');
    }
}
