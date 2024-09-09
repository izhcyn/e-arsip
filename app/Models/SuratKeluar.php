<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratKeluar extends Model
{
    use HasFactory;

    protected $table = 'surat_keluar';

    protected $primaryKey = 'suratkeluar_id';

    public $timestamps = true;

    protected $fillable = [
        'no_surat',
        'kode_indeks',
        'asal_surat',
        'perihal',
        'penulis',
        'penerima',
        'tanggal_keluar',
        'dokumen',
        'visibility'
    ];
}
