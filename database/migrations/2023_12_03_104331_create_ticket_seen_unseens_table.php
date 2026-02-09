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
        Schema::create('ticket_seen_unseens', function (Blueprint $table) {
            $table->id();
            $table->integer('ticket_id')->default(0);
            $table->integer('conversion_id')->default(0);
            $table->tinyInteger('is_seen')->default(1);
            $table->string('tenant_id')->nullable();
            $table->integer('created_by')->default(0);
            $table->unique(['ticket_id', 'created_by']);
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
        Schema::dropIfExists('ticket_seen_unseens');
    }
};
