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
        Schema::create('client_professional_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('title')->nullable();
            $table->string('title_spacify')->nullable();
            $table->string('highest_degree')->nullable();
            $table->string('diploma_or_certifiction_spacify')->nullable();
            $table->string('address')->nullable();
            $table->string('country')->nullable();
            $table->string('current_institution')->nullable();
            $table->text('professional_bio')->nullable();
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
        Schema::dropIfExists('client_professional_details');
    }
};
