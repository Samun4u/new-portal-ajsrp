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
        Schema::create('reviewer_applications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id')->nullable();
            $table->string('full_name');
            $table->string('email');
            $table->string('institution');
            $table->string('country');
            $table->string('orcid')->nullable();
            $table->json('profile_links')->nullable();
            $table->string('qualification');
            $table->string('field_of_study');
            $table->string('position');
            $table->integer('experience_years');
            $table->json('subject_areas');
            $table->json('keywords');
            $table->text('review_experience')->nullable();
            $table->unsignedBigInteger('cv_file_id')->nullable();
            $table->unsignedBigInteger('photo_file_id')->nullable();
            $table->boolean('agreement');
            $table->boolean('consent_acknowledgment');
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
        Schema::dropIfExists('reviewer_applications');
    }
};
