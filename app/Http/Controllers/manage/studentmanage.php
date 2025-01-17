<?php

namespace App\Http\Controllers\manage;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;


class studentmanage extends Controller
{
    public function add_student_page()
    {
        return response(view('user-manage.add_student'))
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
    }

    public function all_student_page()
    {
        return response(view('user-manage.all_student_list'))
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
    }
    public function store_student(Request $request)
    {
        $rules = [
            'studentName' => ['required', 'string'],
            'universityName' => ['required', 'string'],
            'studentEmail' => ['required', 'email', 'unique:students,email'],
            'studentPhoneno' => ['required', 'string', 'unique:students,phone'],
            'inlineRadioOptions' => ['required', 'in:Male,Female,Other']
        ];
        $messages = [
            'studentName.required' => '❗Please enter the student name.',
            'studentName.string' => '❗The student name must be a valid string.',

            'universityName.required' => '❗Please enter the university name.',
            'universityName.string' => '❗The university name must be a valid string.',

            'studentEmail.required' => '❗Please enter an email address.',
            'studentEmail.email' => '❗Invalid email format.',
            'studentEmail.unique' => '❗This email is already registered.',

            'studentPhoneno.required' => '❗Please enter a phone number.',
            'studentPhoneno.string' => '❗The phone number must be a valid string.',
            'studentPhoneno.unique' => '❗This phone number is already registered.',

            'inlineRadioOptions.required' => '❗Please select a gender.',
            'inlineRadioOptions.in' => '❗Invalid gender selection.'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()->messages()
            ], 422);
        }

        DB::table('students')->insert([
            'name' => $request->input('studentName'),
            'university' => $request->input('universityName'),
            'email' => $request->input('studentEmail'),
            'phone' => $request->input('studentPhoneno'),
            'gender' => $request->input('inlineRadioOptions'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Student registered successfully!'
        ], 200);
    }
    public function getStudents()
    {
        $students = DB::table('students')->paginate(20);
        return response()->json($students);
    }
}
