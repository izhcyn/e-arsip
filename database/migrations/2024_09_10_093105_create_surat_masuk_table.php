<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuratMasukTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('surat_masuk', function (Blueprint $table) {
            $table->bigIncrements('suratmasuk_id');
            $table->string('no_surat', 255)->nullable();
            $table->string('kode_indeks', 50)->nullable();
            $table->string('asal_surat', 255)->nullable();
            $table->string('perihal', 255)->nullable();
            $table->string('penerima', 255)->nullable();
            $table->date('tanggal_diterima')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->string('visibility', 255)->default('public');

            // Optional: Add indexes
            $table->index('no_surat');
            $table->index('kode_indeks');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('surat_masuk');
    }
}
