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
        Schema::create('client_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_id')->nullable();
            $table->unsignedBigInteger('client_id')->nullable();
            $table->decimal('amount', 12, 2)->default(0);
            $table->decimal('discount', 12, 2)->default(0);
            $table->tinyInteger('discount_type')->default(0);
            $table->decimal('tax_amount', 12, 2)->default(0);
            $table->integer('tax_type')->default(0);
            $table->decimal('platform_charge', 12, 2)->default(0);
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('total', 12, 2)->default(0);
            $table->decimal('transaction_amount', 12, 2)->default(0);
            $table->tinyInteger('payment_status')->default(0);
            $table->tinyInteger('recurring_type')->default(RECURRING_EVERY_MONTH);
            $table->tinyInteger('recurring_payment_type')->default(PAYMENT_TYPE_ONETIME);
            $table->tinyInteger('order_create_type')->default(CREATE_TYPE_DEACTIVATE);
            $table->tinyInteger('working_status')->default(0);
            $table->string('system_currency')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('tenant_id')->nullable();
            $table->string('order_form_id')->nullable();
            $table->string('quotation_id')->nullable();
            $table->unsignedBigInteger('last_reply_id')->nullable();
            $table->unsignedBigInteger('last_reply_by')->nullable();
            $table->integer('file');
            $table->timestamp('last_reply_time')->nullable();
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
        Schema::dropIfExists('client_orders');
    }
};
