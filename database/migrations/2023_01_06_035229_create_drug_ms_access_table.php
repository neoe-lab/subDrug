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
        Schema::create('drug_ms_access', function (Blueprint $table) {
            $table->id();
            $table->string('code1');
            $table->string('drug_name');
            $table->string('unit');
            $table->string('dosage_form')->nullable();
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
        Schema::dropIfExists('drug_ms_access');
    }
};
