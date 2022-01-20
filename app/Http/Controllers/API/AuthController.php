<?php

namespace App\Http\Controllers\API;

use App\Models\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    //
    public function register(Request $request)
    {

        $validator = Validator::make($request->all(), [
            //'name' => 'required',
            'userName' => 'required',
            'password' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'validation_errors' => $validator
            ]);
        } else {
            $user = User::create([
                //'name' => $request->name,
                'userName' => $request->userName,
                'password' => Hash::make($request->password),
            ]);
            $token = $user->createToken($user->userName . '_Token')->plainTextToken;
            return response()->json([
                'status' => 200,
                'userId' => $user->id,
                'username' => $user->userName,
                'token' => $token,
                'message' => 'Register Successfully'
            ]);
        }
    }
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'userName' => 'required',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'validation_errors' => $validator
            ]);
        } else {
            $user = User::where('userName', $request->userName)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'status' => 401,
                    'message' => 'Invalid Credentials'
                ]);
            } else {

                $token = $user->createToken($user->userName . '_Token')->plainTextToken;
                return response()->json([
                    'status' => 200,
                    'userId' => $user->id,
                    'username' => $user->userName,
                    'token' => $token,
                    'message' => 'Logged In Successfully'
                ]);
            }
        }
    }

    //logout
    public function logout()
    {
        // auth()->user()->tokens()->delete();
        // return response()->json([
        //     "status" => 200,
        //     "message" => "logged out successfully",
        // ]);
    }
}
