<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PemantauanController extends Controller
{
    
    public function index()
    {
        return response()->view('admin.pemantauan.index');
    }

}