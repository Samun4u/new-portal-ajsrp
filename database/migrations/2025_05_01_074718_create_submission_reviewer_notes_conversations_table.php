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
        Schema::create('submission_reviewer_notes_conversations', function (Blueprint $table) {
            $table->id();
            $table->string('submission_reviewer_note_id');
            $table->unsignedBigInteger('user_id');
            $table->longText('conversation_text');
            $table->text('attachment')->nullable();
            $table->string('tenant_id')->nullable();
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
        Schema::dropIfExists('submission_reviewer_notes_conversations');
    }
};
