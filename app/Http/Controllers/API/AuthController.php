<?php
namespace App\Http\Controllers\API;
use App\Services\OtpService;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
class AuthController extends Controller
{

 public function register(Request $request, OtpService $otpService)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'phone' => 'required|string|unique:users,phone',
        'password' => 'required|min:6',
    ]);

    $user = User::create([
        'name' => $request->name,
        'phone' => $request->phone,
        'password' => Hash::make($request->password),
    ]);

    $otpService->send($user);

    return response()->json([
        'message' => 'OTP sent successfully',
    ], 201);
}
    public function login(Request $request)
{
    $request->validate([
        'phone' => 'required',
        'password' => 'required',
    ]);

    $user = User::where('phone', $request->phone)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {

        return response()->json([
            'message' => 'Invalid phone or password'
        ], 401);

    }

    if (!$user->is_verified) {

        return response()->json([
            'message' => 'Please verify your phone first.'
        ], 403);

    }

    $token = $user->createToken('Foodify')->plainTextToken;

    return response()->json([
        'message' => 'Login Successfully',
        'token' => $token,
        'user' => $user
    ]);
}


    public function logout(Request $request)
    {

$token = $request->user()->currentAccessToken();

if ($token) {
    $token->delete();
}

return response()->json([
    'message' => 'Logout Successfully'
]);
        return response()->json([
            'message'=>'Logout Successfully'
        ]);

    }
    public function resendOtp(Request $request, OtpService $otpService)
{
    $request->validate([
        'phone' => 'required',
    ]);

    $user = User::where('phone', $request->phone)->first();

    if (!$user) {
        return response()->json([
            'message' => 'User not found'
        ], 404);
    }

    $otpService->send($user);

  return response()->json([
    'message' => 'OTP sent successfully.',
    'otp' => $user->otp
]);
}

    public function profile(Request $request)
    {

        return response()->json($request->user());

    }
    public function verifyOtp(Request $request)
{
    $request->validate([
        'phone' => 'required',
        'otp' => 'required|digits:6',
    ]);

    $user = User::where('phone', $request->phone)->first();

    if (!$user) {
        return response()->json([
            'message' => 'User not found'
        ], 404);
    }

    if ($user->otp !== $request->otp) {
        return response()->json([
            'message' => 'Invalid OTP'
        ], 400);
    }

    if (now()->greaterThan($user->otp_expires_at)) {
        return response()->json([
            'message' => 'OTP expired'
        ], 400);
    }

    $user->update([
        'otp' => null,
        'otp_expires_at' => null,
        'is_verified' => true,
    ]);

    $token = $user->createToken('Foodify')->plainTextToken;

    return response()->json([
        'message' => 'Verified Successfully',
        'token' => $token,
        'user' => $user,
    ]);
}
public function forgotPassword(Request $request, OtpService $otpService)
{
    $request->validate([
        'phone' => 'required',
    ]);

    $user = User::where('phone', $request->phone)->first();

    if (!$user) {
        return response()->json([
            'message' => 'User not found'
        ], 404);
    }

    $otpService->send($user);

    return response()->json([
        'message' => 'OTP sent successfully.'
    ]);
}
public function verifyResetOtp(Request $request)
{
    $request->validate([
        'phone' => 'required',
        'otp' => 'required|digits:6',
    ]);

    $user = User::where('phone', $request->phone)->first();

    if (!$user) {
        return response()->json([
            'message' => 'User not found'
        ], 404);
    }

    if ($user->otp != $request->otp) {
        return response()->json([
            'message' => 'Invalid OTP'
        ], 400);
    }

    if (now()->gt($user->otp_expires_at)) {
        return response()->json([
            'message' => 'OTP expired'
        ], 400);
    }

    return response()->json([
        'message' => 'OTP Verified'
    ]);
}
public function resetPassword(Request $request)
{
    $request->validate([
        'phone' => 'required',
        'password' => 'required|min:6|confirmed',
    ]);

    $user = User::where('phone', $request->phone)->first();

    if (!$user) {
        return response()->json([
            'message' => 'User not found'
        ], 404);
    }

    $user->update([
        'password' => Hash::make($request->password),
        'otp' => null,
        'otp_expires_at' => null,
    ]);

    return response()->json([
        'message' => 'Password reset successfully.'
    ]);
}
}