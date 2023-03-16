<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\BaseController as BaseController;
use Carbon\Carbon;

class AuthController extends BaseController
{
    public function register(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'name' => 'required|string',
            'phone_number' => 'required|min:8|max:13|unique:users',
            'email' => 'required|email| unique:users',
            'password' => 'required'
        ]);
        if ($validator->fails()) {
            $response['message'] = $validator->messages()->first();
            $response['status'] = false;
            return $response;
        } else {
            $user = new User([
                'name' => $request->name,
                'phone_number' => $request->phone_number,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'email_verified_at' => Carbon::now(),
                'is_admin' => "2"
            ]);
            $user->save();

            $tokenResult = $user->createToken('Personal Access Token');
            $token = $tokenResult->token;
            $token->expires_at = Carbon::now()->addMonths(3);
            $token->save();

            $customer = $user->only(
                ['id',
                    'name',
                    'email',
                    'phone_number',
                ]
            );
            $token = [
                'access_token' => $tokenResult->accessToken,
                'token_type' => 'Bearer',
                'expires_at' => Carbon::parse(
                    $tokenResult->token->expires_at
                )->toDateTimeString()];

            return response()->json(['status' => true,
            'message' => 'User Registered SUccessfully.',
                'data' => ['customer' => $customer, 'token' => $token,]
            ]);
        }

    }

    public function login(Request $request)
    {
        try {
            $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
                'email' => 'required',
                'password' => 'required|string',
                'remember_me' => 'boolean',
            ]);
            if ($validator->fails()) {
                $response['message'] = $validator->messages()->first();
                $response['status'] = false;
                return $response;
            } else {
                $credentials = request(['email', 'password']);
                $credentials = ['email' => $request->get('email'), 'password' => $request->get('password')];

                if (!Auth::guard()->attempt($credentials))
                    return response()->json([
                        'status' => false,
                        'message' => 'The email or password is incorrect.'
                    ]);
                $user = $request->user();

                $tokenResult = $user->createToken('Personal Access Token');
                $token = $tokenResult->token;
                if ($request->remember_me)
                    $token->expires_at = \Illuminate\Support\Carbon::now()->addMonths(3);
                $token->save();
                $token = [
                    'access_token' => $tokenResult->accessToken,
                    'token_type' => 'Bearer',
                    'expires_at' => Carbon::parse(
                        $tokenResult->token->expires_at
                    )->toDateTimeString()];

                return response()->json(['status' => true,
                    'data' => ['user' => $user, 'token' => $token]
                ]);
            }
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
        }
}
