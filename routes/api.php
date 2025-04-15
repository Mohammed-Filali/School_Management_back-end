<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\AdmineController;
use App\Http\Controllers\ClasseController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\ClassTypeController;
use App\Http\Controllers\AttendanceController;

use App\Http\Controllers\ExamRecordController;
use App\Http\Controllers\TotalRecordController;
use App\Http\Controllers\StudentParentController;
use App\Http\Controllers\ClassTypeCourseController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::middleware(['auth:sanctum'])->group(static function () {
    Route::get('/me', function (Request $request) {
        $user = $request->user();

        // Include related data based on the user role
        if ($user->role === 'teacher') {
            $user->load(['course', 'classes.classType.classe.students.attendance', 'exams.records.student']);
             // Eager load classes and their related course for teachers
        }

        if ($user->role === 'student') {
            $user->load('classe', 'attendance','moyennes.course','records.exams.course');
            $user->absent_count = \App\Models\Attendance::where('user_id', $user->id)
                ->where('status', 'absent')
                ->count();
            $user->present_count = \App\Models\Attendance::where('user_id', $user->id)
                ->where('status', 'present')
                ->count();
            $user->late_count = \App\Models\Attendance::where('user_id', $user->id)
                ->where('status', 'late')
                ->count();
            return $user;
        }

        if ($user->role === 'parent') {
            $user->load('students.moyennes.course', 'students.records.exams.course', 'students.attendance'); // Eager load students for parents
        }

        // Add counts only for admin
        if ($user->role === 'admin') {
            $user->students_count = \App\Models\User::count();
            $user->teachers_count = \App\Models\Teacher::count();
            $user->parents_count = \App\Models\StudentParent::count();
            $user->classes_count = \App\Models\Classe::count();
            $user->attendances_count = \App\Models\Attendance::count();
        }

        return $user;
    });

  });

Route::middleware(['auth:sanctum','ability:student'])->prefix('student')->group( static function(){


    Route::apiResource('students' , StudentController::class)->only(['index', 'show','update']);
    Route::apiResource('Exams', ExamController::class)->only(['index', 'show']);
    Route::apiResource('Records', ExamRecordController::class)->only(['index', 'show']);
    Route::post('/update-password', [StudentController::class, 'updatePassword']);
    Route::prefix('attendance')->group(function () {
        Route::get('/', [AttendanceController::class, 'index']);
    });

});


Route::middleware(['auth:sanctum','ability:parent'])->prefix('parent')->group( static function(){
    Route::apiResource( 'parents' , StudentParentController::class)->only(['index', 'show','update']);
    Route::apiResource('students' , StudentController::class)->only(['index', 'show']);
    Route::apiResource('Exams', ExamController::class)->only(['index', 'show']);
    Route::apiResource('Records', ExamRecordController::class)->only(['index', 'show']);
    Route::post('/update-password', [StudentParentController::class, 'updatePassword']);
    Route::prefix('attendance')->group(function () {
        Route::get('/', [AttendanceController::class, 'index']);
    });

});

Route::apiResource('classeTypes', ClassTypeController::class)->only(['index', 'show']);
Route::apiResource('cours', CourseController::class)->only(['index', 'show']);

// routes/api.php
Route::prefix('attendance')->group(function () {
    Route::get('/', [AttendanceController::class, 'index']);
    Route::post('/', [AttendanceController::class, 'store']);
    Route::post('/bulk', [AttendanceController::class, 'bulkStore']);
    Route::get('/stats', [AttendanceController::class, 'stats']);

});

Route::middleware(['auth:sanctum','ability:admin'])->prefix('admin')->group( static function(){

    Route::apiResources([
        'parents'=>StudentParentController::class ,
        'students' => StudentController::class,
        'teachers' => TeacherController::class,
        'classes' => ClasseController::class,
        'cours' => CourseController::class,
        'classeCoursTypes' => ClassTypeCourseController::class,
        ]) ;
        Route::post('/update-password', [AdmineController::class, 'updatePassword']);

        Route::apiResource('classeTypes', ClassTypeController::class)->except(['update']);

        Route::post('classeTypes/{id}', [ClassTypeController::class, 'update']);
    });


Route::middleware(['auth:sanctum','ability:teacher'])->prefix('teacher')->group( static function(){
    Route::apiResources([
    'students' => StudentController::class,
    'classes' => ClasseController::class,
    'classeTypes' => ClassTypeController::class,
    'classeCoursTypes' => ClassTypeCourseController::class,
    'cours' => CourseController::class,
    'Exams' => ExamController::class,
    'Records' => ExamRecordController::class,
    'totalRecords' => TotalRecordController::class,
    ]) ;
    Route::post('/update-password', [TeacherController::class, 'updatePassword']);

});
Route::prefix('attendance')->group(function () {
    Route::get('/', [AttendanceController::class, 'index']);
    Route::post('/', [AttendanceController::class, 'store']);
    Route::post('/bulk', [AttendanceController::class, 'bulkStore']);
    Route::get('/stats', [AttendanceController::class, 'stats']);

});

    Route::apiResource('tasks', TaskController::class);


// require __DIR__.'/auth.php';
