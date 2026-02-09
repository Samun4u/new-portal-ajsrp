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
        Schema::create('quotations', function (Blueprint $table) {
            $table->id();
            $table->string('tenant_id')->nullable();
            $table->unsignedBigInteger('client_id')->nullable();
            $table->date('expire_date')->nullable();
            $table->string('address')->nullable();
            $table->string('quotation_id')->default(0);
            $table->string('client_name')->nullable();
            $table->string('email')->nullable();
            $table->string('description')->nullable();
            $table->decimal('total', 12, 2)->default(0);
            $table->decimal('sub_total', 12, 2)->default(0);
            $table->decimal('discount', 12, 2)->default(0);
            $table->tinyInteger('status')->default(0);
            $table->tinyInteger('is_emailed')->default(0);
            $table->unsignedBigInteger('user_id')->nullable();
            $table->tinyInteger('discount_type')->default(DISCOUNT_TYPE_FLAT);
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
        Schema::dropIfExists('quotations');
    }
};
