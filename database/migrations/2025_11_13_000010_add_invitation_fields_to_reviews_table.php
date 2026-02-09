<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->enum('invitation_status', ['pending', 'accepted', 'declined', 'expired'])
                ->default('pending')
                ->after('status');
            $table->boolean('conflict_declared')->default(false)->after('invitation_status');
            $table->text('conflict_details')->nullable()->after('conflict_declared');
            $table->timestamp('invited_at')->nullable()->after('conflict_details');
            $table->timestamp('responded_at')->nullable()->after('invited_at');
            $table->json('invitation_metadata')->nullable()->after('responded_at');
        });

        Schema::table('client_order_assignees', function (Blueprint $table) {
            $table->timestamp('invited_at')->nullable()->after('due_at');
            $table->timestamp('responded_at')->nullable()->after('invited_at');
            $table->enum('invitation_status', ['pending', 'accepted', 'declined', 'expired'])
                ->default('pending')
                ->after('responded_at');
        });
    }

    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropColumn([
                'invitation_status',
                'conflict_declared',
                'conflict_details',
                'invited_at',
                'responded_at',
                'invitation_metadata',
            ]);
        });

        Schema::table('client_order_assignees', function (Blueprint $table) {
            $table->dropColumn(['invited_at', 'responded_at', 'invitation_status']);
        });
    }
};

