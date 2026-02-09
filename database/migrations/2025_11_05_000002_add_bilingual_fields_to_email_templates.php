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
        Schema::table('email_templates', function (Blueprint $table) {
            $table->string('subject_ar')->nullable()->after('subject');
            $table->text('body_ar')->nullable()->after('body');
            $table->string('language')->default('both')->after('body_ar'); // both, en, ar
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('email_templates', function (Blueprint $table) {
            $table->dropColumn(['subject_ar', 'body_ar', 'language']);
        });
    }
};

