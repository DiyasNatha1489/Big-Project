<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WaliKelasController extends Controller
{
    public function dashboard()
    {
        return view('wali-kelas.dashboard');
    }
}