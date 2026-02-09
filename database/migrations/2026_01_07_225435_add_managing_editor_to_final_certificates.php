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
        Schema::table('final_certificates', function (Blueprint $table) {
            $table->string('managing_editor')->nullable()->after('chief_editor_ar');
            $table->string('managing_editor_ar')->nullable()->after('managing_editor');
            $table->string('managing_editor_signature_path')->nullable()->after('signature_path');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('final_certificates', function (Blueprint $table) {
            $table->dropColumn(['managing_editor', 'managing_editor_ar', 'managing_editor_signature_path']);
        });
    }
};
