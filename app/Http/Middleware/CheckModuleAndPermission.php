<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckModuleAndPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next, $module, $action)
    {
        $user = Auth::user();

        // Super admin bypass
        if ($user->type === 'admin') {
            return $next($request);
        }
        $permissions = \DB::table('permissions')
            ->where('role_id', $user->type)
            ->value($module);
        if ($permissions && in_array($action, explode(',', $permissions))) {
            return $next($request);
        }
        abort(403, 'Unauthorized action.');
    }

}

