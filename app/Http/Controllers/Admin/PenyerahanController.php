<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PenyerahanController extends Controller
{

    public function index()
    {
        return response()->view('admin.penyerahan.index');
    }

}