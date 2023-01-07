<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rules\Enum;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('drug_general', function (Blueprint $table) {
            $table->id();
            $table->text('drug_name');
            $table->string('unit');
            $table->string('type')->nullable();
            $table->string('group')->nullable();
            $table->string('icode')->unique();
            $table->string('code1')->unique();
            $table->integer('packing')->unsigned();
            $table->string('ABC_analysis_type')->nullable();
            $table->string('VED_analysis_type')->nullable();
            $table->string('ed_list',1)->nullable();
            $table->enum('status',['N','Y'])->default('Y');
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
        Schema::dropIfExists('drug_general');
    }
};
