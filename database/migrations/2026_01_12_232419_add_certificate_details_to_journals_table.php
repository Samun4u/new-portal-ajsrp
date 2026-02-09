<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('journals', function (Blueprint $table) {
            if (!Schema::hasColumn('journals', 'impact_factor')) {
                $table->string('impact_factor')->nullable()->after('status');
            }
            if (!Schema::hasColumn('journals', 'chief_editor_name_ar')) {
                $table->string('chief_editor_name_ar')->nullable()->after('editor_in_chief');
            }
            if (!Schema::hasColumn('journals', 'managing_editor_name_en')) {
                $table->string('managing_editor_name_en')->nullable()->after('chief_editor_name_ar');
            }
            if (!Schema::hasColumn('journals', 'managing_editor_name_ar')) {
                $table->string('managing_editor_name_ar')->nullable()->after('managing_editor_name_en');
            }
            if (!Schema::hasColumn('journals', 'signature_path')) {
                $table->string('signature_path')->nullable()->after('managing_editor_name_ar');
            }
            if (!Schema::hasColumn('journals', 'managing_editor_signature_path')) {
                $table->string('managing_editor_signature_path')->nullable()->after('signature_path');
            }
            if (!Schema::hasColumn('journals', 'stamp_path')) {
                $table->string('stamp_path')->nullable()->after('managing_editor_signature_path');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('journals', function (Blueprint $table) {
            $table->dropColumn([
                'impact_factor',
                'chief_editor_name_ar',
                'managing_editor_name_en',
                'managing_editor_name_ar',
                'signature_path',
                'managing_editor_signature_path',
                'stamp_path',
            ]);
        });
    }
};
