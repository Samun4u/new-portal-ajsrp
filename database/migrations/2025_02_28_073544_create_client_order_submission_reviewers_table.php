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
        Schema::create('client_order_submission_reviewers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_order_submission_id');
            $table->string('referred_article_title')->nullable();
            $table->string('corresponding_author_first_name')->nullable();
            $table->string('corresponding_author_last_name')->nullable();
            $table->string('corresponding_author_email')->nullable();
            $table->string('first_author_first_name')->nullable();
            $table->string('first_author_last_name')->nullable();
            $table->string('first_author_email')->nullable();
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
        Schema::dropIfExists('client_order_submission_reviewers');
    }
};
