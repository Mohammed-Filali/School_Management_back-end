<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTeacherRequest;
use App\Http\Requests\UpdateTeacherRequest;
use App\Http\Resources\TeacherResource;
use App\Models\Teacher;
use DateTime;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return  TeacherResource::collection(Teacher::all())  ;

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTeacherRequest $request)
    {
        try{
            $teachrs=  $request->validated() ;
            $teachrs['password'] = Hash::make($teachrs['password']);
            // $teachrs['last_login'] = new DateTime();
            $teacher =Teacher::create($teachrs);
            return response()->json([
                'message' => 'teacher successfully created!',
                'data' => new TeacherResource($teacher),
                'status' =>201
            ], 201);

        } catch (\Exception $e) {
            // If something goes wrong, return an error message
            return response()->json([
               'message' => 'Failed to create teacher',
            'errors' => [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                // You can add more error details if needed, such as file/line info:
                'file' => $e->getFile(),
                'line' => $e->getLine(),],
                ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Teacher $teacher)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTeacherRequest $request, Teacher $teacher)
    {

            // Update the teacher record

            $newteacher=$request->validated();
            $newteacher['password'] = Hash::make($newteacher['password']);

            $teacher->update($newteacher);

            // Return a success response
            return response()->json([
                'student' => new TeacherResource($teacher),
                'message' => __('Student updated successfully')
              ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Teacher $teacher)
    {
        try{
            $teacher->delete();

           return response()->json([
            'message' => 'teacher successfully deleted!',
            'data' => new TeacherResource($teacher),
            'status' =>201
            ], 201);

        }catch (\Exception $e) {
            // If something goes wrong, return an error message
            return response()->json([
               'message' => 'Failed to delete teacher',
                ], 500);
            }
    }

    public function updatePassword(Request $request)
  {
      $request->validate([
          'current_password' => 'required',
          'new_password' => 'required|min:8',
      ]);

      $user = $request->user();

      // Check current password
      if (!Hash::check($request->current_password, $user->password)) {
          return response()->json(['error' => 'Current password is incorrect.'], 400);
      }

      // Update password
      $user->password = Hash::make($request->new_password);
      $user->save();

      return response()->json(['message' => 'Password updated successfully.']);
  }
}
