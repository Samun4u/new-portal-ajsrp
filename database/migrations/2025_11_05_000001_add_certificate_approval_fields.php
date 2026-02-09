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
        // Add fields to research table to link with client orders and track approval
        Schema::table('research', function (Blueprint $table) {
            $table->string('client_order_id')->nullable()->after('manuscript_path');
            $table->foreignId('user_id')->nullable()->after('client_order_id')->constrained('users')->onDelete('cascade');
            $table->enum('approval_status', ['pending', 'approved', 'rejected'])->default('pending')->after('user_id');
            $table->timestamp('approved_at')->nullable()->after('approval_status');
            $table->foreignId('approved_by')->nullable()->after('approved_at')->constrained('users')->onDelete('set null');
            $table->text('admin_notes')->nullable()->after('approved_by');
            $table->enum('language', ['en', 'ar'])->default('en')->after('admin_notes');
        });

        // Add certificate_sent flag to primary certificates table
        Schema::table('primary_certificates', function (Blueprint $table) {
            $table->boolean('certificate_sent')->default(false)->after('journal_name');
            $table->timestamp('sent_at')->nullable()->after('certificate_sent');
            $table->foreignId('research_id')->nullable()->after('sent_at')->constrained('research')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('research', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['approved_by']);
            $table->dropColumn([
                'client_order_id',
                'user_id',
                'approval_status',
                'approved_at',
                'approved_by',
                'admin_notes',
                'language'
            ]);
        });

        Schema::table('primary_certificates', function (Blueprint $table) {
            $table->dropForeign(['research_id']);
            $table->dropColumn(['certificate_sent', 'sent_at', 'research_id']);
        });
    }
};

