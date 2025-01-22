<?php

namespace App\Http\Controllers;

use App\Http\Resources\ClassTypeCourseResource;
use App\Models\ClassTypeCourse;
use Exception;
use Illuminate\Http\Request;

class ClassTypeCourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return ClassTypeCourseResource::collection(ClassTypeCourse::with('classeType')->get());

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try{
            $cours=$request->validate([
                'coef'=>'required',
                'course_id'=> 'required',
                'class_type_id'=>'required',
                'teacher_id'=>'required',
                'masseH'=>'required'
            ]);
            $cour=ClassTypeCourse::create($cours);
            return response()->json([
                'data'=>$cour,
                'message'=>'Cour add seccessfuly',
                'status'=>201
            ]);
        }catch(Exception $e){
            return response()->json([
                'errors'=>$e,
                'message'=>'error to add',
                'status'=>500
            ]);
        }


    }

    /**
     * Display the specified resource.
     */
    public function show(ClassTypeCourse $classTypeCourse)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ClassTypeCourse $classTypeCourse)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ClassTypeCourse $classTypeCourse)
    {
        //
    }
}
