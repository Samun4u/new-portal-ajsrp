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
        Schema::create('order_form_services', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('tenant_id')->nullable();
            $table->unsignedBigInteger('order_form_id')->nullable();
            $table->unsignedBigInteger('service_id')->nullable();
            $table->string('policy_link')->nullable();
            $table->integer('quantity')->default(1);
            $table->decimal('price', 12, 2)->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_form_services');
    }
};
