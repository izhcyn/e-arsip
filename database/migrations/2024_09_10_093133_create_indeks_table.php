<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIndeksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('indeks', function (Blueprint $table) {
            $table->bigIncrements('indeks_id');  // BIGINT AUTO_INCREMENT
            $table->string('kode_indeks', 50);   // VARCHAR(50)
            $table->string('kode_surat', 50);    // VARCHAR(50)
            $table->string('judul_indeks', 255); // VARCHAR(255)
            $table->string('last_number', 50); // VARCHAR(255) NULL

            $table->collation = 'utf8mb4_general_ci'; // Setting collation as per the screenshot
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('indeks');
    }
}
