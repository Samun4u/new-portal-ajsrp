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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('service_name');
            $table->longText('service_description');
            $table->text('assign_member')->nullable(); // [1,2,3]
            $table->integer('duration')->nullable();
            $table->integer('image');
            $table->integer('file');
            $table->tinyInteger('payment_type')->default(PAYMENT_TYPE_ONETIME);
            $table->decimal('price', 12, 2);
            $table->tinyInteger('recurring_type')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->unsignedBigInteger('user_id')->default(0);
            $table->string('tenant_id')->nullable();
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
        Schema::dropIfExists('services');
    }
};
