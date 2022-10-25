<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends BaseController
{
    public function register(Request $request){
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'confirm_password' => 'required|same:password'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Please Validate', $validator->errors());
        }

        $input = $request->all();

        $input['password'] = Hash::make($input['password']);
        $user = User::create($input);

        $success['token'] = $user->createToken('Dc4GhT$adfGSDHFDs7&34FfdfFDLhjkN_09%fg')->accessToken;
        $success['name'] = $user->name;

        return $this->sendResponse($success, 'User registered successfully');
    }


    public function login(Request $request){
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password]))
        {
            $user = Auth::user();

            $success['token'] = $user->createToken('Dc4GhT$adfGSDHFDs7&34FfdfFDLhjkN_09%fg')->accessToken;
            $success['name'] = $user->name;
            return $this->sendResponse($success, 'User login successfully');
        }else{
            return $this->sendError('Please check your email|password', ['error' => 'Unauthorized']);
        }
    }


}
