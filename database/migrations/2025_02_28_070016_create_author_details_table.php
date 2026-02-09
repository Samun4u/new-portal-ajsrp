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
        Schema::create('author_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_order_submission_id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('orcid')->nullable();
            $table->string('nationality')->nullable();
            $table->string('whatsapp_number')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->json('affiliation');
            $table->boolean('corresponding_author')->default(false);
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
        Schema::dropIfExists('author_details');
    }
};
