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
        Schema::table('reviewer_applications', function (Blueprint $table) {
            //status 
            $table->string('status')->default('pending')->after('consent_acknowledgment');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reviewer_applications', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
