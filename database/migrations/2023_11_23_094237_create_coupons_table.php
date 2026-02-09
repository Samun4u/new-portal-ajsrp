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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('tenant_id');
            $table->text('service_ids');
            $table->string('title');
            $table->string('code');
            $table->decimal('discount_amount', 12, 2);
            $table->tinyInteger('discount_type')->default(DISCOUNT_TYPE_FLAT);
            $table->tinyInteger('status')->default(STATUS_ACTIVE);
            $table->date('valid_date');
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
        Schema::dropIfExists('coupons');
    }
};
