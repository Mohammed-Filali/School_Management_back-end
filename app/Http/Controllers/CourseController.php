<?php

namespace App\Http\Controllers;

use App\Http\Requests\CourStoreRequest;
use App\Http\Resources\CourResource;
use App\Models\Course;
use Exception;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return CourResource::collection(Course::with('teachers')->get()); ;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CourStoreRequest $request)
    {
        try{
            $cours= $request->validated();
            $cour = Course::create($cours) ;
            $response = new CourResource($cour) ;
            return response()->json([
                'Cour' => $response,
                'message' => __('Cour created successfully')
              ]);
        }catch(Exception $e){
            return response()->json([
                'message' => 'Failed to create Cour',
             'errors' => [
                 'message' => $e->getMessage(),
                 'code' => $e->getCode(),
                 'file' => $e->getFile(),
                 'line' => $e->getLine(),],
                 ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Course $course)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CourStoreRequest $request, $id )
    {
        $course= Course::find($id);
        try{
            $newcours= $request->validated();
            $course->fill($newcours)->save() ;
            return response()->json([
                'Cour' => $course,
                'message' => __('Cour Updated successfully')
              ]);
        }catch(Exception $e){
            return response()->json([
                'message' => 'Failed to Update Cour',
             'errors' => [
                 'message' => $e->getMessage(),
                 'code' => $e->getCode(),
                 'file' => $e->getFile(),
                 'line' => $e->getLine(),],
                 ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $course =Course::find($id);
        try{
            $course->delete();

           return response()->json([
            'message' => 'Cour successfully deleted!',
            'data' => new CourResource($course),
            'status' =>201
            ], 201);

        }catch (\Exception $e) {
            return response()->json([
               'message' => 'Failed to delete Cour',
                ], 500);
            }
        }

}
