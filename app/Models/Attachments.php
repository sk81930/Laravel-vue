<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\File;

class Attachments extends Model
{
    use HasFactory;
    protected $guarded = []; 

    public static function multipleUpload($files,$path){

        $ids = [];

        foreach ($files as $key => $file) {
            $ids[] = self::singleUpload($file,$path);
        }

        return $ids;

    }
    public static function singleUpload($file,$path){

        $full_path = public_path($path);
        
        File::isDirectory($full_path) or File::makeDirectory($full_path, 0777, true, true);

        $name = $file->getClientOriginalName();

        $type = $file->getClientOriginalExtension();

        $filename = time().'_'.$name;


        $filePath = $file->move($full_path, $filename);


        $data = array();

        $data["name"] = $name;
        $data["type"] = $type;
        $data["path"] = $path.$filename;

        $attachment_data = self::create($data);

        return $attachment_data->id;

    }
}
