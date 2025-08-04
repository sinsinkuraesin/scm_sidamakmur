<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('tbl_jual', function (Blueprint $table) {
            $table->enum('status', ['Diproses', 'Selesai'])->default('Diproses');
        });
    }

    public function down()
    {
        Schema::table('tbl_jual', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }

};
