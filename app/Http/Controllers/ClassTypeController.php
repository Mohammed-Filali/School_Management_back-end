<?php

namespace App\Http\Controllers;

use Log;
use Storage;
use App\Models\ClassType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ClasseTypeResource;

class ClassTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return ClasseTypeResource::collection(
            ClassType::with('classe', 'classTypeCourses.course')->get()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Log::info("Request Data create:", $request->all()); // Log all incoming data

        $messages = [
            'name.required' => 'The class name is required.',
            'code.required' => 'The class code is required.',
            'image.image' => 'The uploaded file must be an image.',
            'image.mimes' => 'The image must be of type: jpg, jpeg, png, gif.',
            'image.max' => 'The image size must not exceed 5MB.',
        ];

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif,avif|max:5120', // Max 5MB
            'description' => 'nullable|string',
        ], $messages);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public'); // Store in 'public/images'
        }

        $classType = new ClassType();
        $classType->name = $validated['name'];
        $classType->code = $validated['code'];
        $classType->description = $validated['description'] ?? null;
        $classType->image = $imagePath; // Save the image path if uploaded
        $classType->save();

        return response()->json([
            'message' => 'Class added successfully',
            'data' => new ClasseTypeResource($classType),
        ]);
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
    public function update(Request $request)
{

    $classType = ClassType::find($request->id);
    if (!$classType) {
        return response()->json(['message' => 'Class not found'], 404);
    }

    $messages = [
        'name.required' => 'The class name is required.',
        'code.required' => 'The class code is required.',
        'image.image' => 'The uploaded file must be an image.',
        'image.mimes' => 'The image must be of type: jpg, jpeg, png, gif.',
        'image.max' => 'The image size must not exceed 5MB.',
    ];

    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'code' => 'required|string|max:50',
        'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:5120', // Max 5MB
        'description' => 'nullable|string',
    ], $messages);



    if ($request->hasFile('image')) {
        if ($classType->image && Storage::exists($classType->image)) {
            Storage::delete($classType->image);
        }

        $imagePath = $request->file('image')->store('images', 'public');
        $classType->image = $imagePath;
    }

    $classType->name = $validated['name'];
    $classType->code = $validated['code'];
    $classType->description = $validated['description'] ?? null;
    $classType->image = $imagePath; // Save the image path if uploaded
    $classType->save();

    $classType->save();

    return response()->json([
        'message' => 'Class updated successfully',
        'data' => new ClasseTypeResource($classType),
    ]);
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy( $id)
    {
        $classType = ClassType::find($id);
        try {
            if ($classType->image && Storage::exists($classType->image)) {
                Storage::delete($classType->image);
            }

            $classType->deleteOrFail();

            return response()->json([
                'message' => 'Class type deleted successfully',
                'status' => 200,
            ]);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Failed to delete class type'], 500);
        }
    }
}
