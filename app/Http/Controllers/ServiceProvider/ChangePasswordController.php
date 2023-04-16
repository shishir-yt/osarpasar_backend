<?php

namespace App\Http\Controllers\ServiceProvider;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;

class ChangePasswordController extends Controller
{
    function changePassword()
    {
        $info['title'] = 'Change Password';
        return view('service_providers.change_password', $info);
    }

    function changePasswordSave(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required',
            'confirm_password' => 'required|same:new_password',
        ]);
        $serviceProvider = User::findOrFail(auth()->user()->id);
        if ($request->old_password == Crypt::decryptString($serviceProvider->password)) {
            $serviceProvider->password = Crypt::encryptString($request->new_password);
            $serviceProvider->save();
            return redirect()->back()->with('success', 'Password Changed Successfully.');
        } else {
            return redirect()->back()->with('error', 'Old Password Mismatched.')->withInput($request->input());
        }
    }
}