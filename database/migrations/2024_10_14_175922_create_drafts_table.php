<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        if (!Schema::hasTable('drafts')) {
            Schema::create('drafts', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('user_id')->nullable(); // Jika ada user_id, tambahkan relasi
                $table->date('tanggal')->nullable(); // Boleh kosong
                $table->string('no_surat', 255)->nullable(); // Boleh kosong
                $table->string('indeks', 255)->nullable(); // Boleh kosong
                $table->string('perihal', 255)->nullable(); // Boleh kosong
                $table->text('lampiran')->nullable(); // Sudah nullable
                $table->string('kepada', 255)->nullable(); // Boleh kosong
                $table->string('alamat', 255)->nullable(); // Boleh kosong
                $table->text('isi_surat')->nullable(); // Boleh kosong
                $table->string('penulis', 255)->nullable(); // Boleh kosong
                $table->string('jabatan', 255)->nullable(); // Boleh kosong
                $table->text('notes')->nullable(); // Sudah nullable
                $table->string('template_surat', 255)->nullable(); // Sudah nullable
                $table->string('signature', 255)->nullable(); // Sudah nullable
                $table->string('file_lampiran', 255)->nullable(); // Sudah nullable
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('drafts');
    }
};
