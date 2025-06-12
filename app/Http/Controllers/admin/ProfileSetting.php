<?php

namespace App\Http\Controllers\admin;

use App\Helpers\ActivityLogger;
use App\Http\Controllers\Controller;
use App\Models\Renter;
use App\Models\Reservation;
use App\Models\User;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Traits\imageUploadTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class ProfileSetting extends Controller
{
    use imageUploadTrait;
    public function profile(){
        if (ActivityLogger::hasPermission('settings', 'profile')) {
            ActivityLogger::UserLog('open profile ' . Auth::user()->name);
            return view('admin.pages.profile.profile');
        }else{
            abort(403, 'Unauthorized action.');
        }
    }
    public function get_profile(Request $request){
        $id=$request->id;
        $Data=User::find($id);
        return response()->json($Data);
    }
    public function profile_update(Request $request)
    {
        if (ActivityLogger::hasPermission('settings', 'profile')) {
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
    }
    public function security(){
        if (ActivityLogger::hasPermission('settings', 'profile')) {
        ActivityLogger::UserLog('Open profile Security '.Auth::user()->name);
        return view('admin.pages.profile.security');
    }
    }
    public function security_update(Request $request)
    {
        if (ActivityLogger::hasPermission('settings', 'profile')) {
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
    public function get_smtp(){
        if (ActivityLogger::hasPermission('settings', 'smtp')) {
        $smtpSettings = [
            'MAIL_HOST' => env('MAIL_HOST'),
            'MAIL_PORT' => env('MAIL_PORT'),
            'MAIL_USERNAME' => env('MAIL_USERNAME'),
            'MAIL_PASSWORD' => env('MAIL_PASSWORD'),
            'MAIL_ENCRYPTION' => env('MAIL_ENCRYPTION'),
            'MAIL_FROM_ADDRESS' => env('MAIL_FROM_ADDRESS'),
        ];
        return response()->json($smtpSettings);
    }
    }

    public function smtp(){
        if (ActivityLogger::hasPermission('settings', 'smtp')) {
        ActivityLogger::UserLog('Open SMTP '.Auth::user()->name);
        return view('admin.pages.profile.smtp');
    }else{
            abort(403, 'Unauthorized action.');
        }
    }
    public function smtp_update(Request $request)
    {
        if (ActivityLogger::hasPermission('settings', 'smtp')) {
        $user = Auth::user();
        $envValues = [
            'MAIL_HOST' => $request->input('mail_host'),
            'MAIL_ENCRYPTION' => $request->input('mail_enc'),
            'MAIL_USERNAME' => $request->input('mail_username'),
            'MAIL_PASSWORD' => $request->input('mail_password'),
            'MAIL_PORT' => $request->input('mail_port'),
            'MAIL_FROM_ADDRESS' => $request->input('admin_email'),
        ];
        try {
            $this->updateEnvFile($envValues);
            ActivityLogger::UserLog('Update SMTP '.Auth::user()->name);
            return response()->json(1);
        } catch (\Exception $e) {
            Log::error('Failed to update .env file: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update Payment settings.');
        }
    }
    }
    protected function updateEnvFile(array $values)
    {
        $envFilePath = base_path('.env');
        $content = file_get_contents($envFilePath);

        foreach ($values as $envKey => $envValue) {
            $pattern = "/^{$envKey}=.*/m";
            $replacement = "{$envKey}=\"{$envValue}\"";
            $content = preg_replace($pattern, $replacement, $content);
        }

        if (false === file_put_contents($envFilePath, $content)) {
            throw new \Exception('Unable to write to .env file.');
        }
    }
}
