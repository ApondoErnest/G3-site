<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admin_user_scopes', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('centre_id');
            $table->timestamp('created_at');

            $table->foreign('user_id', 'fk_admin_user_scopes_users')
                ->references('id')->on('users')
                ->cascadeOnDelete();
            $table->foreign('centre_id', 'fk_admin_user_scopes_centres')
                ->references('id')->on('centres')
                ->cascadeOnDelete();
            $table->unique(['user_id', 'centre_id'], 'uq_admin_user_scopes_user_centre');
            $table->index('centre_id', 'idx_admin_user_scopes_centre');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_user_scopes');
    }
};
