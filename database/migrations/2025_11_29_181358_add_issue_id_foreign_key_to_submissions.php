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
        Schema::table('client_order_submissions', function (Blueprint $table) {
            $table->foreign('issue_id')->references('id')->on('issues')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('client_order_submissions', function (Blueprint $table) {
            $table->dropForeign(['issue_id']);
        });
    }
};
