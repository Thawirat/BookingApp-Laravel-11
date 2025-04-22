<?php

namespace App\Http\Controllers;

class UsageController extends Controller
{
    public function index()
    {
        return view('usage'); // เรียก view usage.blade.php
    }
}
