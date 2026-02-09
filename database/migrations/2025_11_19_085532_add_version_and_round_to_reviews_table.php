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
        Schema::table('reviews', function (Blueprint $table) {
            $table->unsignedInteger('version')->default(1)->after('reviewer_id');
            $table->unsignedInteger('round')->default(1)->after('version');
            $table->unsignedBigInteger('previous_version_id')->nullable()->after('round');
            $table->index(['client_order_submission_id', 'reviewer_id', 'version'], 'review_version_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropIndex('review_version_index');
            $table->dropColumn(['version', 'round', 'previous_version_id']);
        });
    }
};
