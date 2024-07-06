<?php

namespace App\Http\Controllers\BackOffice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    
    public function index()
    {
        return response()->view('back-office.index');
    }

    public function pendataan()
    {
        return response()->view('back-office.pendataan');
    }

    public function timTeknis()
    {
        return response()->view('back-office.tim-teknis');
    }

    public function penetapan()
    {
        return response()->view('back-office.penetapan');
    }
    
}