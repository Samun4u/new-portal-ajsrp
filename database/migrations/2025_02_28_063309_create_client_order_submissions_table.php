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
        Schema::create('client_order_submissions', function (Blueprint $table) {
            $table->id();
            $table->string('client_order_id');
            $table->unsignedBigInteger('journal_id')->nullable();
            $table->unsignedBigInteger('article_type_id')->nullable();
            $table->string('article_title')->nullable();
            $table->longText('article_abstract')->nullable();
            $table->text('article_keywords')->nullable();
            $table->integer('full_article_file')->nullable();
            $table->integer('covert_letter_file')->nullable();
            $table->boolean('has_author')->nullable();
            $table->boolean('has_conflict_of_interest')->nullable();
            $table->text('conflict_details')->nullable();
            $table->boolean('has_funding')->nullable();
            $table->boolean('has_data_availability_statement')->nullable();
            $table->string('data_availability_statement')->nullable();
            $table->string('data_availability_url')->nullable();
            $table->boolean('add_reviewers')->nullable();
            $table->boolean('suggested_reviewers')->nullable();
            $table->boolean('has_opposed_reviewers')->nullable();
            $table->boolean('final_submit_success')->nullable();
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
        Schema::dropIfExists('client_order_submissions');
    }
};
