<?php

use App\Actions\Appointment\Data\CreateAppointmentRequestData;
use App\Actions\Contact\Data\SubmitContactMessageData;
use App\Domain\Enums\ContactIntent;
use App\Domain\Enums\Locale;
use App\Domain\Enums\PreferredChannel;
use App\Domain\Enums\PreferredPeriod;
use App\Models\Centre\Centre;
use App\Models\User;
use App\Support\Clock;
use Carbon\CarbonImmutable;
use Database\Seeders\AdminRolesSeeder;
use Database\Seeders\BaselineCentresSeeder;
use Tests\TestCase;

pest()->extend(TestCase::class)
    ->in('Feature');

pest()->extend(TestCase::class)
    ->in('Unit');

afterEach(function (): void {
    Clock::unfreeze();
});

function seedBaselineCentres(): void
{
    (new BaselineCentresSeeder)->run();
}

function centreId(string $code): int
{
    return (int) Centre::query()->where('code', $code)->value('id');
}

function freezeDisplayTime(string $datetime): CarbonImmutable
{
    $instant = CarbonImmutable::parse($datetime, Clock::displayTimezone());
    Clock::freeze($instant);

    return $instant;
}

/**
 * @param  array<string, mixed>  $overrides
 */
function insertCentre(array $overrides = []): int
{
    return DB::table('centres')->insertGetId(array_merge([
        'code' => 'nomayos',
        'name' => json_encode(['fr' => 'Nomayos', 'en' => 'Nomayos']),
        'address' => json_encode(['fr' => 'Yaoundé', 'en' => 'Yaoundé']),
        'landmark' => json_encode(['fr' => 'Carrefour Nomayos', 'en' => 'Nomayos junction']),
        'latitude' => 3.7902275,
        'longitude' => 11.4439448,
        'email' => 'g3sarl1@gmail.com',
        'postal_code' => '12775 Yaoundé',
        'status' => 'active',
        'sort_order' => 2,
        'holiday_default_open' => true,
        'seo_title' => null,
        'seo_description' => null,
        'created_at' => now(),
        'updated_at' => now(),
    ], $overrides));
}

/**
 * @param  array<string, mixed>  $overrides
 */
function insertVehicleCategory(array $overrides = []): int
{
    return DB::table('vehicle_categories')->insertGetId(array_merge([
        'code' => 'category-'.uniqid(),
        'label' => json_encode(['fr' => 'Catégorie', 'en' => 'Category']),
        'examples' => null,
        'description' => null,
        'sort_order' => 1,
        'is_published' => false,
        'created_at' => now(),
        'updated_at' => now(),
    ], $overrides));
}

/**
 * @param  array<string, mixed>  $overrides
 */
function insertService(array $overrides = []): int
{
    return DB::table('services')->insertGetId(array_merge([
        'code' => 'service-'.uniqid(),
        'title' => json_encode(['fr' => 'Service', 'en' => 'Service']),
        'summary' => null,
        'body' => null,
        'icon' => null,
        'sort_order' => 1,
        'is_published' => false,
        'created_at' => now(),
        'updated_at' => now(),
    ], $overrides));
}

/**
 * @param  array<string, mixed>  $overrides
 */
function insertRequiredDocument(array $overrides = []): int
{
    return DB::table('required_documents')->insertGetId(array_merge([
        'label' => json_encode(['fr' => 'Carte grise', 'en' => 'Registration card']),
        'vehicle_category_id' => insertVehicleCategory(),
        'service_id' => null,
        'sort_order' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ], $overrides));
}

function linkServiceToCentre(int $serviceId, int $centreId): void
{
    DB::table('centre_service')->insert([
        'centre_id' => $centreId,
        'service_id' => $serviceId,
    ]);
}

function linkServiceToCategory(int $serviceId, int $categoryId): void
{
    DB::table('service_vehicle_category')->insert([
        'service_id' => $serviceId,
        'vehicle_category_id' => $categoryId,
    ]);
}

/**
 * @param  array<string, mixed>  $overrides
 */
function insertTariffVersion(array $overrides = []): int
{
    return DB::table('tariff_versions')->insertGetId(array_merge([
        'label' => 'tariff-'.uniqid(),
        'status' => 'draft',
        'effective_from' => '2026-01-01',
        'effective_until' => null,
        'reviewed_at' => null,
        'reviewed_by' => null,
        'published_at' => null,
        'published_by' => null,
        'created_at' => now(),
        'updated_at' => now(),
    ], $overrides));
}

/**
 * @param  array<string, mixed>  $overrides
 */
function insertTariffItem(array $overrides = []): int
{
    $versionId = $overrides['tariff_version_id'] ?? insertTariffVersion();
    $categoryId = $overrides['vehicle_category_id'] ?? insertVehicleCategory();

    return DB::table('tariff_items')->insertGetId(array_merge([
        'tariff_version_id' => $versionId,
        'vehicle_category_id' => $categoryId,
        'service_id' => null,
        'amount_xaf' => 25000,
        'validity_notes' => null,
        'sort_order' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ], $overrides));
}

