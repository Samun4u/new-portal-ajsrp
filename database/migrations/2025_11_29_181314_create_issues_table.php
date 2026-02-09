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
        Schema::create('issues', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('journal_id');
            $table->integer('volume')->nullable();
            $table->integer('number')->nullable();
            $table->year('year')->nullable();
            $table->string('title')->nullable()->comment('e.g., Special Issue: AI in Education');
            $table->string('status')->default('planned')->comment('planned, scheduled, published');
            $table->date('planned_publication_date')->nullable();
            $table->date('publication_date')->nullable();
            $table->string('ojs_issue_id')->nullable()->comment('OJS issue ID if syncing');
            $table->timestamps();

            $table->foreign('journal_id')->references('id')->on('journals')->onDelete('cascade');
            $table->index(['journal_id', 'volume', 'number', 'year']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('issues');
    }
};
