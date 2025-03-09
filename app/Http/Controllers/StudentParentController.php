<?php

namespace App\Http\Controllers;

use DateTime;
use App\Models\StudentParent;
use App\Http\Resources\StudentParentResource;
use App\Http\Requests\StoreStudentParentRequest;
use App\Http\Requests\UpdateStudentParentRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class StudentParentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return  StudentParentResource::collection(StudentParent::all())  ;

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStudentParentRequest $request)
    {
        try{
            $parents=  $request->validated() ;
            $parents['password'] = Hash::make($parents['password']);
            $parents['last_login'] = new DateTime();
            $parent =StudentParent::create($parents);
            return response()->json([
                'message' => 'Parent successfully created!',
                'data' => new StudentParentResource($parent),
                'status' =>201
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
               'message' => 'Failed to create parent',
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
    public function show(StudentParent $studentParent)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStudentParentRequest $request,StudentParent $Parent)
{
    if (!$Parent) {
        return response()->json(['message' => 'Parent not found'], 404);
    }

    try {

        $newParent=$request->validated();
        $newParent['password'] = Hash::make($newParent['password']);

        $Parent->update($newParent);

        return response()->json([
            'message' => 'Parent successfully updated!',
            'data' => $Parent,
            'status' => 201,
        ], 201);
    } catch (ModelNotFoundException $e) {
        return response()->json(['message' => 'Parent not found'], 404);
    } catch (\Exception $e) {
        return response()->json(['message' => 'Failed to update parent'], 500);
    }
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StudentParent $Parent)
    {
        try{
                $Parent->delete();

               return response()->json([
                'message' => 'Parent successfully deleted!',
                'data' => new StudentParentResource($Parent),
                'status' =>201
                ], 201);

            }catch (\Exception $e) {
                // If something goes wrong, return an error message
                return response()->json([
                   'message' => 'Failed to delete parent',
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

                if (!Hash::check($request->current_password, $user->password)) {
                    return response()->json(['error' => 'Current password is incorrect.'], 400);
                }

                $user->password = Hash::make($request->new_password);
                $user->save();

                return response()->json(['message' => 'Password updated successfully.']);
            }
}
