<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToDraftsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('drafts', function (Blueprint $table) {
            $table->enum('status', ['draft', 'sent'])->default('draft')->after('file_lampiran');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('drafts', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}
