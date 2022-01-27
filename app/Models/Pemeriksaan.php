<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemeriksaan extends Model
{
    use HasFactory;

    protected $table = 'pemeriksaan';

    protected $fillable = [
        'kode', 'no_sep', 'no_bpjs', 'faskes_id', 'pasien_id', 'kategori_pasien', 'tanggal', 'total_tagihan_layanan', 'total_tagihan_obat', 'status', 'no_rekam_medis'
    ];
}