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
        Schema::create('drug_hosxp', function (Blueprint $table) {
            $table->id();
            $table->string('icode');
            $table->string('drug_name');
            $table->string('unit');
            $table->string('dosage_from')->nullable();
            $table->string('did');
            $table->string('tpu_codelist');
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
        Schema::dropIfExists('drug_hosxp');
    }
};
