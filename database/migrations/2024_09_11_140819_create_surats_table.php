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
        if (!Schema::hasTable('surats')) {
            Schema::create('surats', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->date('tanggal');
                $table->string('no_surat', 255);
                $table->string('indeks', 255);
                $table->string('perihal', 255);
                $table->text('lampiran')->nullable();
                $table->string('kepada', 255);
                $table->string('alamat', 255);
                $table->text('isi_surat');
                $table->string('penulis', 255);
                $table->string('jabatan', 255);
                $table->text('notes')->nullable();
                $table->string('template_surat', 255)->nullable();
                $table->string('signature', 255)->nullable();
                $table->string('file_lampiran', 255)->nullable();
                $table->timestamps();
            });
        }
    }


};
