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
        Schema::create('reviewer_certificates', function (Blueprint $table) {
            $table->id();
            $table->string('client_order_id');
            $table->string('title');
            $table->integer('reviewer_id');
            $table->text('affiliations');
            $table->string('paper_title');
            $table->string('journal_name');
            $table->tinyInteger('status')->default(STATUS_ACTIVE);
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
        Schema::dropIfExists('reviewer_certificates');
    }
};
