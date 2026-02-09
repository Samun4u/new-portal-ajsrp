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
        // Prevent migration failures on environments where the table was created manually / previously.
        if (Schema::hasTable('client_order_submission_revisions')) {
            return;
        }

        Schema::create('client_order_submission_revisions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_order_submission_id');
            $table->string('client_order_id');
            $table->unsignedBigInteger('author_id');
            $table->unsignedInteger('version')->default(1);
            $table->unsignedBigInteger('manuscript_file_id');
            $table->unsignedBigInteger('response_file_id')->nullable();
            $table->text('response_summary')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->foreign('client_order_submission_id')
                ->references('id')
                ->on('client_order_submissions')
                ->onDelete('cascade');

            $table->foreign('author_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('manuscript_file_id')
                ->references('id')
                ->on('file_managers')
                ->onDelete('cascade');

            $table->foreign('response_file_id')
                ->references('id')
                ->on('file_managers')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_order_submission_revisions');
    }
};

