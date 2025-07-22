<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        Schema::create('tbl_ikan', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('kd_ikan');
            $table->string('jenis_ikan');
            $table->double('harga_beli');
            $table->double('harga_jual');
            $table->integer('stok');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tbl_ikan');
    }
};
