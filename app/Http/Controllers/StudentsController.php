<?php

namespace App\Http\Controllers;

use App\Models\EStudents;
use App\Models\HomeWork;
use App\Models\students;
use App\Models\activity;
use App\Models\tasks;
use App\Models\school;
use App\Models\classes;
use App\Models\Attendance;


use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use Redirect;

class StudentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $schoolId = session('user')['school_id'];

        $students = students::where('school', $schoolId)
            ->select('students.*', 'school.school_name')
            ->paginate(50);

        $classes = classes::where('school_id', $schoolId)->get();

        return view('admin.students')
            ->with('students', $students)
            ->with('classes', $classes);

    }
    public function SuperAdminViewStudents(Request $request)
    {
        $rqMethod = $request->method();

        if ($rqMethod === 'POST') {
            $students = students::join('school', 'school.id', 'students.school')
                ->select('students.*', 'school.school_name')
                ->get();
            return response()->json([
                'students' => $students
            ]);
        }

        return view('dashboard.superadmin.students.view')
        ;
    }
    public function SuperAdminCreateStudents(Request $request)
    {

        if ($request->father_name && $request->name && $request->dob && $request->email && $request->password && $request->school) {

            $student = new students();
            $student->name = $request->name;
            $student->father_name = $request->father_name;
            $student->admission_no = $request->admission_no;
            $student->group = $request->group;
            $student->email = $request->email;
            $student->password = bcrypt($request->password);
            $student->dob = $request->dob;
            $student->school = $request->school;
            $student->class = $request->class;
            $student->section = $request->section;
            $student->gender = $request->gender;
            $student->contact = $request->contact;

            $student->save();

            return redirect()->route('dashboard.superadmin.students.view');
        } else {
            $schools = school::all();
            return view('dashboard.superadmin.students.create')
                ->with('schools', $schools)
            ;
        }
    }
    public function SuperAdminEditStudent(Request $request)
    {
        $requestMethod = $request->method();

        if ($requestMethod === 'PUT') {
            $student = students::find($request->id);
            $student->name = $request->name;
            $student->father_name = $request->father_name;
            $student->admission_no = $request->admission_no;
            $student->group = $request->group;
            if ($request->email) {
                $student->email = $request->email;
            }
            if ($student->password) {
                $student->password = bcrypt($request->password);
            }
            if ($request->dob) {
                $student->dob = $request->dob;
            }
            $student->school = $request->school;
            if ($request->class) {
                $student->class = $request->class;
            }
            if ($request->section) {
                $student->section = $request->section;
            }
            if ($request->gender) {
                $student->gender = $request->gender;
            }
            if ($request->contact) {
                $student->contact = $request->contact;
            }

            $student->save();
            return redirect()->route('superadmin.students.view');
        } else if ($requestMethod === 'GET') {
            $schools = school::all();
            $student = students::find($request->id);
            return view('dashboard.superadmin.students.edit')
                ->with('schools', $schools)
                ->with('student', $student)
            ;
        } else {
            return null;
        }
    }
    public function SuperAdminDeleteStudent(Request $request)
    {
        if ($request->id) {
            $deletedRows = students::destroy($request->id);
            if ($deletedRows > 0) {
                return response()->json([
                    'status' => 200,
                    'deleted' => true
                ]);
            } else {
                return response()->json([
                    'status' => 200,
                    'deleted' => false,
                    'message' => 'Failed To Delete Record'
                ]);
            }
        } else {
            return response()->json([
                'status' => 200,
                'deleted' => false,
                'message' => 'Record Id is not Provided',
                'form' => $request->id
            ]);
        }
    }
    public function filter(Request $request)
    {

        $class_name = null;
        if ($request->has('class_name') && $request->input('class_name') !== null) {
            $class_name = $request->input('class_name');
            session(['filter.student.class_name' => $request->input('class_name')]);
        } else {
            $class_name = session('filter.student.class_name') ?? null;
        }

        $schoolId = session('user')['school_id'];


        $students = students::where('school', $schoolId)
            ->where('class', $class_name)
            ->paginate(50);

        $classes = classes::where('school_id', $schoolId)->get();

        return view('admin.students')
            ->with('students', $students)
            ->with('classes', $classes);
    }



    // ==================== SCHOOL ADMIN

    public function SchoolAdminViewStudents(Request $request)
    {

        $schoolId = HelperFunctionsController::getUserSchoolsIds();
        $rqMethod = $request->method();

        if ($rqMethod === 'POST') {
            $students = students::whereIn('school', $schoolId)
                ->join('school', 'school.id', 'students.school')
                ->select('students.*', 'school.school_name')
                ->get();
            return response()->json([
                'students' => $students
            ]);
        }

        return view('dashboard.admin.students.view')
        ;
    }
    public function SchoolAdminDeleteStudent(Request $request)
    {
        if ($request->id) {
            $deletedRows = students::destroy($request->id);
            if ($deletedRows > 0) {
                return response()->json([
                    'status' => 200,
                    'deleted' => true
                ]);
            } else {
                return response()->json([
                    'status' => 200,
                    'deleted' => false,
                    'message' => 'Failed To Delete Record'
                ]);
            }
        } else {
            return response()->json([
                'status' => 200,
                'deleted' => false,
                'message' => 'Record Id is not Provided',
                'form' => $request->id
            ]);
        }
    }
    public function SchoolAdminCreateStudent(Request $request)
    {
        if ($request->father_name && $request->name && $request->dob && $request->email && $request->password) {

            $student = new students();
            $student->name = $request->name;
            $student->father_name = $request->father_name;
            $student->admission_no = $request->admission_no;
            $student->group = $request->group;
            $student->email = $request->email;
            $student->password = bcrypt($request->password);
            $student->dob = $request->dob;
            $student->school = $request->school_id;
            $student->class = $request->class;
            $student->section = $request->section;
            $student->gender = $request->gender;
            $student->contact = $request->contact;
            $student->save();

            return redirect()->route('schooladmin.students.view');
        } else {
            $schools = HelperFunctionsController::getUserSchools();
            return view('dashboard.admin.students.create')
                ->with('schools', $schools)
            ;
        }
    }
    public function SchoolAdminEditStudent(Request $request)
    {
        $requestMethod = $request->method();

        if ($requestMethod === 'PUT') {
            $student = students::find($request->id);
            $student->name = $request->name;
            $student->father_name = $request->father_name;
            $student->admission_no = $request->admission_no;
            $student->group = $request->group;
            if ($request->email) {
                $student->email = $request->email;
            }
            if ($student->password) {
                $student->password = bcrypt($request->password);
            }
            if ($request->dob) {
                $student->dob = $request->dob;
            }
            if ($request->class) {
                $student->class = $request->class;
            }
            if ($request->section) {
                $student->section = $request->section;
            }
            if ($request->gender) {
                $student->gender = $request->gender;
            }
            if ($request->contact) {
                $student->contact = $request->contact;
            }

            $student->save();
            return redirect()->route('schooladmin.students.view');
        } else if ($requestMethod === 'GET') {
            $schools = school::all();
            $student = students::find($request->id);
            return view('dashboard.admin.students.edit')
                ->with('schools', $schools)
                ->with('student', $student)
            ;
        } else {
            return null;
        }
    }
    // ==================== SCHOOL ADMIN



    public function login(Request $request)
    {
        $user = students::where('email', $request->input('email'))->first();
        if (!$user || !\Hash::check($request->password, $user->password)) {
            return response()->json([
                'email' => 'The provided credentials do not match our records.',
            ], 200);
        }
        $payload = [
            'id' => $user->id,
            'email' => $user->email,
        ];

        $jwt = JWT::encode($payload, 'tecai', 'HS256');

        return response()->json([
            'success' => 'You have successfully logged in.',
            'user' => $user,
            'token' => $jwt,
        ], 200);
    }
    public function getStudent(Request $request)
    {
        $student = students::where('id', $request->id)->first();
        return response()->json([
            'success' => 'You have successfully logged in.',
            'user' => $student,
        ], 200);
    }
    public function updateStudentToken(Request $request)
    {
        try {
            $student = students::where('id', $request->id)->first();
            $student->token = $request->token;
            $student->save();

            return response()->json([
                'success' => 'student token updated',
                'status' => true,
                'user' => $student,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => $th->getMessage(),
                'status' => false,
            ], 200);
        }
    }

    public function getAssignments(Request $request)
    {
        $student = students::where('students.id', $request->id)
            ->join('school', 'students.school', '=', 'school.id')
            ->first();
        $studentId = $request->id;
        $class = classes::where('class_name', $student->class)->first();
        $query = Activity::where('class_id', $class->id)
            ->join('course', 'activity.course_id', '=', 'course.id')
            ->join('teachers', 'activity.tid', '=', 'teachers.id')
            ->select(
                'activity.id',
                'activity.added_on',
                'activity.title',
                'activity.type',
                'activity.updated_at',
                'activity.created_at',
                'activity.deadline',
                'activity.total_marks',
                'teachers.name as teacher_name'
            )
            ->whereNotIn('activity.id', function ($subquery) use ($studentId) {
                $subquery->select('activity_id')
                    ->from('tasks')
                    ->where('std_id', $studentId);
            });

        $activity = $query->get();

        return response()->json([
            'success' => true,
            'assignments' => $activity,
            'class' => $class,
            'class_id' => $class->id,
            'class_name' => $class->class_name,
        ]);
    }
    public function getAssignment(Request $request, $id)
    {
        $currentUrl = url('/') . '/excercise/assets/php/create.php?id=' . $id;

        if ($request->has('teacher')) {
            $currentUrl = url('/') . '/excercise/assets/php/create.php?id=' . $id . '&teacher=true';
        }
        if ($request->has('token')) {
            $currentUrl = url('/') . '/excercise/assets/php/create.php?id=' . $id . '&token=' . $request->token;
        }


        $activity = activity::where('id', $id)->first();

        // Path to the public directory
        $publicPath = public_path('excercise/assets/php/');

        // Path to the JSON file within the public directory
        $filePath = $publicPath . '/data.json';

        // Write the JSON data to the file
        file_put_contents($filePath, json_encode($activity));

        return redirect($currentUrl);

    }

    public function studentGradeAssignment(Request $request, $id)
    {

        if (!$request->has('points_obtained')) {
            return response()->json([
                'success' => false,
                'message' => 'Please Provide points_obtained field'
            ]);
        }
        if (!$request->has('points_total')) {
            return response()->json([
                'success' => false,
                'message' => 'Please Provide points_total field'
            ]);
        }

        $std = HelperFunctionsController::getCurrentStudent($request->id);



        $tasks = new tasks();
        $tasks->std_id = $request->id;
        $tasks->activity_id = $id;
        $tasks->points_obtained = $request->points_obtained;
        $tasks->points_total = $request->points_total;
        $tasks->save();


        if ($std->token) {
            HelperFunctionsController::sendNotification($std->token, 'You have been Graded on The Assignment', $std->name . ' Your Score is saved.');
        }

        return response()->json([
            'success' => true,
            'task' => $tasks
        ]);
    }

    public function getAttendance(Request $request)
    {
        $date = now();

        $attendance = Attendance::where('student_id', $request->id)
            ->whereDate('date', '=', $date)
            ->join('students', 'attendance.student_id', '=', 'students.id')
            ->select('attendance.*', 'students.name')
            ->get();

        return response()->json([
            'status' => true,
            'attendance' => $attendance,
        ], 200);
    }
    public function getAttendanceByDate(Request $request)
    {
        $date = now();
        if ($request->has('date')) {
            $date = $request->date;
        }

        $attendance = Attendance::where('student_id', $request->id)
            ->whereDate('date', '=', $date)
            ->join('students', 'attendance.student_id', '=', 'students.id')
            ->select('attendance.*', 'students.name')
            ->get();

        return response()->json([
            'status' => true,
            'attendance' => $attendance,
        ], 200);
    }
    public function getAttendanceMonth(Request $request)
    {
        $startDate = now()->startOfMonth();
        $endDate = now()->endOfMonth();

        $attendance = Attendance::where('student_id', $request->id)
            ->whereBetween('date', [$startDate, $endDate])
            ->join('students', 'attendance.student_id', '=', 'students.id')
            ->select('attendance.*', 'students.name')
            ->get();

        return response()->json([
            'status' => true,
            'attendance' => $attendance,
        ], 200);
    }
    public function getHomeWorks(Request $request)
    {
        $date = now();

        $student = students::where('students.id', $request->id)
            ->join('school', 'students.school', '=', 'school.id')
            ->first();
        $class = classes::where('class_name', $student->class)->first();

        $homeworks = HomeWork::where('school_id', '=', $student->school)
            ->where('class_id', '=', $class->id)
            ->get()
        ;

        return response()->json([
            'success' => true,
            'homeworks' => $homeworks,
            'class' => $class,
            'student' => $student,
            'class_id' => $class->id,
            'class_name' => $class->class_name,
        ]);
    }
    public function getStudentMarks(Request $request)
    {
        $studentId = $request->id;
        $marks = tasks::where('std_id', $studentId)
            ->join('activity', 'activity.id', '=', 'tasks.activity_id')
            ->join('classes', 'classes.id', '=', 'activity.class_id')
            ->join('course', 'course.id', '=', 'activity.course_id')
            ->select(
                'tasks.id',
                'tasks.points_obtained as obtained',
                'tasks.points_total as total',
                'activity.title',
                'tasks.added_on as attempted_date',
                'classes.class_name',
                'course.course_name'
            )
            ->get();
        return response()->json([
            'marks' => $marks,
            'status' => true
        ]);
    }
}
