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
        Schema::create('proof_files', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_order_submission_id');
            $table->unsignedBigInteger('file_id')->comment('FileManager ID');
            $table->string('version')->default('1')->comment('Proof version number');
            $table->text('notes')->nullable()->comment('Editor notes for this proof');
            $table->string('status')->default('pending')->comment('pending, approved, corrections_requested');
            $table->text('corrections_requested')->nullable()->comment('Author corrections notes');
            $table->unsignedBigInteger('uploaded_by')->nullable()->comment('User who uploaded proof');
            $table->unsignedBigInteger('reviewed_by')->nullable()->comment('Author who reviewed');
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();

            $table->foreign('client_order_submission_id')->references('id')->on('client_order_submissions')->onDelete('cascade');
            $table->foreign('file_id')->references('id')->on('file_managers')->onDelete('cascade');
            $table->foreign('uploaded_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('reviewed_by')->references('id')->on('users')->onDelete('set null');
            $table->index(['client_order_submission_id', 'status']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('proof_files');
    }
};
