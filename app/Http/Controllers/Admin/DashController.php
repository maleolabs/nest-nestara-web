<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\DomainServices\JenisPerizinanService;
use App\Services\DomainServices\UserService;
use Illuminate\Http\Request;

class DashController extends Controller
{

    protected UserService $userService;

    protected JenisPerizinanService $jenisPerizinanService;

    public function __construct(
        UserService $userService,
        JenisPerizinanService $jenisPerizinanService)
    {
        $this->userService = $userService;
        $this->jenisPerizinanService = $jenisPerizinanService;
    }
    
    public function index()
    {
        return response()->view('admin.index', [
            'users' => $this->userService->getAll(),
            'jenis_perizinan' => $this->jenisPerizinanService->getPaginateAll()
        ]);
    }

}