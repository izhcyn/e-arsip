<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Draft extends Model
{
    protected $fillable = [
        'user_id', 'tanggal', 'no_surat', 'indeks', 'perihal', 'kepada',
        'alamat', 'isi_surat', 'penulis', 'jabatan', 'notes', 'template_surat',
        'signature', 'file_lampiran', 'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

