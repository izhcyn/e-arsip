<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Indeks extends Model
{
    // Tentukan primary key sebagai 'indeks_id'
    protected $primaryKey = 'indeks_id';

    // Jika primary key adalah integer dan auto-increment
    public $incrementing = true;

    // Jika tipe primary key adalah integer
    protected $keyType = 'int';

    public $timestamps = false; // Jika tidak menggunakan timestamps

    protected $fillable = [
        'kode_indeks',
        'kode_surat',
        'judul_indeks',
        'detail_indeks',
    ];
}