function linkTariffItemToCentre(int $itemId, int $centreId): void
{
    DB::table('tariff_item_centre')->insert([
        'tariff_item_id' => $itemId,
        'centre_id' => $centreId,
    ]);
}

/**
 * @return array{serviceId: int, categoryId: int}
 */
function seedBookableCatalogue(int $centreId): array
{
    $categoryId = insertVehicleCategory([
        'code' => 'vp-'.uniqid(),
        'is_published' => true,
    ]);
    $serviceId = insertService([
        'code' => 'visite-technique-'.uniqid(),
        'is_published' => true,
    ]);
    linkServiceToCentre($serviceId, $centreId);
    linkServiceToCategory($serviceId, $categoryId);

    return [
        'serviceId' => $serviceId,
        'categoryId' => $categoryId,
    ];
}

/**
 * @param  array<string, mixed>  $overrides
 */
function insertAppointmentRequest(array $overrides = []): int
{
    $centreId = $overrides['centre_id']
        ?? DB::table('centres')->value('id')
        ?? insertCentre(['code' => 'centre-'.uniqid()]);
    $serviceId = $overrides['service_id'] ?? insertService(['is_published' => true]);
    $categoryId = $overrides['vehicle_category_id'] ?? insertVehicleCategory(['is_published' => true]);

    return DB::table('appointment_requests')->insertGetId(array_merge([
        'public_reference' => 'G3-26-'.strtoupper(substr(uniqid(), -5)),
        'centre_id' => $centreId,
        'service_id' => $serviceId,
        'vehicle_category_id' => $categoryId,
        'registration_normalized' => 'LT123AB',
        'registration_display' => 'LT 123 AB',
        'preferred_date' => '2026-09-10',
        'preferred_period' => 'morning',
        'contact_name' => 'Jean Dupont',
        'contact_phone_e164' => '+237687187516',
        'contact_email' => null,
        'preferred_channel' => 'phone',
        'locale' => 'fr',
        'status' => 'received',
        'idempotency_key' => null,
        'finalized_at' => null,
        'created_at' => now(),
        'updated_at' => now(),
    ], $overrides));
}

/**
 * @param  array<string, mixed>  $overrides
 */
function insertAppointmentStatusHistory(int $requestId, array $overrides = []): int
{
    return DB::table('appointment_status_histories')->insertGetId(array_merge([
        'appointment_request_id' => $requestId,
        'status' => 'received',
        'public_note' => null,
        'actor_type' => 'system',
        'actor_id' => null,
        'created_at' => now(),
    ], $overrides));
}

function createAppointmentPayload(int $centreId, array $overrides = []): CreateAppointmentRequestData
{
    $catalogue = seedBookableCatalogue($centreId);

    return new CreateAppointmentRequestData(
        centreId: $overrides['centreId'] ?? $centreId,
        serviceId: $overrides['serviceId'] ?? $catalogue['serviceId'],
        vehicleCategoryId: $overrides['vehicleCategoryId'] ?? $catalogue['categoryId'],
        registration: $overrides['registration'] ?? 'LT 123 AB',
        preferredDate: $overrides['preferredDate'] ?? CarbonImmutable::parse('2026-09-09', 'Africa/Douala'),
        preferredPeriod: $overrides['preferredPeriod'] ?? PreferredPeriod::Morning,
        contactName: $overrides['contactName'] ?? 'Jean Dupont',
        contactPhone: $overrides['contactPhone'] ?? '687187516',
        contactEmail: $overrides['contactEmail'] ?? null,
        preferredChannel: $overrides['preferredChannel'] ?? PreferredChannel::Phone,
        locale: $overrides['locale'] ?? Locale::Fr,
        idempotencyKey: $overrides['idempotencyKey'] ?? null,
        rateLimitKey: $overrides['rateLimitKey'] ?? 'test-client',
    );
}

/**
 * @param  array<string, mixed>  $overrides
 */
function insertContentBlock(array $overrides = []): int
{
    return DB::table('content_blocks')->insertGetId(array_merge([
        'key' => 'block-'.uniqid(),
        'page' => 'home',
        'schema_version' => 1,
        'content' => json_encode(['fr' => ['headline' => 'Bienvenue'], 'en' => ['headline' => 'Welcome']]),
        'locale_status' => json_encode(['fr' => 'complete', 'en' => 'complete']),
        'is_published' => false,
        'published_at' => null,
        'created_at' => now(),
        'updated_at' => now(),
    ], $overrides));
}

/**
 * @param  array<string, mixed>  $overrides
 */
