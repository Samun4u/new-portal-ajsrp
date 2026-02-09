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
        Schema::table('user_packages', function (Blueprint $table) {
            $table->string('tenant_id')->default('zainiklab')->after('id');
        });

        Schema::table('subscription_orders', function (Blueprint $table) {
            $table->string('tenant_id')->default('zainiklab')->after('id');
        });

        Schema::table('settings', function (Blueprint $table) {
            $table->string('tenant_id')->nullable()->after('id');
        });

        Schema::table('service_assignees', function (Blueprint $table) {
            $table->string('tenant_id')->default('zainiklab')->after('id');
        });

        Schema::table('user_activity_logs', function (Blueprint $table) {
            $table->string('tenant_id')->nullable()->after('id');
        });

        Schema::table('notifications', function (Blueprint $table) {
            $table->string('tenant_id')->nullable()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_packages', function (Blueprint $table) {
            $table->dropColumn('tenant_id');
        });

        Schema::table('subscription_orders', function (Blueprint $table) {
            $table->dropColumn('tenant_id');
        });

        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn('tenant_id');
        });

        Schema::table('service_assignees', function (Blueprint $table) {
            $table->dropColumn('tenant_id');
        });

        Schema::table('user_activity_logs', function (Blueprint $table) {
            $table->dropColumn('tenant_id');
        });

        Schema::table('notifications', function (Blueprint $table) {
            $table->dropColumn('tenant_id');
        });
    }
};
