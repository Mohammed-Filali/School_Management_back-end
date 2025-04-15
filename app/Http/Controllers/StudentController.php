<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Http\Resources\StudentResource;
use App\Models\User;
use DateTime;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
  public function index(): AnonymousResourceCollection
  {
    return  StudentResource::collection(User::with('attendance','classe','records.exams')->get())  ;
}

  public function store(StoreStudentRequest $request): JsonResponse
  {
    $formFields = $request->validated();
    $formFields['password'] = Hash::make($formFields['password']);
    // $formFields['last_login_date'] = new DateTime();
    $student = User::create($formFields);
    $response = new StudentResource($student);
    return response()->json([
      'student' => $response,
      'message' => __('Student created successfully')
    ]);
  }

  public function update(UpdateStudentRequest $request, $id): JsonResponse
  {
    $student=User::find($id);
    $formFields = $request->validated();
    $formFields['password'] = Hash::make($formFields['password']);
    $student->update($formFields);
    return response()->json([
      'student' => new StudentResource($student),
      'message' => __('Student updated successfully')
    ]);
  }

  public function destroy(User $student): StudentResource
  {
    $student->delete();

    return new StudentResource($student);
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
