<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tbl_detail_jual', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('jual_id');
            $table->unsignedBigInteger('jenis_ikan');
            $table->double('harga_jual');
            $table->double('jml_ikan');
            $table->double('total');
            $table->timestamps();

            $table->foreign('jual_id')->references('jual_id')->on('tbl_jual')->onDelete('cascade');
            $table->foreign('jenis_ikan')->references('id')->on('tbl_ikan')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('tbl_detail_jual');
    }
};
