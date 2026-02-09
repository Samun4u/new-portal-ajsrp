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
        Schema::create('client_order_submission_revision_files', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('revision_id');
            $table->unsignedBigInteger('file_id');
            $table->string('label')->nullable();
            $table->timestamps();

            $table->foreign('revision_id', 'revision_files_revision_fk')
                ->references('id')
                ->on('client_order_submission_revisions')
                ->onDelete('cascade');

            $table->foreign('file_id', 'revision_files_file_fk')
                ->references('id')
                ->on('file_managers')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_order_submission_revision_files');
    }
};

