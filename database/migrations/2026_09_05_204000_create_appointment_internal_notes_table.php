<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appointment_internal_notes', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('appointment_request_id');
            $table->unsignedBigInteger('author_id');
            $table->text('body');
            $table->timestamps();

            $table->foreign('appointment_request_id', 'fk_appointment_internal_notes_appointment_requests')
                ->references('id')->on('appointment_requests')
                ->cascadeOnDelete();
            $table->foreign('author_id', 'fk_appointment_internal_notes_users')
                ->references('id')->on('users')
                ->restrictOnDelete();
            $table->index(['appointment_request_id', 'created_at'], 'idx_appointment_internal_notes_request_created');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointment_internal_notes');
    }
};
