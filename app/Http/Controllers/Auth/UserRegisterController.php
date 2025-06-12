<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserRegisterController extends Controller
{
    public function user_register()
    {
        return view('auth.user_register');
    }
    public function user_register_store(Request $request) {
        if (empty($request->name) || empty($request->store_name)|| empty($request->average_orders)|| empty($request->whatsapp)|| empty($request->mobile)|| empty($request->dropshipping_experience)|| empty($request->dropshipping_status)|| empty($request->bank)|| empty($request->ac_title)|| empty($request->ac_no)|| empty($request->iban)) {
            return response()->json(2);
        }
        if (empty($request->password)) {
            return response()->json(3);
        }
        if (empty($request->password_confirmation)) {
            return response()->json(4);
        }
        if ($request->password_confirmation != $request->password) {
            return response()->json(5);
        }
        $uppercase = preg_match('@[A-Z]@', $request->password);
        $lowercase = preg_match('@[a-z]@', $request->password);
        $number = preg_match('@[0-9]@', $request->password);

        if (!$uppercase || !$lowercase || !$number || strlen($request->password) < 8) {
            return response()->json(6);
        }
        if (User::where('email', $request->email)->exists()) {
            return response()->json(7);
        }
      $user =  User::create([
            'name' => $request->name,
            'email' => $request->email,
            'store_name' => $request->store_name,
            'average_orders' => $request->average_orders,
            'whatsapp' => $request->whatsapp,
            'mobile' => $request->mobile,
            'dropshipping_experience' => $request->dropshipping_experience,
            'dropshipping_status' => $request->dropshipping_status,
            'bank' => $request->bank,
            'ac_title' => $request->ac_title,
            'ac_no' => $request->ac_no,
            'iban' => $request->iban,
            'role' => 'seller',
              'type' => 'seller',
            'password' => Hash::make($request->password),
        ]);
        if (!empty($user)){
            return response()->json(1);
        }else{
            return response()->json(0);
        }
    }
}
