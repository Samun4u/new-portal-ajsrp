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
        Schema::create('editorial_board_applications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id')->nullable();
            $table->string('full_name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('country');
            $table->string('linkedin')->nullable();
            $table->string('degree');
            $table->string('specialization');
            $table->string('title');
            $table->string('institution');
            $table->integer('experience');
            $table->text('publications')->nullable();
            $table->unsignedBigInteger('supporting_doc_file_id')->nullable();
            $table->boolean('editorial_board_exp')->default(false);
            $table->text('editorial_details')->nullable();
            $table->boolean('peer_reviewer_exp')->default(false);
            $table->text('reviewer_details')->nullable();
            $table->json('interests');
            $table->string('other_interest')->nullable();
            $table->text('purpose');
            $table->unsignedBigInteger('cv_file_id')->nullable();
            $table->unsignedBigInteger('photo_file_id')->nullable();
            $table->boolean('acknowledgment')->default(false);
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
        Schema::dropIfExists('editorial_board_applications');
    }
};
