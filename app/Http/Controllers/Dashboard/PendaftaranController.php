<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PendaftaranController extends Controller
{

    public function index()
    {
        return response()->view('dashboard.pendaftaran.index');
    }

}