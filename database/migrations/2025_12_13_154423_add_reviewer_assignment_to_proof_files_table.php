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
        Schema::table('proof_files', function (Blueprint $table) {
            $table->string('review_type')->default('author')->after('status')->comment('author, editor, reviewer');
            $table->unsignedBigInteger('assigned_reviewer_id')->nullable()->after('review_type')->comment('Assigned reviewer/editor to review');
            $table->text('review_notes')->nullable()->after('corrections_requested')->comment('Reviewer/Editor review notes');

            $table->foreign('assigned_reviewer_id')->references('id')->on('users')->onDelete('set null');
            $table->index(['review_type', 'assigned_reviewer_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('proof_files', function (Blueprint $table) {
            $table->dropForeign(['assigned_reviewer_id']);
            $table->dropIndex(['review_type', 'assigned_reviewer_id', 'status']);
            $table->dropColumn(['review_type', 'assigned_reviewer_id', 'review_notes']);
        });
    }
};
