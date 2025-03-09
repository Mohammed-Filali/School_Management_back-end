<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tasks = Task::all(); // Retrieve all tasks, or use pagination if needed
        return response()->json($tasks);
    }

    public function show($id)
    {
        $task = Task::findOrFail($id); // Find task or fail if not found
        return response()->json($task);
    }

    public function store(Request $request)
    {
        // Validate incoming data
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'nullable|in:todo,in progress,completed',
            'priority' => 'nullable|in:high,medium,normal,low',
            'taskable_type' => 'required|string',
            'taskable_id' => 'required|' ,function ($attribute, $value, $fail) {
                $userExists = DB::table('users')->where('id', $value)->exists();

                $teacherExists = DB::table('teachers')->where('id', $value)->exists();

                $adminExists = DB::table('admins')->where('id', $value)->exists();

                if (!$userExists && !$teacherExists && !$adminExists) {
                    $fail('The selected taskable id is invalid. It must exist in one of the following tables: users, teachers, admins.');
                }
            }, // Assuming taskable is a polymorphic relationship
        ]);

        $task = Task::create($validated);

        return response()->json($task, 201);
    }

    public function update(Request $request, $id)
    {
        // Find the task or fail if it doesn't exist
        $task = Task::findOrFail($id);

        $validated = $request->validate([
           'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'nullable|in:todo,in progress,completed',
            'priority' => 'nullable|in:high,medium,normal,low',
            'taskable_type' => 'required|string',
            'taskable_id' => 'required|' ,function ($attribute, $value, $fail) {
                $userExists = DB::table('users')->where('id', $value)->exists();

                $teacherExists = DB::table('teachers')->where('id', $value)->exists();

                $adminExists = DB::table('admins')->where('id', $value)->exists();

                if (!$userExists && !$teacherExists && !$adminExists) {
                    $fail('The selected taskable id is invalid. It must exist in one of the following tables: users, teachers, admins.');
                }
            },
        ]);

        $task->update($validated);

        return response()->json($task);
    }

    public function destroy($id)
    {
        $task = Task::findOrFail($id);

        $task->delete();

        return response()->json(['message' => 'Task deleted successfully.']);
    }
}
