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
        Schema::create('bulk_email_template_histories', function (Blueprint $table) {
            $table->id();
            $table->text('to');
            $table->text('bcc')->nullable();
            $table->string('subject');
            $table->text('body');
            $table->string('status');
            $table->text('api_response')->nullable();
            $table->foreignId('admin_id')->constrained('users');
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
        Schema::dropIfExists('bulk_email_template_histories');
    }
};
