<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\DomainServices\JenisPerizinanService;
use Illuminate\Http\Request;

class JenisPerizinanController extends Controller
{

    protected JenisPerizinanService $jenisPerizinanService;

    public function __construct(JenisPerizinanService $jenisPerizinanService)
    {
        $this->jenisPerizinanService = $jenisPerizinanService;
    }
    
    public function index()
    {
        return response()->view('admin.perizinan.index');
    }

    public function loadDataPerizinan(Request $request)
    {
        $filter = $request->input('filter', 'semua');
        $page = $request->input('page', 1);
        $perPage = $filter == 'semua' ? $request->input('count', 20) : 180;

        $data = $this->jenisPerizinanService->getPaginateAll($page, $perPage);
        $idsAvailabele = $this->jenisPerizinanService->getIdsWhereTemplateExists();
        
        $data_html = [];
        foreach ($data as $item) {
            $available = in_array($item->id, $idsAvailabele);

            if ($filter == 'tersedia' && !$available) continue;
            else if ($filter == 'tidak_tersedia' && $available) continue;

            $avTxt = $available ? 'text-primary' : 'text-danger';
            $avBg = $available ? 'bg-app-primary-light': 'bg-danger-light';
            $avMs = $available ? 'tersedia' : 'tidak tersedia';

            $id = encrypt_id($item->id);
            $show_url = route('admin.jenis-perizinan.show', ['id' => $id]);
            $data_html[] = <<<HTML
                <div class="col-md-3 col-sm-4 mb-4 d-flex align-items-stretch">
                    <div class="card mb-0 min-vh-30 w-100">
                        <div class="card-body">
                            <h6 class="mb-1 text-bold">
                                <a href="{$show_url}" class="text-decoration-underline">{$item->nama}</a>
                            </h6>
                            <div class="d-flex gap-2 align-items-center flex-wrap">
                                <span class="badge bg-light border rounded-pill text-dark text-capitalize">{$item->kelompok_izin}</span>
                                <span class="badge bg-light border rounded-pill text-dark text-capitalize">{$item->masa_berlaku}</span>
                                <span class="badge bg-light border rounded-pill text-dark text-capitalize">{$item->durasi}</span>
                                <span class="badge {$avBg} border rounded-pill {$avTxt} text-capitalize">{$avMs}</span>
                            </div>
                        </div>
                    </div>
                </div>
            HTML;
        }

        return response()->json($data_html);
    }
    
}