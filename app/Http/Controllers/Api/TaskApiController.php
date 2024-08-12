<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\Tasks;

use Auth;

class TaskApiController extends Controller
{
     public function getTasks()
    {
        $tasks = new Tasks;

        if(Auth::user()->role == "manager"){
          $tasks = $tasks->where("created_by",auth::user()->id);
        }
       
        $tasks = $tasks->with("created_by_user")->with("project_user")->with("assign_to_user")->with("observer_users")->paginate(10);


        return response([ 'status' => true, "tasks" => $tasks ], 200);
    }
    public function getTaskById(Request $request,$id)
    {
        $requestData = $request->all();
        $task = new Tasks;

        if(Auth::user()->role == "manager"){
          $task = $task->where("created_by",auth::user()->id);
        }
       
        $task = $task->with("created_by_user")->with("project_user")->with("assign_to_user")->with("observer_users");

        $task = $task->where("id",$id)->first();

        return response([ 'status' => true, "task" => $task ], 200);

    }
    public function AddEditTask(Request $request)
    {
        $requestData = $request->all();


      

        if(isset($requestData["id"]) && $requestData["id"] !=""){
            return $this->UpdateTask($request);
        }


        $validate_data = [
            'title' => 'required',
            'assign_to' => 'required',
            'priority' => 'required',
        ];

        $validator = Validator::make($requestData,$validate_data);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }
        

        $data = array();
        $data["title"] = $requestData["title"];
        $data["description"] = $requestData["description"];
        $data["project"] = $requestData["project"];
        $data["assign_to"] = $requestData["assign_to"];

        if(isset($requestData["observer"]) && !empty($requestData["observer"])){
            $observer = array();

            foreach ($requestData["observer"] as $observer_val) {
                $observer[] = $observer_val["value"];
            }

            $data["observer"] = $observer;

        }

        $data["priority"] = $requestData["priority"];


        $data["created_by"] = Auth::user()->id;

        Tasks::create($data);

        return response([ 'status' => true, 'message' => 'Task successfully add.' ], 200);
    }
    public function UpdateTask($request)
    {
        $requestData = $request->all();

        

        $validate_data = [
            'title' => 'required',
            'assign_to' => 'required',
            'priority' => 'required',
        ];

        $validator = Validator::make($requestData,$validate_data);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $task = Tasks::find($requestData["id"]);

        $task->title = $request->title;
        $task->description = $request->description;
        $task->project = $request->project;
        $task->assign_to = $request->assign_to;
        if(isset($requestData["observer"]) && !empty($requestData["observer"])){
            $observer = array();

            foreach ($requestData["observer"] as $observer_val) {
                $observer[] = $observer_val["value"];
            }

            $task->observer = $observer;

        }
        $task->priority = $request->priority;

        
        $task->save();

        return response([ 'status' => true, 'message' => 'Task Details successfully update.' ], 200);

    }
}
