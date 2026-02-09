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
        Schema::create('certificate_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_order_submission_id');
            $table->unsignedBigInteger('file_id')->nullable();
            $table->string('journal_name')->nullable();
            $table->string('volume')->nullable();
            $table->string('issue')->nullable();
            $table->date('acceptance_date')->nullable();
            $table->date('publication_date')->nullable();
            $table->string('editor_in_chief')->nullable();
            $table->text('custom_data')->nullable(); // JSON for any additional custom fields
            $table->boolean('is_active')->default(true); // Latest certificate is active
            $table->unsignedBigInteger('issued_by')->nullable(); // User who issued it
            $table->timestamp('issued_at')->nullable();
            $table->timestamps();

            $table->foreign('client_order_submission_id')->references('id')->on('client_order_submissions')->onDelete('cascade');
            $table->foreign('file_id')->references('id')->on('file_managers')->onDelete('set null');
            $table->foreign('issued_by')->references('id')->on('users')->onDelete('set null');
            $table->index(['client_order_submission_id', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('certificate_histories');
    }
};
