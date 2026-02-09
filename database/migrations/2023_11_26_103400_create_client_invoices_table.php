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
        Schema::create('client_invoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('client_id')->nullable();
            $table->date('due_date')->nullable();
            $table->string('invoice_id')->default(0);
            $table->decimal('payable_amount', 12, 2)->default(0);
            $table->decimal('tax_amount', 12, 2)->default(0);
            $table->integer('tax_type')->default(0);
            $table->decimal('total', 12, 2)->default(0);
            $table->tinyInteger('is_mailed')->default(0);
            $table->tinyInteger('is_recurring')->default(0);
            $table->tinyInteger('payment_status')->default(0);
            $table->tinyInteger('create_type')->default(CREATE_TYPE_DEACTIVATE);
            $table->string('transaction_id')->nullable();
            $table->string('payment_id')->nullable();
            $table->bigInteger('gateway_id')->nullable();
            $table->decimal('conversion_rate', 12, 2)->default(0);
            $table->decimal('platform_charge', 12, 2)->default(0);
            $table->string('system_currency')->nullable();
            $table->string('gateway_currency')->nullable();
            $table->tinyInteger('bank_id')->nullable();
            $table->string('bank_deposit_by')->nullable();
            $table->string('bank_deposit_slip_id')->nullable();
            $table->decimal('transaction_amount', 12, 2)->default(0);
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('tenant_id')->default(0);
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
        Schema::dropIfExists('client_invoices');
    }
};
