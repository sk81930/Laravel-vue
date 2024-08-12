<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\Tasks;
use App\Models\TaskComments;
use App\Models\Attachments;

use Auth;

class TaskCommentsApiController extends Controller
{
    public function getComments(Request $request,$id)
    {

        $comments = new TaskComments;

        $comments = $comments->where("taskid",$id);

        $comments = $comments->orderby("id","DESC");

        $comments = $comments->with("created_by_user")->with("attachments_data")->paginate(2);

        return response([ 'status' => true, "comments" => $comments ], 200);

        
    }
    public function AddComment(Request $request)
    {
        $requestData = $request->all();

        $validate_data = [
            'comment' => 'required',
        ];

        $validator = Validator::make($requestData,$validate_data);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }



        
        $data = array();
        $data["taskid"] = $requestData["taskid"];
        $data["comment"] = $requestData["comment"];

        $data["created_by"] = Auth::user()->id;

        $taskdata = TaskComments::create($data);

        if(isset($taskdata->id)){

            
            if(isset($requestData["files"]) && !empty($requestData["files"])){

                $path = "/attachments/task/";

                $ids = Attachments::multipleUpload($requestData["files"],$path);

                if(!empty($ids)){
                    $taskdata->attachments = $ids;
                    $taskdata->save();
                    
                }
            }

            return response([ 'status' => true, 'message' => 'Comment successfully add.' ], 200);

        }else{
            return response()->json([
                'error' => "Comment created error!"
            ], 422);
        }

        
    }
}
