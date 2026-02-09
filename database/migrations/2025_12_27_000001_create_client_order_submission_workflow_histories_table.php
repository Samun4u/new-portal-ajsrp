<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('client_order_submission_workflow_histories', function (Blueprint $table) {
            $table->id();
            // NOTE: 2nd argument of unsignedBigInteger is $autoIncrement (bool). Do NOT pass numbers.
            $table->unsignedBigInteger('client_order_submission_id')->index();
            $table->string('event_type', 80);
            $table->string('field', 80)->nullable();
            $table->text('from_value')->nullable();
            $table->text('to_value')->nullable();
            $table->json('meta')->nullable();
            $table->unsignedBigInteger('actor_id')->nullable();
            $table->timestamps();

            $table->foreign('client_order_submission_id')
                ->references('id')
                ->on('client_order_submissions')
                ->onDelete('cascade');
            $table->foreign('actor_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null');

            $table->index(['client_order_submission_id', 'event_type'], 'cos_wh_submission_event_idx');
            $table->index(['client_order_submission_id', 'created_at'], 'cos_wh_submission_created_idx');
        });
    }

    public function down()
    {
        Schema::dropIfExists('client_order_submission_workflow_histories');
    }
};


