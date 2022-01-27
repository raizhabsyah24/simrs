<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use App\Repositories\Interfaces\DokterInterface;

class DokterRepository implements DokterInterface
{
    public function daftarPasienDokterSpesialis(int $poli_id)
    {
        return DB::table('periksa_dokter as pd')
            ->selectRaw('
            DISTINCT pd.id as periksa_dokter_id, pas.id as pasien_id,  poli.id as poli_id, d.id as dokter_id, pas.nama as nama_pasien, pas.jenis_kelamin, pd.status_diperiksa, pas.tanggal_lahir, pd.no_antrian_periksa,  d.nama as nama_dokter
        ')
            ->join('pemeriksaan_detail as pede', 'pede.id', '=', 'pd.pemeriksaan_detail_id')
            ->join('pasien as pas', 'pas.id', '=', 'pd.pasien_id')
            ->join('dokter as d', 'd.id', '=', 'pede.dokter_id')
            ->join('poli', 'poli.id', '=', 'pede.poli_id')
            ->join('dokter_poli as dp', 'dp.poli_id', '=', 'pd.poli_id')
            ->where('pede.poli_id', $poli_id)
            ->whereDate('pd.tanggal', now())
            ->orderBy('pd.no_antrian_periksa');
    }

    public function dokterSpesialis(int $dokter_id)
    {
        return DB::table('dokter_poli as dp')
            ->selectRaw('
            d.id as dokter_id, d.nama as nama_dokter, p.id as poli_id, p.nama as nama_poli,
            p.spesialis
        ')
            ->join('dokter as d', 'd.id', '=', 'dp.dokter_id')
            ->join('poli as p', 'p.id', '=', 'dp.poli_id')
            ->where('dp.dokter_id', $dokter_id)
            ->first();
    }

    public function rekamMedisPasienPeriksa(int $periksa_dokter_id)
    {
        return DB::table('periksa_dokter as pd')
            ->selectRaw('
            rmp.tanggal as tanggal_periksa, rmp.tujuan as poli, rmp.dokter, rmp.subjektif, rmp.objektif, rmp.assesment, rmp.plan, rmp.keterangan 
        ')
            ->join('pasien as p', 'p.id', '=', 'pd.pasien_id')
            ->join('rekam_medis as rm', 'rm.pasien_id', '=', 'pd.pasien_id')
            ->join('rekam_medis_pasien as rmp', 'rmp.rekam_medis_id', '=', 'rm.id')
            ->where('pd.id', $periksa_dokter_id)
            ->get();
    }

    public function indentitasPasien(int $pasien_id)
    {
        return DB::table('pasien as p')
            ->selectRaw('
            p.nama as nama_pasien, p.tanggal_lahir, p.jenis_kelamin, p.alamat, p.no_hp,
            kp.nama as kategori_pasien, rm.kode as no_rekam_medis, p.golongan_darah
        ')
            ->join('periksa_dokter as pd', 'pd.pasien_id', 'p.id')
            ->join('pemeriksaan_detail as pede', 'pede.id', 'pd.pemeriksaan_detail_id')
            ->join('pemeriksaan as pem', 'pem.id', 'pede.pemeriksaan_id')
            ->join('kategori_pasien as kp', 'kp.id', 'pem.kategori_pasien')
            ->join('rekam_medis as rm', 'rm.pasien_id', 'p.id')
            ->where('p.id', $pasien_id)
            ->first();
    }
}