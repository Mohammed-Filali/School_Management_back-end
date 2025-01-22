<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;

class AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request)
    {
        $request->authenticate();
        $guards = ['web', 'teacher', 'parent', 'admin'];

        $user = null;
        foreach ($guards as $guard) {
          $currentGuard = Auth::guard($guard);
          if ($currentGuard->check()) {
            $user = $currentGuard->user();break;
          }
        };
        $request->session()->regenerate();

        return response()->json([
            'data'=>$user,
            'success'=>true,
            'message' => 'Login successful',
            'token' => $user->createToken('api',[$user->getRoleAttribute()])->plainTextToken ,
        ]);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): Response
    {
        $guards = ['web', 'teacher', 'parent', 'admin'];

        $user=null;

        foreach ($guards as $guard) {
            $currentGuard = Auth::guard($guard);
            if ($currentGuard->check()) {
                $user = $currentGuard->user();
                break;
            }

        }
        $user->tokens()->delete();

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return response()->noContent();
    }





    public function sendResetLink(Request $request)
{
    // Validate email for specific roles
    $validator = Validator::make($request->all(), [
        'email' => 'required|email|exists:users,email|exists:admins,email|exists:teachers,email|exists:parents,email',
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    // Determine which provider/role is being requested for password reset
    $role = $this->determineRoleByEmail($request->email);

    // Send reset link via Laravel's built-in Password reset
    $status = Password::sendResetLink(
        $request->only('email'),
        $role
    );

    if ($status === Password::RESET_LINK_SENT) {
        return response()->json(['message' => 'Reset link sent successfully!']);
    }

    return response()->json(['message' => 'Failed to send reset link.'], 500);
}

private function determineRoleByEmail($email)
{
    if (strpos($email, 'admin') !== false) {
        return 'admins';
    } elseif (strpos($email, 'teacher') !== false) {
        return 'teachers';
    } elseif (strpos($email, 'parent') !== false) {
        return 'parents';
    } else {
        return 'users';
    }
}

}
