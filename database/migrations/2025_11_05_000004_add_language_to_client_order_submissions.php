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
        Schema::table('client_order_submissions', function (Blueprint $table) {
            $table->enum('language', ['en', 'ar'])->default('en')->after('approval_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('client_order_submissions', function (Blueprint $table) {
            $table->dropColumn('language');
        });
    }
};

