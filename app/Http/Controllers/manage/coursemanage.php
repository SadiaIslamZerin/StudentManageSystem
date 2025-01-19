<?php

namespace App\Http\Controllers\manage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class coursemanage extends Controller
{
    public function add_course_page()
    {
        return response(view('course-manage.create_course'))
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
    }
    public function all_course_page()
    {
        return response(view('course-manage.all_course_list'))
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
    }

    public function store_course(Request $request)
    {
        $rules = [
            'courseName' => ['required', 'string', 'unique:courses,name'],
            'courseDuration' => ['required', 'integer', 'min:1'],
            'courseStartDate' => ['required', 'date'],
            'courseEndDate' => ['required', 'date', 'after:courseStartDate'],
            'courseStartTime' => ['required', 'date_format:H:i'],
            'courseEndTime' => ['required', 'date_format:H:i', 'after:courseStartTime'],
            'courseClassDay' => ['required', 'regex:/^(Fri|Sat|Sun|Mon|Tue|Wed|Thu)(, (Fri|Sat|Sun|Mon|Tue|Wed|Thu))*$/'],
            'courseFees' => ['required', 'numeric', 'min:0.01'],
        ];

        $messages = [
            'courseName.required' => '❗Please enter the course name.',
            'courseName.string' => '❗The course name must be a valid string.',
            'courseName.unique' => '❗This course name is already registered.',

            'courseDuration.required' => '❗Please enter the course duration.',
            'courseDuration.integer' => '❗The course duration must be a valid integer.',
            'courseDuration.min' => '❗The course duration must be at least 1 month.',

            'courseStartDate.required' => '❗Please select a start date for the course.',
            'courseStartDate.date' => '❗Invalid start date format.',

            'courseEndDate.required' => '❗Please select an end date for the course.',
            'courseEndDate.date' => '❗Invalid end date format.',
            'courseEndDate.after' => '❗The end date must be after the start date.',

            'courseStartTime.required' => '❗Please specify the start time for the course.',
            'courseStartTime.date_format' => '❗The start time must be in the format HH:mm.',

            'courseEndTime.required' => '❗Please specify the end time for the course.',
            'courseEndTime.date_format' => '❗The end time must be in the format HH:mm.',
            'courseEndTime.after' => '❗The end time must be after the start time.',

            'courseClassDay.required' => '❗Please specify the class days.',
            'courseClassDay.regex' => '❗Invalid class days format. Use: Fri, Sat, Sun, etc.',

            'courseFees.required' => '❗Please specify the course fees.',
            'courseFees.numeric' => '❗The course fees must be a valid number.',
            'courseFees.min' => '❗The course fees must be greater than 0.',
        ];


        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()->messages()
            ], 422);
        }

        DB::table('courses')->insert([
            'name' => $request->input('courseName'),
            'duration' => $request->input('courseDuration'),
            'start_date' => $request->input('courseStartDate'),
            'end_date' => $request->input('courseEndDate'),
            'class_start_hour' => $request->input('courseStartTime'),
            'class_end_hour' => $request->input('courseEndTime'),
            'classdays' => $request->input('courseClassDay'),
            'fees' => $request->input('courseFees'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Return success response
        return response()->json([
            'status' => 'success',
            'message' => 'Course added successfully!'
        ], 200);
    }

    public function getCourses()
    {
        $students = DB::table('courses')->paginate(25);
        return response()->json($students);
    }

    public function deleteCourse($id)
    {
        $course = DB::table('courses')->where('id', $id)->first();

        if (!$course) {
            return response()->json(['message' => 'Course not found'], 404);
        }

        DB::table('courses')->where('id', $id)->delete();
        return response()->json(['message' => 'Course deleted successfully'], 200);
    }
    public function enrollStudentInCourse(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'course_id' => 'required|exists:courses,id',
        ]);

        $exists = DB::table('student_course')
            ->where('student_id', $validated['student_id'])
            ->where('course_id', $validated['course_id'])
            ->exists();

        if ($exists) {
            return response()->json(['error' => 'Student is already enrolled in this course.'], 200);
        }

        DB::table('student_course')->insert([
            'student_id' => $validated['student_id'],
            'course_id' => $validated['course_id'],
            'payment_status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['success' => 'Student enrolled successfully.'], 200);
    }

    public function getStudentsForCourse($courseId)
    {
        $students = DB::table('student_course')
            ->join('students', 'students.id', '=', 'student_course.student_id')
            ->where('student_course.course_id', '=', $courseId)
            ->select('students.id as student_id', 'students.name', 'students.email', 'students.phone', 'students.university', 'student_course.payment_status')
            ->paginate(15);

        return response()->json($students);
    }
}
