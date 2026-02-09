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
        Schema::create('client_order_conversation_seens', function (Blueprint $table) {
            $table->id();
            $table->integer('order_id')->default(0);
            $table->integer('conversion_id')->default(0);
            $table->tinyInteger('is_seen')->default(1);
            $table->string('tenant_id')->nullable();
            $table->integer('created_by')->default(0);
            $table->unique(['order_id', 'created_by']);
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
        Schema::dropIfExists('client_order_conversation_seens');
    }
};
