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
        Schema::table('surat_masuk', function (Blueprint $table) {
            $table->string('dokumen')->nullable(); // Modify as needed based on file upload requirements
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('surat_masuk', function (Blueprint $table) {
            $table->dropColumn('dokumen');
        });
    }

};
