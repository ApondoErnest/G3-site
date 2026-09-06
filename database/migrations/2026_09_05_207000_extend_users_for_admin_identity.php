<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->boolean('is_active')->default(true)->after('email_verified_at');
            $table->text('mfa_secret')->nullable()->after('is_active');
            $table->timestamp('mfa_confirmed_at')->nullable()->after('mfa_secret');

            $table->index('is_active', 'idx_users_active');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->dropIndex('idx_users_active');
            $table->dropColumn(['is_active', 'mfa_secret', 'mfa_confirmed_at']);
        });
    }
};
