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
        Schema::table('journals', function (Blueprint $table) {
            $table->string('short_name')->nullable()->after('title');
            $table->string('issn_print')->nullable()->after('short_name');
            $table->string('issn_online')->nullable()->after('issn_print');
            $table->string('ojs_context')->nullable()->after('issn_online')->comment('OJS journal path/slug for API integration');
        });
    }

    public function down()
    {
        Schema::table('journals', function (Blueprint $table) {
            $table->dropColumn(['short_name', 'issn_print', 'issn_online', 'ojs_context']);
        });
    }
};
