<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LocationSessionController extends Controller
{
    public function index(Request $request)
    {
      // $location_name = $request->location_name;
     $value = Session::put('location_name',$request->location_name);
     if (Session::has('location_name')) {
         return 1;
     }
       

    }
}
