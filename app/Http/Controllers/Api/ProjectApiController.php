<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\Projects;

use Auth;
class ProjectApiController extends Controller
{
    public function getProjects()
    {
       
        $projects = Projects::with("created_by")->paginate(10);



        return response([ 'status' => true, "projects" => $projects ], 200);
    }
    public function getAllProject()
    {
        $projects = new Projects;

        if(Auth::user()->role == "manager"){
          $projects = $projects->where("created_by",auth::user()->id);
        }
       
        $projects = $projects->with("created_by")->get();


        return response([ 'status' => true, "projects" => $projects ], 200);
    }

    public function getProjectById(Request $request,$id)
    {
        $requestData = $request->all();
        $project = Projects::with("created_by")->where("id",$id)->first();

        return response([ 'status' => true, "project" => $project ], 200);

    }
    public function AddEditProject(Request $request)
    {
        $requestData = $request->all();

      

        if(isset($requestData["id"]) && $requestData["id"] !=""){
            return $this->UpdateProject($request);
        }


        $validate_data = [
            'title' => 'required|max:55',
            'client' => 'required',
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
        $data["client"] = $requestData["client"];
        $data["access_details"] = $requestData["access_details"];
        $data["created_by"] = Auth::user()->id;


        $user = Projects::create($data);

        return response([ 'status' => true, 'message' => 'Project successfully add.' ], 200);
    }
    public function UpdateProject($request)
    {
        $requestData = $request->all();

        $validate_data = [
            'title' => 'required|max:55',
            'client' => 'required',
        ];


        $validator = Validator::make($requestData,$validate_data);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        };

        $project = Projects::find($requestData["id"]);

        $project->title = $request->title;
        $project->description = $request->description;
        $project->client = $request->client;
        $project->access_details = $request->access_details;
        $project->save();

        return response([ 'status' => true, 'message' => 'Project Details successfully update.' ], 200);

    }
}
