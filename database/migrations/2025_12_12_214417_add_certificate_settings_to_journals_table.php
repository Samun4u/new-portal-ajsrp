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
        Schema::table('journals', function (Blueprint $table) {
            $table->string('editor_in_chief')->nullable()->after('ojs_context');
            $table->unsignedBigInteger('certificate_logo_file_id')->nullable()->after('editor_in_chief');
            $table->text('certificate_template_settings')->nullable()->after('certificate_logo_file_id'); // JSON for custom template settings

            $table->foreign('certificate_logo_file_id')->references('id')->on('file_managers')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('journals', function (Blueprint $table) {
            $table->dropForeign(['certificate_logo_file_id']);
            $table->dropColumn(['editor_in_chief', 'certificate_logo_file_id', 'certificate_template_settings']);
        });
    }
};
