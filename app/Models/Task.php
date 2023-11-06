<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;

class Task extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['text', 'status', 'card_id', 'time_status'];

    protected $appends = ['time'];

    public function getTimeAttribute(){
        $times = Time::where('task_id', $this->id)->get();

        $sum_time = 0;
        $first_time = 0; 
        foreach($times as $time){
            if($time->status == 1){
                $first_time = $time->created_at;
            }
            if($time->status == 0 && $first_time < $time->created_at){
                $sum_time += $time->created_at->diffInSeconds($first_time);
            }
        }
        return Carbon::parse($sum_time)->format('H:i:s');
    }
}
