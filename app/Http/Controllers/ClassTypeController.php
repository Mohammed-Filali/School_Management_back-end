<?php

namespace App\Http\Controllers;

use App\Http\Resources\ClasseTypeResource;
use App\Models\ClassType;
use Illuminate\Http\Request;

class ClassTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return ClasseTypeResource::collection(ClassType::with('classe')->with('classeTypeCourse')->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $classType = ClassType::find($id);

        if (!$classType) {
            return response()->json([
                'message' => 'ClassType not found.',
            ], 404);
        }

        return new ClasseTypeResource($classType);

    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ClassType $classType)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ClassType $classType)
    {
        //
    }
}
