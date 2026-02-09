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
        Schema::create('quotation_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('quotation_id');
            $table->unsignedBigInteger('service_id')->nullable();
            $table->string('service_name')->nullable();
            $table->decimal('price', 12, 2)->default(0);
            $table->integer('quantity')->default(0);
            $table->decimal('total', 12, 2)->default(0);
            $table->tinyInteger('duration')->default(DURATION_TYPE_DAY);

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
        Schema::dropIfExists('quotation_items');
    }
};
