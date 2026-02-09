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
        Schema::table('reviewer_certificates', function (Blueprint $table) {
            $table->string('language', 10)->default('en')->after('journal_name');
            $table->string('chief_editor_name')->nullable()->after('language');
            $table->string('chief_editor_name_ar')->nullable()->after('chief_editor_name');
            $table->string('signature_image')->nullable()->after('chief_editor_name_ar');
            $table->string('logo_image')->nullable()->after('signature_image');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reviewer_certificates', function (Blueprint $table) {
            $table->dropColumn(['language', 'chief_editor_name', 'chief_editor_name_ar', 'signature_image', 'logo_image']);
        });
    }
};
