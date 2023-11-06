<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Time;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $valid = request()->validate([
            'text' => ['required', 'string'],
            'card_id' => ['required', 'numeric']
        ]);

        Task::create([
            'card_id' => $request->card_id,
            'text' => $request->text,
            'status' => 0,
        ]);
        return response()->json(['message' => true]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function completed(Request $request, $id)
    {
        $task = Task::where('id', $id)->update(['status' => 1]);

        // если записывалось время 
        if($task->time_status == 1){
            $task->time_status = 0;
            $task->save();

            Time::create([
                'task_id' => $id,
                'status' => 0,
            ]);
        }

        return response()->json(['message' => true]);
    }

    /**
     * Display the specified resource.
     */
    public function time(Request $request, $id)
    {
        $valid = request()->validate([
            'time_status' => ['required', 'boolean'],
        ]);

        Time::create([
            'task_id' => $id,
            'status' => $request->time_status,
        ]);

        $task = Task::where('id', $id)->update(['time_status' => $request->time_status]);

        return response()->json(['message' => true]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task, $id)
    {
        Task::destroy('id', $id);
        
        return response()->json(['message' => true]);
    }
}
