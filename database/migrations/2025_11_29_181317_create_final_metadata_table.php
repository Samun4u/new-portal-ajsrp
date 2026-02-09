<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('final_metadata', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_order_submission_id');
            $table->string('final_title')->nullable();
            $table->string('short_title')->nullable();
            $table->longText('final_abstract')->nullable();
            $table->text('final_keywords')->nullable();
            $table->text('funding_statement')->nullable();
            $table->text('conflict_statement')->nullable();
            $table->text('acknowledgements')->nullable();
            $table->text('notes_for_layout')->nullable();
            $table->boolean('author_confirmed')->default(false);
            $table->timestamps();

            $table->foreign('client_order_submission_id')->references('id')->on('client_order_submissions')->onDelete('cascade');
            $table->unique('client_order_submission_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('final_metadata');
    }
};
