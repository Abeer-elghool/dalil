<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\Auth\LoginRequest;
use App\Http\Resources\Admin\Admin\AdminResource;

class AuthController extends Controller
{
    //

    public function login(LoginRequest $request)
    {
        $token = auth('admin')->attempt($this->getCredentials($request));
        if (!$token) {
            return  response()->json(['data' => null, 'message' => 'Please check your data and try again', 'status' => 422], 422);
        }
        $user = auth('admin')->user();
        if (!$user->active) {
            return response()->json(['data' => null, 'message' => trans('api.validation.please_active_your_account_first'), 'status' => 401], 401);
        }
        data_set($user, 'token', $token);
        return (['data' => (($user)), 'status' => 200, 'message' => trans('api.validation.success')]);
    }


    protected function getCredentials(Request $request)
    {
        $username = $request->email;
        $credentials = [];
        switch ($username) {
            case filter_var($username, FILTER_VALIDATE_EMAIL):
                $username = 'email';
                break;
            case is_numeric($username):
                $username = 'phone';
                break;
            default:
                $username = 'email';
                break;
        }
        $credentials[$username] = $request->email;
        $credentials['password'] = $request->password;
        return $credentials;
    }

    public function profile()
    {
        return response()->json(['status' => 200, 'data' => AdminResource::make(auth('admin')->user()), 'message' => '']);
    }
}
