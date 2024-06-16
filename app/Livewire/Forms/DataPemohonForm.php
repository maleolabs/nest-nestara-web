<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class DataPemohonForm extends Form
{
    
    #[Validate('required|string|max:8')]
    public $sumber_identitas;
    
    #[Validate('required|string|max:16')]
    public $nomor_id;
    
    #[Validate('required|string|max:100')]
    public $nama_pemohon;
        
    #[Validate('required|max:15')]
    public $no_telepon;
        
    #[Validate('required|numeric')]
    public $provinsi;
        
    #[Validate('required|numeric')]
    public $kabupaten;
        
    #[Validate('required|numeric')]
    public $kecamatan;
        
    #[Validate('required|numeric')]
    public $kelurahan;
        
    #[Validate('required|max:150')]
    public $detail_alamat;

    public $alamat_luar_negeri;

}