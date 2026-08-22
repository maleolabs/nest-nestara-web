<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\DomainServices\JenisPerizinanService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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

            if ($available) {
                $avBadge = '<span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-1 text-[11px] font-bold text-emerald-700 ring-1 ring-emerald-200"><span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Tersedia</span>';
            } else {
                $avBadge = '<span class="inline-flex items-center gap-1.5 rounded-full bg-red-50 px-2.5 py-1 text-[11px] font-bold text-red-600 ring-1 ring-red-200"><span class="h-1.5 w-1.5 rounded-full bg-red-400"></span> Tidak tersedia</span>';
            }

            $id = encrypt_id($item->id);
            $show_url = route('admin.jenis-perizinan.show', ['id' => $id]);

            $nama = Str::limit(str_capitalize($item->nama, true), 50);

            $data_html[] = <<<HTML
                <a href="{$show_url}" class="group flex flex-col rounded-2xl bg-white p-4 ring-1 ring-ink-200 hover:ring-nest-300 hover:shadow-[0_12px_32px_rgba(15,23,42,0.10)] transition">
                    <div class="flex items-start justify-between gap-2">
                        <div class="h-9 w-9 rounded-xl bg-nest-50 text-nest-700 grid place-items-center ring-1 ring-nest-200 group-hover:bg-nest-700 group-hover:text-white transition">
                            <i class="fa-solid fa-file-shield text-sm"></i>
                        </div>
                        {$avBadge}
                    </div>
                    <h6 class="mt-3 font-bold text-sm leading-snug text-ink-900 line-clamp-2">{$nama}</h6>
                    <div class="mt-2 flex flex-wrap gap-1.5">
                        <span class="rounded-full bg-ink-50 px-2.5 py-1 text-[11px] font-semibold text-ink-600 ring-1 ring-ink-200">{$item->kelompok_izin}</span>
                        <span class="rounded-full bg-ink-50 px-2.5 py-1 text-[11px] font-semibold text-ink-600 ring-1 ring-ink-200">Berlaku {$item->masa_berlaku}</span>
                        <span class="rounded-full bg-ink-50 px-2.5 py-1 text-[11px] font-semibold text-ink-600 ring-1 ring-ink-200">Durasi {$item->durasi}</span>
                    </div>
                    <span class="mt-3 inline-flex items-center gap-1.5 text-xs font-bold text-nest-700">Kelola <i class="fa-solid fa-arrow-right text-[10px] group-hover:translate-x-0.5 transition"></i></span>
                </a>
            HTML;
        }

        return response()->json($data_html);
    }

    public function searchJenisPerizinan(Request $request)
    {
        $q = $request->input('q', '');
        $result = $this->jenisPerizinanService->searchByName($q, 10);
        $idsAvailabele = $this->jenisPerizinanService->getIdsWhereTemplateExists();

        $data = [];

        foreach ($result as $item) {
            $id = encrypt_id($item->id);
            $show_url = route('admin.jenis-perizinan.show', ['id' => $id]);
            $nama = Str::limit(str_capitalize($item->nama, true), 50);

            $available = in_array($item->id, $idsAvailabele);

            if ($available) {
                $avBadge = '<span class="ml-auto inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2 py-0.5 text-[10px] font-bold text-emerald-700 ring-1 ring-emerald-200">Tersedia</span>';
            } else {
                $avBadge = '<span class="ml-auto inline-flex items-center gap-1 rounded-full bg-red-50 px-2 py-0.5 text-[10px] font-bold text-red-600 ring-1 ring-red-200">Tidak tersedia</span>';
            }

            $data[] = <<<HTML
                <a href="{$show_url}" class="group flex items-center gap-3 px-4 py-3 border-b border-ink-100 last:border-b-0 hover:bg-nest-50 transition">
                    <span class="h-8 w-8 shrink-0 rounded-lg bg-nest-50 text-nest-700 grid place-items-center ring-1 ring-nest-200 group-hover:bg-nest-700 group-hover:text-white transition">
                        <i class="fa-solid fa-file-shield text-xs"></i>
                    </span>
                    <span class="min-w-0 flex-1">
                        <span class="block text-sm font-bold text-ink-900 truncate">{$nama}</span>
                        <span class="block text-[11px] font-semibold text-ink-500 truncate">{$item->kelompok_izin}</span>
                    </span>
                    {$avBadge}
                </a>
            HTML;
        }

        return response()->json($data);
    }
    
}