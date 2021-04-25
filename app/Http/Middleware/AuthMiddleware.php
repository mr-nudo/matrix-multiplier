<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;

class AuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!($request->hasHeader('php-auth-user')) && $request->hasHeader('php-auth-pw')) {
            return response()->json(['status' => 'error', 'message' => 'Authorization required'], 401);
        }

        $username = $request->header('php-auth-user');
        $password = $request->header('php-auth-pw');

        $auth_user = User::where('username', $username)->first();

        if ($auth_user && ($auth_user->password === $password)) {
            $request->merge(['auth_user' => $auth_user]);
            return $next($request);
        }

        return response()->json(['status' => 'error', 'message' => 'Invalid credentials'], 401);
    }
}
