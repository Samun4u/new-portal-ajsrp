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
        Schema::table('journal_signatures', function (Blueprint $table) {
            $table->string('managing_editor')->nullable()->after('chief_editor_name_ar');
            $table->string('managing_editor_name')->nullable()->after('chief_editor_name_ar');
            $table->string('managing_editor_name_ar')->nullable()->after('managing_editor_name');
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
        Schema::table('journal_signatures', function (Blueprint $table) {
            $table->dropColumn(['managing_editor', 'managing_editor_name', 'managing_editor_name_ar', 'managing_editor_signature_path']);
        });
    }
};
