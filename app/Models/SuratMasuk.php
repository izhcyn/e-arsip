<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratMasuk extends Model
{
    use HasFactory;

    protected $table = 'surat_masuk';

    protected $fillable = [
        'no_surat',
        'kode_indeks',
        'asal_surat',
        'perihal',
        'penerima',
        'tanggal_diterima',
        'dokumen',
        'visibility'
    ];
}
