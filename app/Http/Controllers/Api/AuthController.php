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
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpMail;

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
                'email' => 'nullable',
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

        public function updateProfile(Request $request)
    {
        $customer = auth()->user();

        //validation
        $validator = Validator::make($request->all(), [
            'phone' => 'nullable|min:8|max:11'
        ]);

        if ($validator->fails()) {
            $response['message'] = $validator->messages()->first();
            $response['success'] = false;
            return response()->json($response);
        }
        try {
            $customer->Update($request->only('name', 'email', 'phone'));

            if ($request->profile_image) {
                try {
                    $customer->clearMediaCollection();
                    $customer->addMediaFromBase64($request->profile_image)
                        ->toMediaCollection();
                    $customer->save();
                } catch (FileDoesNotExist $e) {
                } catch (\Exception $e) {
                    error_log($e);
                }
            }

        } catch (\Exception $e) {
            return response()->json(
                [
                    $e,
                    'success' => false,
                    'message' => 'Could\'t update the profile'
                ]
            );
        }
        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
            'data' => $customer
        ]);
    }
    public function changePassword(Request $request)
    {
        try {
            $customer = auth('customer-api')->user();
            //validation
            $validator = Validator::make($request->all(), [
                'old_password' => 'required|string|min:8',
                'new_password' => 'required|string|min:8',
            ]);

            if ($validator->fails()) {
                $response['data'] = $validator->messages();
                $response['success'] = false;
                return $response;
            }

            if (!(Hash::check($request->get('old_password'), $customer->getAuthPassword()))) {
                // The passwords matches
                return response()->json(['success' => false, 'message' => 'Your current password does not match with the password you provided. Please try again.']);
            }

            //update
            if (strcmp($request->get('old_password'), $request->get('new_password')) == 0) {
                //Current password and new password are same
                return response()->json(['success' => false, 'message' => 'New Password cannot be same as your current password. Please choose a different password.']);
            }

            $customer->password = bcrypt($request->get('new_password'));
            $customer->save();

            return response()->json([
                'success' => true,
                'message' => 'Password updated successfully',
                'data' => $customer,
            ]);
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function forgetPassword(Request $request)
    {
        try {
            //validation
            $validator = Validator::make($request->all(), [
                'email' => 'required',
            ]);

            if ($validator->fails()) {
                $response['data'] = $validator->messages();
                $response['success'] = false;
                return $response;
            }

            $user = User::where('email', $request->email)->first();

            $otp = "12345";
            $user->otp = $otp;
            $user->update();

            $mail_details = [
                'subject' => 'OTP Verification',
                'body' => $otp
            ];

            Mail::to($request->email)->send(new OtpMail($mail_details));


            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid email. Please try again.'
                ]);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'user' => $user->id,
                    'otp' => $otp
                ]
            ]);
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function resetPassword(Request $request)
    {
        try {
            //validation
            $validator = Validator::make($request->all(), [
                'user_id' => 'required',
                'otp' => 'required',
                'new_password' => 'required|string|min:8',
            ]);

            if ($validator->fails()) {
                $response['data'] = $validator->messages();
                $response['success'] = false;
                return $response;
            }

            $user = User::where('id', $request->customer_id)->first();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid customer id. Please try again.'
                ]);
            }
            if ($request->otp != $user->otp) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid otp.'
                ]);
            }

            $user->password = bcrypt($request->new_password);
            $user->update();
            return response()->json([
                'success' => true,
                'message' => 'Password Reset Successfully.'
            ]);
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }
}