<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class checkRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = $request->user();
    
        if (in_array('admin', $roles) && $user->role === 'admin') {
            return $next($request);
        }
    
        if (!$user || !in_array($user->role, $roles)) {
            abort(403, 'Unauthorized');
        }
    
        if ($user->role === 'moderator' && !$user->approved) {
            abort(403, 'Account pending approval.');
        }
    
        return $next($request);
    }
    
}
