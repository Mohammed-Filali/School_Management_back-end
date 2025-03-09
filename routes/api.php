<?php

use App\Http\Controllers\AdmineController;
use App\Http\Controllers\TaskController;
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
    Route::apiResource('students' , StudentController::class)->only(['index', 'show']);
    Route::apiResource('Exams', ExamController::class)->only(['index', 'show']);
    Route::apiResource('Records', ExamRecordController::class)->only(['index', 'show']);
    Route::post('/update-password', [StudentParentController::class, 'updatePassword']);

});

Route::apiResource('classeTypes', ClassTypeController::class)->only(['index', 'show']);
Route::apiResource('cours', CourseController::class)->only(['index', 'show']);



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
    ]) ;
    Route::post('/update-password', [TeacherController::class, 'updatePassword']);

});

    Route::apiResource('tasks', TaskController::class);


// require __DIR__.'/auth.php';
