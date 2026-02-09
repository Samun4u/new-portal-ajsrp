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
        Schema::table('authors', function (Blueprint $table) {
            $table->string('title_ar')->nullable()->after('research_id');
            $table->string('title_en')->nullable()->after('title_ar');
            $table->string('title_value')->nullable()->after('title_en');
            $table->string('degree_value')->nullable()->after('title_value');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('authors', function (Blueprint $table) {
            $table->dropColumn(['title_ar', 'title_en', 'title_value', 'degree_value']);
        });
    }
};
