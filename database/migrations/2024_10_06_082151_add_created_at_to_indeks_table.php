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
        Schema::table('indeks', function (Blueprint $table) {
            $table->timestamps(); // Ini akan menambahkan kolom created_at dan updated_at
        });
    }

    public function down()
    {
        Schema::table('indeks', function (Blueprint $table) {
            $table->dropTimestamps(); // Ini akan menghapus kolom created_at dan updated_at
        });
    }

};
