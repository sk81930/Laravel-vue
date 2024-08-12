<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Staudenmeir\EloquentJsonRelations\HasJsonRelationships;

use App\Models\Attachments;

class TaskComments extends Model
{
    use HasFactory, HasJsonRelationships;

    protected $casts = [
        'attachments' => 'array',
    ];

    protected $guarded = []; 

    public function created_by_user()
    {
        return $this->belongsTo('App\Models\User',"created_by","id");
    }
    public function attachments_data()
    {
       return $this->belongsToJson(Attachments::class, 'attachments');
       
    }
}
