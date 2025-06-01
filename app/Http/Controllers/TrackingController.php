<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TrackingController extends Controller
{
    public function index(Request $request)
    {   
        $mindUrl = $request->query('mind_url');
        $videoUrl = $request->query('video_url');
        // Logic to display tracking information
        return view('ar_tracking.index',compact('mindUrl', 'videoUrl'));
    }
}
