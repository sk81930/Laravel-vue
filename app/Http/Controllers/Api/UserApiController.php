<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
Use Auth;


class UserApiController extends Controller
{
     public function updateProfile(Request $request)
    {
        $requestData = $request->all();

        $validate_data = [
            'name' => 'required|max:55',
        ];

        if($requestData["password"] != ""){
            $validate_data["password"] = 'min:6';
            $validate_data['confirm_password'] = 'required_with:password|same:password';
        }


        $validator = Validator::make($requestData,$validate_data);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }
        $user = Auth::user();

        $user->name = $request->name;
        if($requestData["password"] != ""){
             $user->password = $requestData["password"];
        }
        $user->save();

        return response([ 'status' => true, 'message' => 'Profile successfully update.' ], 200);
    }
    public function getUserRole()
    {
        $userrole = User::get();

        return response([ 'status' => true, "userrole" => $userrole ], 200);
    }


}
