<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\ImageTarget;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Video;

class AdminController extends Controller
{
    public function index(){
        return view('home');
    }

}
