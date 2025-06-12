<?php

namespace App\Helpers;

use App\Models\UserLogs;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;

class ActivityLogger
{
    public static function UserLog($activity)
    {

        $user = Auth::user();
        $ip = Request::ip();

        UserLogs::create([
            'user_id' => $user ? $user->id : null,
            'activity' => $activity,
            'ip_address' => $ip,
        ]);
        return true;
    }
    public static function hasPermission($module, $action) {
        $user = Auth::user();

        // Super admin bypass
        if ($user->type === 'admin') {
            return true;
        }

        $permissions = DB::table('permissions')
            ->where('role_id', $user->type)
            ->value($module);

        return $permissions && in_array($action, explode(',', $permissions));
    }
    public static function hasSellerPermission($module, $action) {
        $user = Auth::user();

        // Super admin bypass
        if ($user->type === 'seller') {
            return true;
        }
        if ($user->role === 'sub_seller') {
        $permissions = DB::table('seller_permissions')
            ->where('role_id', $user->type)
            ->value($module);
        return $permissions && in_array($action, explode(',', $permissions));
    }
    }
}
