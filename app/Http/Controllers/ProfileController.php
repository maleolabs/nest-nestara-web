<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    
    public function admin()
    {
        return response()->view('profile.admin');
    }

    public function pengelola_layanan()
    {
        return response()->view('profile.pengelola_layanan');
    }

    public function pegawai()
    {
        return response()->view('profile.pegawai');
    }

    public function pemohon()
    {
        return response()->view('profile.pemohon');
    }
    
}