<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appointment_requests', function (Blueprint $table): void {
            $table->id();
            $table->string('public_reference', 16);
            $table->unsignedBigInteger('centre_id');
            $table->unsignedBigInteger('service_id');
            $table->unsignedBigInteger('vehicle_category_id');
            $table->string('registration_normalized', 32);
            $table->string('registration_display', 32)->nullable();
            $table->date('preferred_date')->nullable();
            $table->string('preferred_period', 32);
            $table->string('contact_name');
            $table->string('contact_phone_e164', 20);
            $table->string('contact_email')->nullable();
            $table->string('preferred_channel', 32)->nullable();
            $table->char('locale', 2);
            $table->string('status', 32);
            $table->string('idempotency_key', 64)->nullable();
            $table->timestamp('finalized_at')->nullable();
            $table->timestamps();

            $table->unique('public_reference', 'uq_appointment_requests_public_reference');
            $table->unique('idempotency_key', 'uq_appointment_requests_idempotency_key');
            $table->foreign('centre_id', 'fk_appointment_requests_centres')
                ->references('id')->on('centres')
                ->restrictOnDelete();
            $table->foreign('service_id', 'fk_appointment_requests_services')
                ->references('id')->on('services')
                ->restrictOnDelete();
            $table->foreign('vehicle_category_id', 'fk_appointment_requests_vehicle_categories')
                ->references('id')->on('vehicle_categories')
                ->restrictOnDelete();
            $table->index(['status', 'centre_id', 'created_at'], 'idx_appointment_requests_queue');
            $table->index(['centre_id', 'created_at'], 'idx_appointment_requests_centre_created');
            $table->index('registration_normalized', 'idx_appointment_requests_registration');
            $table->index('contact_phone_e164', 'idx_appointment_requests_phone');
            $table->index('created_at', 'idx_appointment_requests_created');
            $table->index(['status', 'finalized_at'], 'idx_appointment_requests_finalized_purge');
        });

        Schema::create('appointment_status_histories', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('appointment_request_id');
            $table->string('status', 32);
            $table->json('public_note')->nullable();
            $table->string('actor_type', 32);
            $table->unsignedBigInteger('actor_id')->nullable();
            $table->timestamp('created_at');

            $table->foreign('appointment_request_id', 'fk_appointment_status_histories_appointment_requests')
                ->references('id')->on('appointment_requests')
                ->cascadeOnDelete();
            $table->foreign('actor_id', 'fk_appointment_status_histories_users')
                ->references('id')->on('users')
                ->nullOnDelete();
            $table->index(['appointment_request_id', 'created_at'], 'idx_appointment_status_histories_request_created');
            $table->index('created_at', 'idx_appointment_status_histories_created');
        });

        DB::statement(
            'ALTER TABLE appointment_requests ADD CONSTRAINT chk_ar_locale '
            ."CHECK (locale IN ('fr', 'en'))",
        );
    }

    public function down(): void
    {
        Schema::dropIfExists('appointment_status_histories');
        Schema::dropIfExists('appointment_requests');
    }
};
