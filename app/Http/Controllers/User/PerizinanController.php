<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\JenisPerizinan;
use App\Services\APIClient\dto\OcrRequest;
use App\Services\APIClient\OcrAPIService;
use App\Services\AppService\EncryptionService;
use Illuminate\Http\Request;

class PerizinanController extends Controller
{

    protected EncryptionService $encryptionService;
    protected OcrAPIService $ocrService;

    public function __construct(EncryptionService $encryptionService, OcrAPIService $ocrService)
    {
        $this->encryptionService = $encryptionService;
        $this->ocrService = $ocrService;
    }
    
    public function jenisPerizinan()
    {
        $request = OcrRequest::builder()
                                ->setIzinId(10)
                                ->setJenisPerizinanId(167)
                                ->setSyaratIzinId(855)
                                ->setImageUrl("http://sistem-informasi-dinas-pmptsp.me/api/files/MTcyNTQzMzA1MDg1NS10ZXN0LmpwZw==")
                                ->build();
        $this->ocrService->sendOcrRequest($request);
        return response()->view('user.jenis-perizinan', [
            'all_perizinan' => JenisPerizinan::paginate(20)
        ]);
    }

    public function create()
    {
        return response()->view('user.create-izin');
    }

    public function semuaIzin()
    {
        return response()->view('user.izin-saya');
    }

    public function revisiIzin(Request $request)
    {
        $izin_id = $this->encryptionService->decryptId($request->route('izin_id'));
        return response()->view('user.revisi-izin', [
            'izin_id' => $izin_id
        ]);
    }

}