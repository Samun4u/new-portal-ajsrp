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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_order_submission_id');
            $table->string('client_order_id');
            $table->unsignedBigInteger('reviewer_id');
            $table->text('comments')->nullable();
            $table->string('admin_comments')->nullable();
            $table->string('admin_status')->nullable();
            $table->string('status')->default(SUBMISSION_REVIEWER_ORDER_STATUS_PENDING_REVIEW);
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
        Schema::dropIfExists('reviews');
    }
};
