<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Draft extends Model
{
    use HasFactory;

    // Tentukan tabel yang digunakan oleh model
    protected $table = 'drafts';

    // Tentukan kolom yang dapat diisi (fillable)
    protected $fillable = [
        'tanggal', 'no_surat', 'indeks', 'perihal', 'lampiran', 'kepada',
        'alamat', 'isi_surat', 'penulis', 'jabatan', 'notes', 'template_surat',
        'signature', 'file_lampiran', 'status'
    ];
}
