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
        Schema::create('drug_export', function (Blueprint $table) {
            $table->id();
            $table->string('drug_general_id');
            $table->string('drug_name');
            $table->string('lot_no')->nullable();
            $table->integer('qty')->unsigned();
            $table->decimal('price',$precision = 8,$scale = 2)->unsigned();
            $table->string('discount_by');
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
        Schema::dropIfExists('drug_export');
    }
};
