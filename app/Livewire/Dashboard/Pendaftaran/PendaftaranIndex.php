<?php

namespace App\Livewire\Dashboard\Pendaftaran;

use App\Models\Izin;
use App\Models\Pemohon;
use App\Services\APIClient\RegionAPIService;
use Illuminate\Support\Facades\DB;
use App\Services\DomainServices\PemohonService;
use App\Services\DomainServices\PerizinanService;
use App\Services\DomainServices\StatusIzinService;
use App\utils\enums\StatusIzinEnum;
use Carbon\Carbon;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class PendaftaranIndex extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    #[Url()]
    public $layout, $tab, $preview_izin_id, $preview_pemohon_id;
    public $menu;
    public $preview_data_izin = false;

    public $preview_pemohon = false;

    private const DATA_PEMOHON = 'Data Pemohon';
    private const DATA_IZIN_BARU = 'Permohonan Izin Baru';

    /** Paginator tidak boleh public — Livewire tidak bisa dehydrate. Dikirim via render(). */
    protected $semua_pemohon;
    protected $semua_izin;
    public $semua_perpanjangan = [],
        $semua_perubahan = [];
    public $search = '',
        $start_date,
        $end_date,
        $filter_status;

    protected array $tanggalPermohonanMap = [];

    public function mount()
    {
        $tabs = [
            self::DATA_PEMOHON,
            self::DATA_IZIN_BARU
        ];

        $defaultMenu = self::DATA_PEMOHON;
        if ($this->tab !== null) {
            $isTabValid = in_array($this->tab, $tabs);
            $selectedMenu = $isTabValid ? $this->tab : $defaultMenu;
        } else {
            $selectedMenu = $defaultMenu;
        }
        $this->selectMenu($selectedMenu, false);

        $this->filter_status = StatusIzinEnum::DIDAFTARKAN;
        
        if ($this->preview_izin_id) {
            $this->preview_data_izin = true;
        }

        if ($this->preview_pemohon_id) {
            $this->preview_pemohon = true;
        }

        $this->start_date = Carbon::today()->subMonth()->toDateString();
        $this->end_date = Carbon::today()->toDateString();
    }

    public function render()
    {
        $this->setDataList();
        $this->buildTanggalPermohonanMap();
        return view('livewire.dashboard.pendaftaran.pendaftaran-index', [
            'semua_izin' => $this->semua_izin,
            'semua_pemohon' => $this->semua_pemohon,
        ]);
    }

    /** reset ke halaman 1 saat filter berubah */
    public function updatedSearch() { $this->resetPage('page'); $this->resetPage('pPage'); }
    public function updatedStartDate() { $this->resetPage('page'); $this->resetPage('pPage'); }
    public function updatedEndDate() { $this->resetPage('page'); $this->resetPage('pPage'); }

    public function selectMenu($select_menu, $reset_url = true)
    {
        $this->menu = $select_menu;
        $this->dispatch('menu-selected', ['menu' => $select_menu]);
        $this->search = '';
        $this->preview_data_izin = false;
        $this->preview_izin_id = $reset_url ? null : $this->preview_izin_id;
        $this->preview_pemohon = false;
        $this->preview_pemohon_id = $reset_url ? null : $this->preview_pemohon_id;
    }

    private function setDataList()
    {
        $this->semua_pemohon = $this->getPemohonService()->getPaginated($this->search, 20, $this->getPage('pPage'), 'pPage');
        $this->semua_izin = $this->getPerizinanService()->get(
            $this->search,
            [StatusIzinEnum::DIDAFTARKAN, StatusIzinEnum::DITINJAU, StatusIzinEnum::REVISI_PERSYARATAN, StatusIzinEnum::BATAL],
            [],
            [],
            $this->start_date,
            $this->end_date,
            'DESC',
            20,
            'created_at',
            paginate: true,
            page: $this->getPage()
        );
    }

    /** Map [izin_id => tanggal didaftarkan] — 1 query ringan, tanpa hydrate model. */
    protected function buildTanggalPermohonanMap(): void
    {
        $ids = collect($this->semua_izin)->pluck('id')->all();
        $this->tanggalPermohonanMap = empty($ids)
            ? []
            : DB::table('status_izin')
                ->whereIn('izin_id', $ids)
                ->where('nama', StatusIzinEnum::DIDAFTARKAN)
                ->pluck('created_at', 'izin_id')
                ->all();
    }

    #[On('change-layout')]
    public function changeLayout($value)
    {
        $this->layout = $value;
    }

    #[On('show-semua-izin')]
    public function showSemuaIzin()
    {
        $this->selectMenu(self::DATA_IZIN_BARU);
    }

    protected function getAlamat($pemohon)
    {
        // terima model Pemohon (hindari query + region lookup per baris);
        // fallback id untuk kompatibilitas
        if (!$pemohon instanceof Pemohon) {
            $pemohon = $this->getPemohonService()->find($pemohon);
        }
        return "{$pemohon->detail_alamat}, " . $this->getRegionValue('kelurahan', $pemohon->kelurahan) . ', ' . $this->getRegionValue('kecamatan', $pemohon->kecamatan) . ', ' . $this->getRegionValue('kabupaten', $pemohon->kabupaten) . ', ' . $this->getRegionValue('provinsi', $pemohon->provinsi);
    }

    private function getRegionValue($type, $id): string
    {
        $value = '';
        switch ($type) {
            case 'provinsi':
                $value = $this->getRegionService()->getProvince($id);
                break;
            case 'kabupaten':
                $value = $this->getRegionService()->getRegency($id);
                break;
            case 'kecamatan':
                $value = $this->getRegionService()->getDistrict($id);
                break;
            case 'kelurahan':
                $value = $this->getRegionService()->getVillage($id);
                break;
        }
        return str_capitalize($value, true);
    }

    public function previewDataIzin($id)
    {
        $this->preview_data_izin = true;
        $this->preview_izin_id = $id;
    }

    public function previewPemohon($id)
    {
        $this->preview_pemohon = true;
        $this->preview_pemohon_id = $id;
    }

    protected function getTanggalPermohonan($izin): string
    {
        // 1) dari map ringan (render list)
        $raw = $this->tanggalPermohonanMap[$izin->id ?? $izin] ?? null;
        if ($raw) {
            return Carbon::parse($raw)->translatedFormat('l, d F Y');
        }

        // 2) fallback relasi eager / query tunggal
        if ($izin instanceof Izin) {
            $permohonan = $izin->didaftarkanStatus;
            return $permohonan ? $permohonan->created_at->translatedFormat('l, d F Y') : '-';
        }

        $permohonan = $this->getStatusIzinService()->findByNamaAndIzin(StatusIzinEnum::DIDAFTARKAN, $izin);
        return $permohonan ? $permohonan->created_at->translatedFormat('l, d F Y') : '-';
    }

    private function getPemohonService(): PemohonService
    {
        return app(PemohonService::class);
    }

    private function getPerizinanService(): PerizinanService
    {
        return app(PerizinanService::class);
    }

    private function getRegionService(): RegionAPIService
    {
        return app(RegionAPIService::class);
    }

    private function getStatusIzinService(): StatusIzinService
    {
        return app(StatusIzinService::class);
    }

}