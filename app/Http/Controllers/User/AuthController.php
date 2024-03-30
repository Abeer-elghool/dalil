<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Auth\ForgetPasswordRequest;
use App\Http\Requests\User\Auth\LoginRequest;
use App\Http\Requests\User\Auth\RegisterRequest;
use App\Http\Requests\User\Auth\UpdatePasswordRequest;
use App\Http\Requests\User\Auth\UpdateProfileRequest;
use App\Http\Requests\User\Auth\VerifyRequest;
use App\Http\Resources\User\User\UserResource;
use App\Mail\RestEmail;
use App\Mail\{VerifyEmail, UpdateEmail};
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        try {
            DB::beginTransaction();
            $code = "111111";
            $user = User::create($request->validated() + ['verified_code' => $code]);
            DB::commit();
            // Mail::to($user)->send(new VerifyEmail($code));
            send_admin_notification([
                'title'       => 'new user registered.',
                'body'        => "$user->name registered check it now.",
                'notify_type' => 'user_register',
                'user_id'     => $user->id
            ]);
            return response()->json(['status' => 200, 'data' => null, 'message' => 'User registered successfully']);
        } catch (Throwable $e) {
            DB::rollBack();
            return response()->json(['status' => 500, 'data' => null, 'message' => 'Registration failed'], 500);
        }
    }

    public function login(LoginRequest $request)
    {
        $token = auth('api')->attempt(['email' => $request->email, 'password' => $request->password]);
        if (!$token) {
            return response()->json(['status' => 401, 'message' => 'wrong email or password!', 'data' => null], 401);
        }
        $user = auth('api')->user();
        if ($user->email_verified_at == null)
            return response()->json(['status' => 403, 'message' => 'Please verify your email first.'], 403);
        if (!$user->active)
            return response()->json(['status' => 403, 'message' => 'your account have been deactivated.'], 403);
        data_set($user, 'token', $token);
        return UserResource::make($user)->additional(['status' => 200, 'message' => 'Login Success.']);
    }

    public function verify(VerifyRequest $request)
    {
        $user = User::where(['email' => $request->email])->firstOrFail();
        if ($user->active && $user->phone_verified_at) {
            return response()->json(['status' => 422, 'message' => 'your email already active.', 'data' => null], 422);
        }
        if ($request->verified_code != $user->verified_code) {
            return response()->json(['status' => 422, 'message' => 'Wrong verification code.', 'data' => null], 422);
        }
        $user->update(['active' => true, 'verified_code' => null, 'email_verified_at' => now()]);
        $token = auth('api')->login($user);
        data_set($user, 'token', $token);
        return UserResource::make($user)->additional(['status' => 'success', 'message' => 'Email verification success.']);
    }

    public function forgetPassword(ForgetPasswordRequest $request)
    {
        $code = "111111";
        $user = User::where('email', $request->email)->firstOrFail();
        $user->update(['reset_code' => $code]);
        Mail::to($user)->send(new RestEmail($code));
        return  response()->json(['status' => 200, 'message' => 'A Rest password code has been sent to your email.', 'data' => null], 200);
    }

    public function updatePassword(UpdatePasswordRequest $request)
    {
        $user = User::where(['email' => $request->email, 'reset_code' => $request->reset_code])->firstOrFail();
        $user->update(['password' => $request->password, 'reset_code' => null]);
        return response()->json(['status' => 200, 'message' => 'password updated successfully.', 'data' => null], 200);
    }

    public function logout()
    {
        auth('api')->logout();
        return response()->json(['status' => 200,  'message' => 'logout success.', 'data' => null]);
    }

    public function profile()
    {
        return UserResource::make(auth('api')->user())->additional(['status' => 200, 'message' => '']);
    }

    public function update_profile(UpdateProfileRequest $request)
    {
        $user = auth('api')->user();
        $data = collect($request->all())->filter()->toArray();
        $message = '';
        // if ($data['email'] && $data['email'] != $user->email) {
        //     $code = "111111";
        //     $data['new_email'] = $data['email'];
        //     $data['verified_code'] = $code;
        //     unset($data['email']);
        //     $message = 'Please verify the new email address!';
        // }
        try {
            DB::beginTransaction();
            $user->update($data);
            $token = auth('api')->login($user);

            data_set($user, 'token', $token);

            DB::commit();
            // if ($data['new_email']) {
            //     $link = env('CURRENT_HOST') . '/api/user/update_email?code=' . $code . '&email=' . $data['new_email'];
            //     Mail::to($data['new_email'])->send(new UpdateEmail($link));
            // }
            return UserResource::make($user)->additional(['status' => 200, 'message' => '']);
        } catch (Throwable $e) {
            DB::rollBack();
            \Log::info($e->getMessage());
            return response()->json(['status' => 500, 'data' => null, 'message' => 'Update failed'], 500);
        }
    }

    public function update_email(Request $request)
    {
        $code = $request->code;
        $email = $request->email;
        if($email && $code)
        {
            $user = User::where(['new_email' => $email, 'verified_code' => $code])->first();
            if($user)
            {
                $user->update(['email' => $email, 'verified_code' => null, 'new_email' => null]);
                return view('emails.email_update_success');
            }
        }
        return view('emails.error');
    }
}
