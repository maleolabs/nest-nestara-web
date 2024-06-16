<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Utils\AppString;
use Illuminate\Http\Request;

class PendaftaranController extends Controller
{

    public function index()
    {
        return response()->view('dashboard.pendaftaran.index');
    }

    public function create(Request $request)
    {
        $type = AppString::capitalize(str_replace('-', ' ', $request->input('type')));
        return response()->view('dashboard.pendaftaran.create', [
            'type' => $type
        ]);
    }

}