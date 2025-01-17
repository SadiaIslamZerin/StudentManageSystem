<?php

namespace App\Http\Controllers\authentications;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Config;


class LoginBasic extends Controller
{
  public function index()
  {
    return response(view('content.authentications.auth-login-basic'))
      ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
  }
  public function loginValidation(Request $request)
  {
    // Validation rules
    $rules = [
      'email-username' => ['required'],
      'password' => ['required', 'regex:/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{5,12}$/']
    ];

    $messages = [
      'email-username.required' => '❗Please enter email or phone no.',
      'password.required' => '❗Please enter password.',
      'password.regex' => '❗Invalid password.'
    ];

    $validator = Validator::make($request->all(), $rules, $messages);

    if ($validator->fails()) {
      return response()->json([
        'status' => 'error',
        'message' => 'Validation failed',
        'errors' => $validator->errors()->messages()
      ], 422);
    }

    $user = DB::table('user_auth')
      ->where('email_or_phone', $request->input('email-username'))
      ->first();

    if (!$user) {
      return response()->json([
        'status' => 'error',
        'message' => 'Invalid credentials. User not found.'
      ], 401);
    }

    // Verify password using Hash::check
    if (!Hash::check($request->password, $user->password)) {
      return response()->json([
        'status' => 'error',
        'message' => 'Invalid credentials. Incorrect password.'
      ], 401);
    }

    Config::set('session.lifetime', 60);

    Session::put('user_logged_in', true);
    Session::put('user_id', $user->id);
    Session::put('userName', $user->name);
    Session::put('userRole', $user->user_role);



    // Successful authentication
    return response()->json([
      'status' => 'success',
      'message' => 'Login successful!',
      'user' => [
        'name' => $user->name,
        'email_or_phone' => $user->email_or_phone,
        'role' => $user->user_role
      ]
    ], 200);
  }
  public function logout()
  {
    // Clear the session
    Session::flush();

    // Redirect to login page
    return redirect('/login')->with('message', 'You have been logged out successfully.');
  }
}
