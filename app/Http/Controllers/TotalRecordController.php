<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\TotalRecords;
use Illuminate\Http\Request;

class TotalRecordController extends Controller
{
    public function index()
    {
        // Logic to retrieve total records
        // For example, you might want to fetch all records from a model
        $totalRecords = TotalRecords::all();

        return response()->json($totalRecords);
    }

    public function show($id)
    {
        // Logic to retrieve a specific record by ID
        $record = TotalRecords::find($id);

        if (!$record) {
            return response()->json(['message' => 'Record not found'], 404);
        }

        return response()->json($record);
    }
    public function store(Request $request)
    {
        // Logic to create a new record
        $validatedData = $request->validate([
            'user_id' => 'required|integer',
            'course_id' => 'required|integer',
            'total' => 'required|decimal:2,2',
        ]);

        $record = TotalRecords::create($validatedData);

        return response()->json($record, 201);
    }
    public function update(Request $request, $id)
    {
        // Logic to update an existing record
        $record = TotalRecor::find($id);

        if (!$record) {
            return response()->json(['message' => 'Record not found'], 404);
        }

        $validatedData = $request->validate([
            'user_id' => 'sometimes|required|integer',
            'course_id' => 'sometimes|required|integer',
            'total' => 'sometimes|required|decimal:2,2',
        ]);

        $record->update($validatedData);

        return response()->json($record);
    }
    public function destroy($id)
    {
        // Logic to delete a record
        $record = TotalRecords::find($id);

        if (!$record) {
            return response()->json(['message' => 'Record not found'], 404);
        }

        $record->delete();

        return response()->json(['message' => 'Record deleted successfully']);
    }
}
