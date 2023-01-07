<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('drug_inv_s', function (Blueprint $table) {
            $table->id();
            $table->string('drug_id');
            $table->string('drug_name');
            $table->string('lot_no');
            $table->integer('qty')->unsigned();
            $table->date('exp_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('drug_inv_s');
    }
};
