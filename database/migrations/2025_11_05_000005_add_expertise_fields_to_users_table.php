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
        Schema::table('users', function (Blueprint $table) {
            $table->string('field_of_study')->nullable()->after('email_verified_at');
            $table->json('subject_areas')->nullable()->after('field_of_study');
            $table->json('expertise_keywords')->nullable()->after('subject_areas');
            $table->integer('experience_years')->nullable()->after('expertise_keywords');
            $table->integer('reviews_completed')->default(0)->after('experience_years');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['field_of_study', 'subject_areas', 'expertise_keywords', 'experience_years', 'reviews_completed']);
        });
    }
};

