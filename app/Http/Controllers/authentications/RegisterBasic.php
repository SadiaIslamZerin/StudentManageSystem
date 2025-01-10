<?php

namespace App\Http\Controllers\authentications;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;


class RegisterBasic extends Controller
{
  public function index()
  {
    return view('content.authentications.auth-register-basic');
  }
  public function store(Request $request)
  {
    // Validation rules
    $rules = [
      'username' => ['required', 'regex:/^[a-zA-Z. ]+$/'],
      'email' => ['required'],
      'password' => ['required', 'regex:/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{5,12}$/']
    ];

    $messages = [
      'username.required' => '❗Please enter name.',
      'username.regex' => '❗Invalid name',
      'email.required' => '❗Please enter email or phone no.',
      'password.required' => '❗Please enter password.',
      'password.regex' => '❗Invalid password format.'
    ];

    // Perform validation
    $validator = Validator::make($request->all(), $rules, $messages);

    if ($validator->fails()) {
      return response()->json([
        'status' => 'error',
        'message' => 'Validation failed',
        'errors' => $validator->errors()->messages()
      ], 422);
    }

    // Store in database
    DB::table('user_auth')->insert([
      'name' => $request->username,
      'email_or_phone' => $request->email,
      'password' => bcrypt($request->password),
      'user_role' => 'USER',
      'created_at' => now(),
      'updated_at' => now(),
    ]);

    return response()->json([
      'status' => 'success',
      'message' => 'User registered successfully!'
    ], 200);
  }
}
