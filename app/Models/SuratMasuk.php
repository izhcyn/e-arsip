<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratMasuk extends Model
{
    use HasFactory;

    // Menentukan nama tabel
    protected $table = 'surat_masuk';

    // Menentukan primary key jika bukan 'id'
    protected $primaryKey = 'suratmasuk_id';

    // Jika timestamps sudah ada (created_at dan updated_at)
    public $timestamps = true;

    // Kolom-kolom yang bisa diisi
    protected $fillable = [
        'no_surat',
        'kode_indeks',
        'asal_surat',
        'perihal',
        'penerima',
        'tanggal_diterima',
        'dokumen',
        'visibility',
    ];
}
