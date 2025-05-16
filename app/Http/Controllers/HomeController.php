<?php

// app/Http/Controllers/HomeController.php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    public function index()
    {
        return view('welcome');  // Return the default Laravel welcome view
    }
}

