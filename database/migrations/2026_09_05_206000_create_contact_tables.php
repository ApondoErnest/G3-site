<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contact_messages', function (Blueprint $table): void {
            $table->id();
            $table->string('intent', 32);
            $table->string('status', 32);
            $table->string('name');
            $table->string('phone_e164', 20);
            $table->string('email');
            $table->string('subject');
            $table->unsignedBigInteger('centre_id')->nullable();
            $table->text('message');
            $table->char('locale', 2);
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();

            $table->foreign('centre_id', 'fk_contact_messages_centres')
                ->references('id')->on('centres')
                ->restrictOnDelete();
            $table->index(['status', 'created_at'], 'idx_contact_messages_inbox');
            $table->index('intent', 'idx_contact_messages_intent');
            $table->index(['status', 'updated_at'], 'idx_contact_messages_resolved');
            $table->index(['status', 'resolved_at'], 'idx_contact_messages_resolved_purge');
        });

        Schema::create('contact_internal_notes', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('contact_message_id');
            $table->unsignedBigInteger('author_id');
            $table->text('body');
            $table->timestamps();

            $table->foreign('contact_message_id', 'fk_contact_internal_notes_contact_messages')
                ->references('id')->on('contact_messages')
                ->cascadeOnDelete();
            $table->foreign('author_id', 'fk_contact_internal_notes_users')
                ->references('id')->on('users')
                ->restrictOnDelete();
            $table->index(['contact_message_id', 'created_at'], 'idx_contact_internal_notes_message_created');
        });

        DB::statement(
            'ALTER TABLE contact_messages ADD CONSTRAINT chk_cm_locale '
            ."CHECK (locale IN ('fr', 'en'))",
        );
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_internal_notes');
        Schema::dropIfExists('contact_messages');
    }
};
