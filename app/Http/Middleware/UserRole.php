<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param string $role
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, string $role)
    {
        if ($request->hasHeader('Authorization') == true) {
            $userRoles = DB::table('user_roles')->where('user_token', '=', $request->header("Authorization"))->get(['role_id']);
            foreach ($userRoles as $value) {
                if ($value != null) {
                    $roleName = DB::table('roles')->where('id', '=', $value->role_id)->get(['name'])->value('name');
                    if ($roleName == $role) {
                        return $next($request);
                    }
                }
            }
        }
        return response(status:401);
    }
}
