<?php

namespace App\Services\DomainServices;

use App\Models\Izin;

interface PerizinanService
{
    
    function saveRegistration(int $jenis_izin_id, int $pemohon_id): ?Izin;

    function find(int $id): ?Izin;

    /**
     * get all with some filters [optional]
     * 
     * @param string $search
     * default empty [all]
     * 
     * @param array $filter_status
     * default empty [all]
     * 
     * @param array $filter_status_progress
     * default empty [all]
     * 
     * @param string $filter_start_date
     * default sub month from today
     * 
     * @param string $filter_end_date
     * default today
     * 
     * @param string $sort
     * default DESC
     * 
     * @return array
     */
    function getAll(
        string $search = '', 
        array $filter_status = [], 
        array $filter_status_progress = [], 
        string $filter_start_date = null, 
        string $filter_end_date = null, 
        string $sort = 'DESC'
    ): array;

    function saveRegistrationAction(int $izin_id, string $tanggal_peninjauan, string $lokasi_izin, string $keterangan = null, bool $selesai = false, int $pegawai_id = null): bool;

    function rollbackRegistration(int $izin_id, string $keterangan, int $pegawai_id): void;

    function saveRegistrationRevision(int $izin_id, string $keterangan = null): void;

    function cancelRegistration(int $zin_id, string $keterangan = null): void;

    function updateIzinStatusThatHaveBeenReviewed(): void;

    function updateAllIzinStatusWhenEntriDataHasChanged(): void;

    function updateIzinStatusWhenEntriDataHasChanged(int $izin_id): void;
    
}