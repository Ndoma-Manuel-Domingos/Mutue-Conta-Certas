<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    
    public function dashboard(Request $request)
    {
        $data = [];
       
        return Inertia::render('Dashboard', $data);
    }


}
