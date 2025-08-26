<?php

namespace App\Http\Controllers\seller;

use App\Helpers\ActivityLogger;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\imageUploadTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    use imageUploadTrait;
    public function profile()
    {
        if (ActivityLogger::hasSellerPermission('settings', 'profile')) {
            ActivityLogger::UserLog('open profile ' . Auth::user()->name);
            return view('seller.pages.profile.profile');
        } else {
            abort(403, 'Unauthorized action.');
        }
    }
    public function get_profile(Request $request)
    {
        if (ActivityLogger::hasPermission('settings', 'profile')) {
            $id = $request->id;
            $Data = User::find($id);
            return response()->json($Data);
        } else {
            abort(403, 'Unauthorized action.');
        }
    }
    public function profile_update(Request $request)
    {
        if (ActivityLogger::hasSellerPermission('settings', 'profile')) {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
            ]);
            if (empty($request->name)) {
                return response()->json(3);
            }
            $user = Auth::user();
            $user_check = User::where('email', $request->email)
                ->where('id', '!=', $user->id)
                ->first();
            if ($user_check) {
                return response()->json(6);
            }
            try {
                $user->name = $request->input('name');
                $user->mobile = $request->input('mobile');
                if ($request->file('avatar')) {
                    if ($user->image && \Storage::exists('public/users/' . $user->image)) {
                        \Storage::delete('public/users/' . $user->image);
                    }
                    $section_one_image = self::uploadFile($request, 'avatar', 'users');
                    $user->image = $section_one_image;
                }
                $user->save();
                ActivityLogger::UserLog('Update profile ' . Auth::user()->name);
                return response()->json(1);
            } catch (\Exception $e) {
                \Log::error('Profile Update Error: ' . $e->getMessage());
                return response()->json(0);
            }
        } else {
            abort(403, 'Unauthorized action.');
        }
    }
    public function security()
    {
        if (ActivityLogger::hasSellerPermission('settings', 'profile')) {
            ActivityLogger::UserLog('Open profile Security ' . Auth::user()->name);
            return view('seller.pages.profile.security');
        } else {
            abort(403, 'Unauthorized action.');
        }
    }





    public function connect_shopify()
    {
        if (ActivityLogger::hasSellerPermission('settings', 'profile')) {
            ActivityLogger::UserLog('Open connect shopify  ' . Auth::user()->name);
            return view('seller.pages.profile.connect_shopify');
        } else {
            abort(403, 'Unauthorized action.');
        }
    }
    public function security_update(Request $request)
    {
        if (ActivityLogger::hasSellerPermission('settings', 'profile')) {
            $user = Auth::user();
            if (!Hash::check($request->currentpassword, $user->password)) {
                return response()->json(3);
            }
            if ($request->newpassword != $request->confirmpassword) {
                return response()->json(4);
            }
            try {
                $user->password = Hash::make($request->newpassword);
                $user->save();
                ActivityLogger::UserLog('Update profile Security ' . Auth::user()->name);
                return response()->json(1);
            } catch (\Exception $e) {
                Log::error('Profile Update Error: ' . $e->getMessage());
                return response()->json(0);
            }
        } else {
            abort(403, 'Unauthorized action.');
        }
    }
}