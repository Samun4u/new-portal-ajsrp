<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('client_order_submissions', function (Blueprint $table) {
            // Editor acceptance fields
            $table->timestamp('acceptance_date')->nullable()->after('approval_status');
            $table->unsignedBigInteger('decision_by_user_id')->nullable()->after('acceptance_date');

            // Metadata workflow
            $table->string('metadata_status')->nullable()->after('decision_by_user_id')->comment('pending_author, pending_editor_review, approved');

            // Workflow stages
            $table->string('workflow_stage')->nullable()->after('metadata_status')->comment('proofreading, galley');

            // File references
            $table->unsignedBigInteger('final_manuscript_file_id')->nullable()->after('workflow_stage');
            $table->unsignedBigInteger('acceptance_certificate_file_id')->nullable()->after('final_manuscript_file_id');

            // Issue assignment
            $table->unsignedBigInteger('issue_id')->nullable()->after('journal_id');

            // Publication dates
            $table->date('scheduled_publication_date')->nullable()->after('issue_id');
            $table->date('publication_date')->nullable()->after('scheduled_publication_date');

            // OJS integration
            $table->string('ojs_article_id')->nullable()->after('publication_date');
            $table->text('ojs_article_url')->nullable()->after('ojs_article_id');

            // Foreign keys (issue_id FK will be added in separate migration after issues table is created)
            $table->foreign('decision_by_user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('final_manuscript_file_id')->references('id')->on('file_managers')->onDelete('set null');
            $table->foreign('acceptance_certificate_file_id')->references('id')->on('file_managers')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('client_order_submissions', function (Blueprint $table) {
            $table->dropForeign(['decision_by_user_id']);
            $table->dropForeign(['final_manuscript_file_id']);
            $table->dropForeign(['acceptance_certificate_file_id']);

            $table->dropColumn([
                'acceptance_date',
                'decision_by_user_id',
                'metadata_status',
                'workflow_stage',
                'final_manuscript_file_id',
                'acceptance_certificate_file_id',
                'issue_id',
                'scheduled_publication_date',
                'publication_date',
                'ojs_article_id',
                'ojs_article_url',
            ]);
        });
    }
};
