<?php

use App\Http\Controllers\ClasseController;
use App\Http\Controllers\ClassTypeController;
use App\Http\Controllers\ClassTypeCourseController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\ExamRecordController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StudentParentController;
use App\Http\Controllers\TeacherController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
            $user->load('course'); // Eager load course for teachers
        }

        if ($user->role === 'student') {
            $user->load('classe'); // Eager load course for teachers
        }
        if ($user->role === 'parent') {
            $user->load('students'); // Eager load course for teachers
        }
      return $user;
    });
  });

Route::middleware(['auth:sanctum','ability:student'])->prefix('student')->group( static function(){


    Route::apiResource('students' , StudentController::class)->only(['index', 'show','update']);
    Route::apiResource('Exams', ExamController::class)->only(['index', 'show']);
    Route::apiResource('Records', ExamRecordController::class)->only(['index', 'show']);
    Route::post('/update-password', [StudentController::class, 'updatePassword']);

});


Route::middleware(['auth:sanctum','ability:parent'])->prefix('parent')->group( static function(){

    Route::apiResource( 'parents' , StudentParentController::class)->only(['index', 'show','update']);
    Route::apiResource('students' , StudentController::class)->only(['index', 'show'])->names([
        'index' => 'parent.students.index',   // Custom name for the index route
        'show' => 'parent.students.show',     // Custom name for the show route
    ]);
    Route::apiResource('Exams', ExamController::class)->only(['index', 'show'])->names([
        'index' => 'parent.Exams.index',  // Custom name for the index route
        'show' => 'parent.Exams.show',    // Custom name for the show route
    ]);;
    Route::apiResource('Records', ExamRecordController::class)->only(['index', 'show'])->names([
        'index' => 'teacher.Records.index',  // Custom name for the index route
        'show' => 'teacher.Records.show',    // Custom name for the show route
    ]);;
    Route::post('/update-password', [StudentParentController::class, 'updatePassword']);
});

// For Admin routes
Route::middleware(['auth:sanctum','ability:admin'])->prefix('admin')->group( static function(){

    Route::apiResources([
        'parents'=>StudentParentController::class ,
        'students' => StudentController::class,
        'teachers' => TeacherController::class,
        'classes' => ClasseController::class,
        'cours' => CourseController::class,
        'classeTypes' => ClassTypeController::class,
        'classeCoursTypes' => ClassTypeCourseController::class,
    ], ['names' => [
        'students.index' => 'admin.students.index', // Custom name for the index route
        'students.show' => 'admin.students.show',
        'parents.index' => 'admin.parents.index', // Custom name for the index route
        'parents.show' => 'admin.parents.show',
        'parents.update' => 'admin.parents.update',
    ]]);
});

Route::middleware(['auth:sanctum','ability:teacher'])->prefix('teacher')->group( static function(){
    Route::apiResources([
    'students' => StudentController::class,
    'classes' => ClasseController::class,
    'classeTypes' => ClassTypeController::class,
    'classeCoursTypes' => ClassTypeCourseController::class,
    'cours' => CourseController::class,

    'Records' => ExamRecordController::class,
    ]) ;
    Route::post('/update-password', [TeacherController::class, 'updatePassword']);
    Route::apiResource('Exams', ExamController::class)->names([
        'index' => 'teacher.Exams.index',  // Custom name for the index route
        'show' => 'teacher.Exams.show',    // Custom name for the show route
    ]);
});

// require __DIR__.'/auth.php';
