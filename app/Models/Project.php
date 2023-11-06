<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;

class Project extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['name'];

    protected $appends = ['time'];

    public function cards(){
        return $this->hasMany(Card::class);
    }

    public function getTimeAttribute(){
        $cards = Card::where('project_id', $this->id)->with('tasks')->get();

        $time = Carbon::createFromFormat('H:i:s', '00:00:00');
        foreach($cards as $card){
            foreach($card->tasks as $task){
                // получаем секунды
                $time_task = Carbon::createFromTimeString($task->time);
                $seconds = $time_task->diffInSeconds(Carbon::createFromTimeString('00:00:00'));
                // прибавляем
                $time->addSeconds($seconds);
            }
        }
        return Carbon::parse($time)->format('H:i:s');
    }
}
