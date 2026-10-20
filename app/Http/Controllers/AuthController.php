<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest\ChangePasswordRequest;
use App\Http\Requests\AuthRequest\ForgotPasswordRequest;
use App\Http\Requests\AuthRequest\LoginRequest;
use App\Http\Requests\AuthRequest\RegisterRequest;
use App\Http\Requests\AuthRequest\ResetPasswordRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class AuthController extends Controller
{

    public function register(RegisterRequest $request)
    {
        $data = $request->validated();

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
        ]);

        // إرسال إشعار التحقق من البريد بعد إنشاء الحساب.
        event(new Registered($user));

        return response()->json([
            'message' => 'Account created successfully. Please verify your email.',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'email_verified_at' => $user->email_verified_at,
                'profile_completed' => $user->profile_completed,
            ],
        ], 201);
    }

    public function login(LoginRequest $request)
    {

        $data = $request->validated();

        $user = User::where('email', $data['email'])->first();



        if (! $user || ! Hash::check($data['password'], $user->password)) {

            return response()->json([
                'message' => 'Invalid credentials.',
            ], 401);
        }

        if ($user->isSuspended()) {
            return response()->json([
                'message' => 'Your account is suspended.',
            ], 403);
        }

        $token = $user->createToken('mobile-app')->plainTextToken;

        return response()->json([
            'message' => 'Login successful.',
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'email_verified_at' => $user->email_verified_at,
                'profile_completed' => $user->profile_completed,
            ],
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()?->delete();

        return response()->json([
            'message' => 'Logged out successfully.',
        ]);
    }


    public function changePassword(ChangePasswordRequest $request)
    {
        $user = $request->user();
        $data = $request->validated();

        $user->password = $data['password'];
        $user->save();

        $user->tokens()->delete();

        return response()->json([
            'message' => 'Password changed successfully. Please log in again.',
        ]);
    }

    public function forgotPassword(ForgotPasswordRequest $request)
    {
        Password::sendResetLink($request->validated());

        // لا نكشف إن كان البريد مسجلاً أم لا.
        return response()->json([
            'message' => 'If the account exists, a password reset link has been sent.',
        ]);
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $data = $request->validated();

        $status = Password::reset(

            $data,

            function (User $user, string $password) {
                $user->password = $password;
                $user->save();

                // إبطال جميع التوكنات بعد إعادة تعيين كلمة المرور.
                $user->tokens()->delete();
            }

        );

        if ($status === Password::PASSWORD_RESET) {
            return response()->json([
                'message' => 'Password has been reset successfully. Please log in again.',
            ]);
        }

        return response()->json([
            'message' => 'The reset link is invalid or expired.',
        ], 422);
    }

    public function verifyEmail(Request $request, string $id, string $hash)
    {
        $user = User::findOrFail($id);

        // التأكد من أن الرابط يخص البريد الحالي لهذا الحساب.
        if (! hash_equals(sha1($user->getEmailForVerification()), $hash)) {
            return response()->json([
                'message' => 'Invalid verification link.',
            ], 403);
        }

        if ($user->hasVerifiedEmail()) {
            return response()->json([
                'message' => 'Email already verified.',
            ]);
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return response()->json([
            'message' => 'Email verified successfully.',
        ]);
    }

    public function resendVerificationEmail(Request $request)
    {
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return response()->json([
                'message' => 'Email already verified.',
            ], 409);
        }

        $user->sendEmailVerificationNotification();

        return response()->json([
            'message' => 'Verification email sent.',
        ]);
    }
}
