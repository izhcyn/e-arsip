<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // database/migrations/xxxx_xx_xx_create_surats_table.php
public function up()
{
    Schema::create('surats', function (Blueprint $table) {
        $table->id();
        $table->date('tanggal');
        $table->string('no_surat');
        $table->string('indeks');
        $table->string('perihal');
        $table->text('lampiran')->nullable();
        $table->string('kepada');
        $table->string('alamat');
        $table->text('isi_surat');
        $table->string('penulis');
        $table->string('jabatan');
        $table->text('notes')->nullable();
        $table->string('template_surat')->nullable();
        $table->string('signature')->nullable();
        $table->timestamps();
    });
}

};
