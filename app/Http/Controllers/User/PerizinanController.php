<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\JenisPerizinan;
use Illuminate\Http\Request;

class PerizinanController extends Controller
{
    
    public function jenisPerizinan()
    {
        return response()->view('user.jenis-perizinan', [
            'all_perizinan' => JenisPerizinan::paginate(20)
        ]);
    }

}