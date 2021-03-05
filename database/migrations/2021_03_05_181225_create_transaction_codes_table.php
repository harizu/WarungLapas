<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction_codes', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_type', 50);
            $table->date('transaction_date');
            $table->unsignedInteger('next_number')->default(1);
            $table->timestamps();
            $table->unique(['transaction_type', 'transaction_date']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transaction_codes');
    }
}
