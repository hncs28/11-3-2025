<?php

namespace App\Http\Controllers\CMS;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\activities;

class CMSActivitiesController extends Controller
{
    
    public function index() 
    {
        $act = activities::all();
        return response()->json(data: $act);
    }

    public function find($actID) {
        $act = activities::findOrFail($actID);
        return response()->json($act);
    }
  

    public function post(Request $request)
    {
        
        $act = new activities;
        $act->actName = $request->actName; 
        $act->actImg = $request->actImg;
        
        $act->save();
        $activity = DB::table('activities')->select('*')->get();
        return response()->json($activity);
    }

    public function delete($actID)
    {
        $activity = activities::find($actID);
        if ($activity) 
        {
            $activity->delete();
            return response()->json(["message"=>"Delete successfully"]);
        }
        else 
        {
            return response()->json(["error"=>"Cannot delete"]);
        }
    }
    public function update(Request $request, $actID)
    {
        $act = activities::find($actID);
        $act->actName = $request->actName; 
        $act->actImg = $request->actImg;
        $act->save();
        $activity = DB::table('activities')->select('*')->get();
        return response()->json($activity);
    }
}
