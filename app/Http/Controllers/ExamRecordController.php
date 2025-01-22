<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExamRecordRequest;
use App\Http\Resources\ExamRecordResource;
use App\Models\ExamRecord;
use Exception;
use Illuminate\Http\Request;

class ExamRecordController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return  ExamRecordResource::collection(ExamRecord::with('exams')->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ExamRecordRequest $request)
    {
        try{
            $records = $request->validated();
            $record=ExamRecord::create($records);
            $respons=new ExamRecordResource($record);
            return response()->json([
                'data'=>$respons,
                'status'=>200,
                'message'=> 'record created succefuly'
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
    public function show(ExamRecord $examRecord)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ExamRecord $examRecord)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ExamRecord $examRecord)
    {
        //
    }
}
