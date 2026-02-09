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
        Schema::table('file_managers', function (Blueprint $table) {
            $table->string('file_role')->nullable()->after('file_type')->comment('final_manuscript, proof_version, galley_version, certificate, revision, other');
            $table->index('file_role');
        });
    }

    public function down()
    {
        Schema::table('file_managers', function (Blueprint $table) {
            $table->dropIndex(['file_role']);
            $table->dropColumn('file_role');
        });
    }
};
