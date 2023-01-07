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
        Schema::create('drug_alert', function (Blueprint $table) {
            $table->id();
            $table->text('drug_name');
            $table->integer('reorder_point')->unsigned();
            $table->integer('safe_stock')->unsigned();
            $table->integer('empty_stock')->unsigned();
            $table->string('description')->nullable();
            $table->enum('status',['N','Y'])->default('N');
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
        Schema::dropIfExists('drug_alert');
    }
};
