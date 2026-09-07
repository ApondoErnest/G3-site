<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->text('app_authentication_secret')->nullable()->after('is_active');
            $table->text('app_authentication_recovery_codes')->nullable()->after('app_authentication_secret');
        });

        Schema::table('users', function (Blueprint $table): void {
            $table->dropColumn(['mfa_secret', 'mfa_confirmed_at']);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->text('mfa_secret')->nullable()->after('is_active');
            $table->timestamp('mfa_confirmed_at')->nullable()->after('mfa_secret');
        });

        Schema::table('users', function (Blueprint $table): void {
            $table->dropColumn([
                'app_authentication_secret',
                'app_authentication_recovery_codes',
            ]);
        });
    }
};
