<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckSellerModuleAndPermission
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
        if ($user->type === 'seller') {
            return $next($request);
        }
        if ($user->role === 'sub_seller') {
        $permissions = \DB::table('seller_permissions')
            ->where('role_id', $user->type)
            ->value($module);
        if ($permissions && in_array($action, explode(',', $permissions))) {
            return $next($request);
        }
        }else{
            abort(403, 'Unauthorized action.');
        }
        abort(403, 'Unauthorized action.');
    }
}
