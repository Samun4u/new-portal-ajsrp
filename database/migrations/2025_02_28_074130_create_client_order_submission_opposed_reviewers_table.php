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
        Schema::create('client_order_submission_opposed_reviewers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_order_submission_id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('affiliation');
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
        Schema::dropIfExists('client_order_submission_opposed_reviewers');
    }
};
