<?php

namespace App\Http\Controllers\Layanan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    
    public function index()
    {
        return response()->view('layanan.index');
    }

    public function pendaftaran()
    {
        return response()->view('layanan.pendaftaran');
    }

    public function penyerahan()
    {
        return response()->view('layanan.penyerahan');
    }

    public function pendaftaranCreate(Request $request)
    {
        $type =str_capitalize(str_replace('-', ' ', $request->input('type')));
        return response()->view('layanan.pendaftaran-create', [
            'type' => $type
        ]);
    }
    
}