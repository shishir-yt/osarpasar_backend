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
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'phone_number' => 'required|numeric|unique:users',
            'email' => 'required|email| unique:users',
            'password' => 'required',
            'type' => 'required',

        ]);
        if ($validator->fails()) {
            return $this->sendError('Error Validation', $validator->errors(), 400);
        }

        $name = $request->input('name');
        $phone_number = $request->input('phone_number');
        $email = $request->input('email');
        $password = $request->input('password');
        $type = $request->input('type');
        $password = Hash::make($password);
        $ldate = date('Y-m-d H:i:s');

        $user = new User;
        $user->name = $name;
        $user->phone_number = $phone_number;
        $user->email = $email;
        $user->password = $password;
        $user->email_verified_at = $ldate;
        $user->type = $type;

        $user->save();
        $success['token'] = $user->createToken('MyAuthApp')->plainTextToken;

        $success['data'] = $user;

        return $this->sendResponse($success, 'User created successfully.');

    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Error Validation', $validator->errors(), 400);
        }
        // $credentials = $request->only('email', 'password');

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            DB::table('personal_access_tokens')->where('tokenable_id', $user->id)->delete();



            $success['token'] =  $user->createToken('classecom')->plainTextToken;
            $success['data'] =  $user;


            return $this->sendResponse($success, 'User Login successfully.');


        } else {
            return $this->sendError('Unauthorised.', ['error' => 'Credettials doesnot match']);
        }
    }
}
