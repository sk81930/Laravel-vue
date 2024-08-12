<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Projects extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'client',
        'access_details',
        'created_by',
        'status',
    ];

    public function created_by()
    {
        return $this->belongsTo('App\Models\User',"created_by","id");
    }
}