function insertFaqEntry(array $overrides = []): int
{
    return DB::table('faq_entries')->insertGetId(array_merge([
        'category_code' => 'general',
        'question' => json_encode(['fr' => 'Question?', 'en' => 'Question?']),
        'answer' => json_encode(['fr' => 'Réponse', 'en' => 'Answer']),
        'sort_order' => 1,
        'is_published' => false,
        'created_at' => now(),
        'updated_at' => now(),
    ], $overrides));
}

/**
 * @param  array<string, mixed>  $overrides
 */
function insertTeamMember(array $overrides = []): int
{
    return DB::table('team_members')->insertGetId(array_merge([
        'name' => 'Jean Dupont',
        'role_title' => json_encode(['fr' => 'Directeur', 'en' => 'Director']),
        'bio' => json_encode(['fr' => 'Bio FR', 'en' => 'Bio EN']),
        'display_publicly' => false,
        'sort_order' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ], $overrides));
}

/**
 * @param  array<string, mixed>  $overrides
 */
function insertRoadSafetySection(array $overrides = []): int
{
    return DB::table('road_safety_sections')->insertGetId(array_merge([
        'anchor' => 'section-'.uniqid(),
        'title' => json_encode(['fr' => 'Freinage', 'en' => 'Braking']),
        'body' => json_encode(['fr' => 'Contenu', 'en' => 'Content']),
        'sort_order' => 1,
        'is_published' => false,
        'created_at' => now(),
        'updated_at' => now(),
    ], $overrides));
}

/**
 * @param  array<string, mixed>  $overrides
 */
function insertEquipment(array $overrides = []): int
{
    return DB::table('equipment')->insertGetId(array_merge([
        'code' => 'equipment-'.uniqid(),
        'label' => json_encode(['fr' => 'Banc de freinage', 'en' => 'Brake tester']),
        'sort_order' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ], $overrides));
}

function linkEquipmentToCentre(int $equipmentId, int $centreId): void
{
    DB::table('centre_equipment')->insert([
        'centre_id' => $centreId,
        'equipment_id' => $equipmentId,
    ]);
}

/**
 * @param  array<string, mixed>  $overrides
 */
function insertPageSeo(array $overrides = []): void
{
    DB::table('page_seo')->insert(array_merge([
        'page' => 'home',
        'seo_title' => json_encode(['fr' => 'Accueil', 'en' => 'Home']),
        'seo_description' => json_encode(['fr' => 'Description FR', 'en' => 'Description EN']),
        'updated_at' => now(),
    ], $overrides));
}

/**
 * @param  array<string, mixed>  $overrides
 */
function insertContactMessage(array $overrides = []): int
{
    return DB::table('contact_messages')->insertGetId(array_merge([
        'intent' => 'assistance',
        'status' => 'new',
        'name' => 'Marie N.',
        'phone_e164' => '+237653100801',
        'email' => 'marie@example.com',
        'subject' => 'Question horaires',
        'centre_id' => null,
        'message' => 'Bonjour, quels sont vos horaires le dimanche ?',
        'locale' => 'fr',
        'resolved_at' => null,
        'created_at' => now(),
        'updated_at' => now(),
    ], $overrides));
}

function insertContactAuthor(): int
{
    return DB::table('users')->insertGetId([
        'name' => 'Contact Staff',
        'email' => 'contact-staff-'.uniqid().'@example.com',
        'password' => bcrypt('secret'),
        'created_at' => now(),
        'updated_at' => now(),
    ]);
}

/**
 * @param  array<string, mixed>  $overrides
 */
function insertContactInternalNote(int $messageId, array $overrides = []): int
{
    return DB::table('contact_internal_notes')->insertGetId(array_merge([
        'contact_message_id' => $messageId,
        'author_id' => insertContactAuthor(),
        'body' => 'Rappeler la cliente demain matin.',
        'created_at' => now(),
        'updated_at' => now(),
    ], $overrides));
}

function submitContactPayload(array $overrides = []): SubmitContactMessageData
{
    return new SubmitContactMessageData(
        intent: $overrides['intent'] ?? ContactIntent::Assistance,
        name: $overrides['name'] ?? 'Marie N.',
        phone: $overrides['phone'] ?? '653100801',
        email: $overrides['email'] ?? 'marie@example.com',
        subject: $overrides['subject'] ?? 'Question horaires',
        message: $overrides['message'] ?? 'Bonjour, quels sont vos horaires le dimanche ?',
        locale: $overrides['locale'] ?? Locale::Fr,
        centreId: $overrides['centreId'] ?? null,
        honeypot: $overrides['honeypot'] ?? '',
        rateLimitKey: $overrides['rateLimitKey'] ?? 'test-contact-client',
    );
}

function seedAdminRoles(): void
{
    (new AdminRolesSeeder)->run();
}

function createAdminUser(string $role = 'super_admin', array $overrides = []): User
{
    seedAdminRoles();

    $user = User::factory()->create($overrides);
    $user->assignRole($role);

    return $user;
}
