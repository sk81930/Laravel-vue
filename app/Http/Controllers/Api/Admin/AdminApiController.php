<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Auth;

class AdminApiController extends Controller
{
    public function getUsers()
    {
       
        $users = User::where("role","!=","admin")->paginate(10);



        return response([ 'status' => true, "users" => $users ], 200);
    }

    public function getUserById(Request $request,$id)
    {
        $requestData = $request->all();
        $user = User::where("id",$id)->first();

        return response([ 'status' => true, "user" => $user ], 200);

    }
    public function AddEditUser(Request $request)
    {
        $requestData = $request->all();


        

        if(isset($requestData["id"]) && $requestData["id"] !=""){
            return $this->UpdateUser($request);
        }


        $validate_data = [
            'name' => 'required|max:55',
            'email' => 'email|required|unique:users',
            'password' => 'required|min:6',
            'confirm_password' => 'required_with:password|same:password'
        ];




        $validator = Validator::make($requestData,$validate_data);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }
        

        $data = array();
        $data["name"] = $requestData["name"];
        $data["email"] = $requestData["email"];
        $data["password"] = Hash::make($requestData['password']);

        if($requestData["role"] == "manager"){
            $data["role"] = "manager";
        }else{
            $data["role"] = "user";
        }



        $user = User::create($data);

        return response([ 'status' => true, 'message' => 'User successfully register.' ], 200);
    }
    public function UpdateUser($request)
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
        };

        $user = User::find($requestData["id"]);

        $user->name = $request->name;
        if($requestData["password"] != ""){
             $user->password = $requestData["password"];
        }
        if($requestData["role"] == "manager"){
            $user->role = "manager";
        }else{
            $user->role = "user";
        }

        $user->save();

        return response([ 'status' => true, 'message' => 'User successfully update.' ], 200);

    }
}
