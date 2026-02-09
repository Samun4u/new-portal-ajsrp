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
        Schema::table('research', function (Blueprint $table) {
            $table->string('paper_id_ar')->nullable()->after('keywords');
            $table->string('paper_id_en')->nullable()->after('paper_id_ar');
            $table->longText('thesis_answer')->nullable()->after('paper_id_en');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('research', function (Blueprint $table) {
            $table->dropColumn(['paper_id_ar', 'paper_id_en', 'thesis_answer']);
        });
    }
};
