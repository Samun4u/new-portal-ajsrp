<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->string('overall_recommendation')->nullable()->after('status');
            $table->tinyInteger('rating_originality')->nullable()->after('overall_recommendation');
            $table->tinyInteger('rating_methodology')->nullable()->after('rating_originality');
            $table->tinyInteger('rating_results')->nullable()->after('rating_methodology');
            $table->tinyInteger('rating_clarity')->nullable()->after('rating_results');
            $table->tinyInteger('rating_significance')->nullable()->after('rating_clarity');
            $table->text('comment_strengths')->nullable()->after('rating_significance');
            $table->text('comment_weaknesses')->nullable()->after('comment_strengths');
            $table->text('comment_for_authors')->nullable()->after('comment_weaknesses');
            $table->text('comment_for_editor')->nullable()->after('comment_for_authors');
            $table->json('specific_checks')->nullable()->after('comment_for_editor');
            $table->unsignedTinyInteger('progress')->default(0)->after('specific_checks');
            $table->decimal('quality_rating', 4, 2)->nullable()->after('progress');
            $table->timestamp('submitted_at')->nullable()->after('quality_rating');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropColumn([
                'overall_recommendation',
                'rating_originality',
                'rating_methodology',
                'rating_results',
                'rating_clarity',
                'rating_significance',
                'comment_strengths',
                'comment_weaknesses',
                'comment_for_authors',
                'comment_for_editor',
                'specific_checks',
                'progress',
                'quality_rating',
                'submitted_at',
            ]);
        });
    }
};

