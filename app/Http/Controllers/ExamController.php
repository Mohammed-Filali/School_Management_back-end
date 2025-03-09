<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExamRequest;
use App\Http\Resources\ExamResource;
use App\Models\Exam;
use Exception;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            'data'=>ExamResource::collection(Exam::with('classe')->with('course')->with('teacher')->with('records')->get()),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ExamRequest $request)
    {
        try{
            $exams= $request->validated();
            $exam = Exam::create($exams) ;
            $response = new ExamResource($exam) ;
            return response()->json([
                'Exame' => $response,
                'message' => __('Exame created successfully')
              ]);
        }catch(Exception $e){
            return response()->json([
                'message' => 'Failed to create Exame',
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
    public function show(Exam $exam)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ExamRequest $request, $id)
    {
        $exam = Exam::find($id) ;

        try{
            $exams= $request->validated();
            $exam->fill($exams)->save();
            $response = new ExamResource($exam) ;
            return response()->json([
                'Exame' => $response,
                'message' =>('Exame created successfully')
              ]);
        }catch(Exception $e){
            return response()->json([
                'message' => 'Failed to create Exame',
             'errors' => [
                 'message' => $e->getMessage(),
                 'code' => $e->getCode(),
                ],
                 ], 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $exam =Exam::find($id);
       try{
           $exam->delete();

          return response()->json([
           'message' => 'Exam successfully deleted!',
           'data' => $exam ,
           'status' =>201
           ], 201);

       }catch (\Exception $e) {
           return response()->json([
              'message' => 'Failed to delete exam',
               ], 500);
           }
       }

}
