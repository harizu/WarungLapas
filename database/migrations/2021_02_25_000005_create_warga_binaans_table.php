<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWargaBinaansTable extends Migration
{
    public function up()
    {
        Schema::create('warga_binaans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nomor_registrasi')->unique();
            $table->string('nama_warga_binaan');
            $table->string('kasus')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
