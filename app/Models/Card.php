<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Card extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['name', 'project_id'];

    public function project(){
        return $this->belongsTo(Project::class);
    }

    public function tasks(){
        return $this->hasMany(Task::class);
    }
}
