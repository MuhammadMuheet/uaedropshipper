<?php

namespace App\Http\Controllers\logistic_company;

use App\Helpers\ActivityLogger;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\imageUploadTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class ProfileSetting extends Controller
{
    use imageUploadTrait;
    public function profile(){
            ActivityLogger::UserLog('open profile ' . Auth::user()->name);
            return view('logistic_company.pages.profile.profile');
    }
    public function get_profile(Request $request){
        $id=$request->id;
        $Data=User::find($id);
        return response()->json($Data);
    }
    public function profile_update(Request $request)
    {

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
                ActivityLogger::UserLog('Update profile '.Auth::user()->name);
                return response()->json(1);
            } catch (\Exception $e) {
                \Log::error('Profile Update Error: '.$e->getMessage());
                return response()->json(0);
            }

    }
    public function security(){
            ActivityLogger::UserLog('Open profile Security '.Auth::user()->name);
            return view('logistic_company.pages.profile.security');
    }
    public function security_update(Request $request)
    {

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
                ActivityLogger::UserLog('Update profile Security '.Auth::user()->name);
                return response()->json(1);
            } catch (\Exception $e) {
                Log::error('Profile Update Error: ' . $e->getMessage());
                return response()->json(0);
            }
    }
}
