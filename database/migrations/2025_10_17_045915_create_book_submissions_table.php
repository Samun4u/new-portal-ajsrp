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
        Schema::create('book_submissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id')->nullable();
            $table->string('title');
            $table->string('author');
            $table->string('genre')->nullable();
            $table->string('language')->nullable();
            $table->year('publication_year')->nullable();
            $table->string('email');
            $table->text('summary')->nullable();
            $table->integer('book_file_id');
            $table->integer('cover_image_file_id')->nullable();
            $table->boolean('allow_public')->default(false);
            $table->string('status')->default('pending');
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
        Schema::dropIfExists('book_submissions');
    }
};
