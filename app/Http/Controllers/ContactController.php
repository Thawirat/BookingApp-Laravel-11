<?php

namespace App\Http\Controllers;

class ContactController extends Controller
{
    public function index()
    {
        return view('contact'); // เรียก view contact.blade.php
    }
}
