<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

use App\Http\Middleware\RoleMiddleware;

class RouteServiceProvider extends ServiceProvider
{
    public function boot()
    {
        parent::boot();

       
    }
}
