<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Staudenmeir\EloquentJsonRelations\HasJsonRelationships;


use App\Models\User;

class Tasks extends Model
{
    use HasFactory, HasJsonRelationships;

    protected $casts = [
        'observer' => 'array',
    ];

    protected $guarded = []; 

    public function created_by_user()
    {
        return $this->belongsTo('App\Models\User',"created_by","id");
    } 
    public function assign_to_user()
    {
        return $this->belongsTo('App\Models\User',"assign_to","id");
    } 
    public function project_user()
    {
        return $this->belongsTo('App\Models\Projects',"project","id");
    } 

    public function observer_users()
    {
       return $this->belongsToJson(User::class, 'observer');
       
    }
}
