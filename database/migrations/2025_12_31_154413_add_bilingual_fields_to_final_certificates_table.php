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
            $table->string('language')->default('english')->after('date');
            $table->string('certificate_number')->nullable()->after('language');
            $table->string('chief_editor')->nullable()->after('certificate_number');
            $table->string('chief_editor_ar')->nullable()->after('chief_editor');
            $table->string('issn')->nullable()->after('chief_editor_ar');
            $table->string('signature_path')->nullable()->after('issn');
            $table->string('stamp_path')->nullable()->after('signature_path');
            $table->string('pdf_path')->nullable()->after('stamp_path');
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
            $table->dropColumn([
                'language',
                'certificate_number',
                'chief_editor',
                'chief_editor_ar',
                'issn',
                'signature_path',
                'stamp_path',
                'pdf_path'
            ]);
        });
    }
};
