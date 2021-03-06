<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePenjualansTable extends Migration
{
    public function up()
    {
        Schema::create('penjualans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('trx_no')->unique();
            $table->integer('qty');
            $table->string('total_price');
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
