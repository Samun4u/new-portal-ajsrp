<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJournalSignaturesTable extends Migration
{
    public function up()
    {
        Schema::create('journal_signatures', function (Blueprint $table) {
            $table->id();
            $table->string('journal_abbrev', 20)->unique();
            $table->string('chief_editor_name')->nullable();
            $table->string('chief_editor_name_ar')->nullable();
            $table->string('signature_path')->nullable();
            $table->string('stamp_path')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('journal_signatures');
    }
}
