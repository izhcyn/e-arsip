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
            $table->integer('last_number')->default(0); // Menambahkan kolom last_number dengan nilai default 0
        });
    }

    public function down()
    {
        Schema::table('indeks', function (Blueprint $table) {
            $table->dropColumn('last_number');
        });
    }

};
