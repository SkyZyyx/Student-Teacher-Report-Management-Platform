<?php
// app/Http/Middleware/RoleMiddleware.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Gate;

class RoleMiddleware
{
    public function handle($request, Closure $next, $role)
    {
        if (Gate::denies($role)) {
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}
