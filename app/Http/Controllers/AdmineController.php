<?php

namespace App\Http\Controllers;

use App\Models\Admine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdmineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Admine $admine)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Admine $admine)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Admine $admine)
    {
        //
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
