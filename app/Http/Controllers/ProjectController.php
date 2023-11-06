<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProjectController extends Controller
{
    public function index(){
        $projects = Project::with('cards.tasks')->get();

        return response()->json(['projects' => $projects]);
    }

    public function get_by_id($id){
        $project = Project::where('id', $id)->with('cards.tasks')->first();
        // $cards = Card::whereHas('project', function ($query) use($id) {
        //     $query->where('id', $id);
        // })->get();

        return response()->json(['project' => $project]);
    }

    public function create(Request $request){

        $valid = request()->validate([
            'name' => ['required', 'string']
        ]);

        Project::create([
            'name' => $request->name
        ]);

        return response()->json(['message' => true]);
    }

    public function update(Request $request, $id){

        $valid = request()->validate([
            'name' => ['required', 'string']
        ]);

        Project::where('id', $id)->update([
            'name' => $request->name
        ]);

        return response()->json(['message' => true]);
    }

    public function destroy($id){
        Project::destroy($id);

        return response()->json(['message' => true]);
    }
}
