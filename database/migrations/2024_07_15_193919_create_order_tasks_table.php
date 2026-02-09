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
        Schema::create('order_tasks', function (Blueprint $table) {
            $table->id();
            $table->string('tenant_id');
            $table->unsignedBigInteger('client_order_id');
            $table->string('taskId')->nullable();
            $table->string('task_name');
            $table->longText('description')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->integer('progress')->default(0);
            $table->tinyInteger('priority')->default(5);
            $table->tinyInteger('client_access')->default(0);
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('last_reply_id');
            $table->unsignedBigInteger('last_reply_by');
            $table->dateTime('last_reply_time');
            $table->tinyInteger('status')->default(ORDER_TASK_STATUS_PENDING);
            $table->softDeletes();
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
        Schema::dropIfExists('order_tasks');
    }
};
