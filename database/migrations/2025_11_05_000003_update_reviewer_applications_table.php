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
        Schema::table('reviewer_applications', function (Blueprint $table) {
            $table->foreignId('approved_user_id')->nullable()->after('status')->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable()->after('approved_user_id');
            $table->foreignId('approved_by')->nullable()->after('approved_at')->constrained('users')->onDelete('set null');
            $table->text('rejection_reason')->nullable()->after('approved_by');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reviewer_applications', function (Blueprint $table) {
            $table->dropForeign(['approved_user_id']);
            $table->dropForeign(['approved_by']);
            $table->dropColumn(['approved_user_id', 'approved_at', 'approved_by', 'rejection_reason']);
        });
    }
};

