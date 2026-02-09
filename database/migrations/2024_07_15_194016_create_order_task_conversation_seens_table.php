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
        Schema::create('order_task_conversation_seens', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_task_id')->default(0);
            $table->unsignedBigInteger('order_task_conversation_id')->default(0);
            $table->tinyInteger('is_seen')->default(1);
            $table->string('tenant_id')->nullable();
            $table->unsignedBigInteger('created_by')->default(0);
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
        Schema::dropIfExists('order_task_conversation_seens');
    }
};
