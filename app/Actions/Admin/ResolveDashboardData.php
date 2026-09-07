<?php

namespace App\Actions\Admin;

use App\Actions\Schedule\ResolveAllCentresAvailability;
use App\Actions\Tariff\ResolveEffectiveTariff;
use App\Data\Admin\DashboardActivityItem;
use App\Data\Admin\DashboardCentreCard;
use App\Data\Admin\DashboardData;
use App\Data\Admin\DashboardExceptionSummary;
use App\Data\Admin\DashboardQueueItem;
use App\Data\Admin\DashboardStats;
use App\Data\Admin\DashboardTariffSummary;
use App\Domain\Enums\AppointmentStatus;
use App\Domain\Enums\ContactStatus;
use App\Domain\Schedule\CentreAvailabilitySnapshot;
use App\Models\Appointment\AppointmentRequest;
use App\Models\Centre\Centre;
use App\Models\Centre\ScheduleException;
use App\Models\Contact\ContactMessage;
use App\Models\Tariff\TariffVersion;
use App\Models\User;
use App\Support\AdminLabels;
use App\Support\AdminLocale;
use App\Support\Clock;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

final class ResolveDashboardData
{
    public function __construct(
        private ResolveAllCentresAvailability $resolveAllCentresAvailability,
        private ResolveEffectiveTariff $resolveEffectiveTariff,
    ) {}

    public function __invoke(User $user): DashboardData
    {
        $now = Clock::nowDisplay();
        $scopedCentreIds = $this->scopedCentreIds($user);

        return new DashboardData(
            displayDateLine: $this->formatDisplayDateLine($now),
            stats: $this->resolveStats($user, $now, $scopedCentreIds),
            queue: $this->resolveQueue($user, $scopedCentreIds),
            centres: $this->resolveCentreCards($user, $scopedCentreIds, $now),
            nextException: $this->resolveNextException($user, $scopedCentreIds, $now),
            tariff: $this->resolveTariffSummary($user),
            activity: $this->resolveActivity($user, $scopedCentreIds),
        );
    }

    /**
     * @return list<int>|null
     */
    private function scopedCentreIds(User $user): ?array
    {
        if ($user->hasRole(['super_admin', 'operations_admin'])) {
            return null;
        }

        $ids = $user->centreScopes()->pluck('centre_id')->all();

        return $ids === [] ? [-1] : $ids;
    }

    private function formatDisplayDateLine(CarbonImmutable $now): string
    {
        $locale = AdminLocale::current()->value;
        $formatted = $now->locale($locale)->isoFormat('dddd D MMMM YYYY');
        $city = match (Clock::displayTimezone()) {
            'Africa/Douala' => __('admin.dashboard.display_city'),
            default => Clock::displayTimezone(),
        };

        return ucfirst($formatted).' · '.$city;
    }

    private function resolveStats(User $user, CarbonImmutable $now, ?array $scopedCentreIds): ?DashboardStats
    {
        $canAppointments = $user->can('viewAny', AppointmentRequest::class);
        $canContacts = $user->can('viewAny', ContactMessage::class);
        $canCentres = $user->can('viewAny', Centre::class);

        if (! $canAppointments && ! $canContacts && ! $canCentres) {
            return null;
        }

        $newToday = 0;
        $newMeta = __('admin.dashboard.stats.no_requests_today');
        $inProcessing = 0;
        $openCount = 0;
        $totalCentres = 0;
        $centresMeta = '—';
        $messages = 0;

        if ($canAppointments) {
            $todayReceived = AppointmentRequest::query()
                ->where('status', AppointmentStatus::Received)
                ->whereDate('created_at', $now->toDateString())
                ->when($scopedCentreIds !== null, fn (Builder $q) => $q->whereIn('centre_id', $scopedCentreIds))
                ->with('centre')
                ->get();

            $newToday = $todayReceived->count();
            $newMeta = $this->formatCentreSplit($todayReceived);

            $inProcessing = AppointmentRequest::query()
                ->whereIn('status', [
                    AppointmentStatus::UnderReview,
                    AppointmentStatus::ModificationRequested,
                ])
                ->when($scopedCentreIds !== null, fn (Builder $q) => $q->whereIn('centre_id', $scopedCentreIds))
                ->count();
        }

        if ($canCentres) {
            $centres = Centre::query()
                ->active()
                ->when($scopedCentreIds !== null, fn (Builder $q) => $q->whereIn('id', $scopedCentreIds))
                ->orderBy('sort_order')
                ->get();

            $snapshots = ($this->resolveAllCentresAvailability)($now);
            $totalCentres = $centres->count();
            $openCount = $centres
                ->filter(fn (Centre $centre): bool => ($snapshots[$centre->id] ?? null)?->isOpenNow ?? false)
                ->count();

            $centresMeta = $this->formatClosingTimesMeta($centres, $snapshots);
        }

        if ($canContacts) {
            $messages = ContactMessage::query()
                ->whereIn('status', [ContactStatus::New, ContactStatus::InProgress])
                ->when($scopedCentreIds !== null, fn (Builder $q) => $q->where(function (Builder $inner) use ($scopedCentreIds): void {
                    $inner->whereNull('centre_id')
                        ->orWhereIn('centre_id', $scopedCentreIds);
                }))
                ->count();
        }

        return new DashboardStats(
            newRequestsToday: $newToday,
            newRequestsMeta: $newMeta,
            inProcessing: $inProcessing,
            inProcessingMeta: __('admin.dashboard.stats.in_processing_meta'),
            centresOpen: $openCount,
            centresTotal: $totalCentres,
            centresMeta: $centresMeta,
            unhandledMessages: $messages,
            messagesMeta: $messages === 1
                ? __('admin.dashboard.stats.message_unhandled')
                : __('admin.dashboard.stats.messages_unhandled'),
        );
    }

    /**
     * @return list<DashboardQueueItem>
     */
    private function resolveQueue(User $user, ?array $scopedCentreIds): array
    {
        if (! $user->can('viewAny', AppointmentRequest::class)) {
            return [];
        }

        return AppointmentRequest::query()
            ->with('centre')
            ->whereIn('status', [AppointmentStatus::Received, AppointmentStatus::UnderReview])
            ->when($scopedCentreIds !== null, fn (Builder $q) => $q->whereIn('centre_id', $scopedCentreIds))
            ->orderBy('created_at')
            ->limit(8)
            ->get()
            ->map(fn (AppointmentRequest $request): DashboardQueueItem => new DashboardQueueItem(
                id: $request->id,
                reference: (string) $request->public_reference,
                requester: $this->formatRequester($request->contact_name, $request->contact_phone_e164),
                centreName: $request->centre?->translatedName(AdminLocale::current()) ?? '—',
                statusLabel: AdminLabels::appointmentStatus($request->status),
                statusClass: AdminLabels::appointmentStatusClass($request->status),
            ))
            ->all();
    }

    /**
     * @return list<DashboardCentreCard>
     */
    private function resolveCentreCards(User $user, ?array $scopedCentreIds, CarbonImmutable $now): array
    {
        if (! $user->can('viewAny', Centre::class)) {
            return [];
        }

        $centres = Centre::query()
            ->active()
            ->with('weeklyHours')
            ->when($scopedCentreIds !== null, fn (Builder $q) => $q->whereIn('id', $scopedCentreIds))
            ->orderBy('sort_order')
            ->get();

        $snapshots = ($this->resolveAllCentresAvailability)($now);

        return $centres
            ->map(function (Centre $centre) use ($snapshots, $now): DashboardCentreCard {
                $snapshot = $snapshots[$centre->id] ?? null;
                $isOpen = $snapshot?->isOpenNow ?? false;

                return new DashboardCentreCard(
                    name: $centre->translatedName(AdminLocale::current()),
                    isOpen: $isOpen,
                    statusLabel: $isOpen ? __('admin.status.open') : __('admin.status.closed'),
                    hoursLine: $this->formatCentreHoursLine($centre, $snapshot, $now),
                );
            })
            ->all();
    }

    private function resolveNextException(User $user, ?array $scopedCentreIds, CarbonImmutable $now): ?DashboardExceptionSummary
    {
        if (! $user->can('viewAny', ScheduleException::class)) {
            return null;
        }

        $exception = ScheduleException::query()
            ->with('centre')
            ->where('starts_on', '>=', $now->toDateString())
            ->when($scopedCentreIds !== null, fn (Builder $q) => $q->where(function (Builder $inner) use ($scopedCentreIds): void {
                $inner->where('applies_to_all_centres', true)
                    ->orWhereIn('centre_id', $scopedCentreIds);
            }))
            ->orderBy('starts_on')
            ->first();

        if ($exception === null) {
            return new DashboardExceptionSummary(
                label: __('admin.dashboard.centres.next_exception'),
                detail: __('admin.dashboard.centres.no_exception'),
            );
        }

        $locale = AdminLocale::current()->value;

        $centreLabel = $exception->applies_to_all_centres
            ? __('admin.dashboard.centres.all_centres')
            : ($exception->centre?->translatedName(AdminLocale::current()) ?? __('admin.dashboard.centres.centre_fallback'));

        $reason = $exception->reason[$locale]
            ?? $exception->reason['fr']
            ?? $exception->reason['en']
            ?? __('admin.dashboard.centres.exception_reason_fallback');
        $state = $exception->is_open
            ? __('admin.dashboard.centres.exception_open')
            : __('admin.dashboard.centres.exception_closed');

        return new DashboardExceptionSummary(
            label: __('admin.dashboard.centres.next_exception'),
            detail: sprintf(
                '%s · %s · %s',
                $centreLabel,
                $exception->starts_on->locale($locale)->isoFormat('D MMM YYYY'),
                $state.' — '.$reason,
            ),
        );
    }

    private function resolveTariffSummary(User $user): ?DashboardTariffSummary
    {
        if (! $user->can('viewAny', TariffVersion::class)) {
            return null;
        }

        $result = ($this->resolveEffectiveTariff)();

        if ($result->isEmpty || $result->version === null) {
            return new DashboardTariffSummary(
                label: __('admin.dashboard.tariff.none'),
                effectiveLine: __('admin.dashboard.tariff.publish_hint'),
                itemCount: 0,
            );
        }

        $version = $result->version;

        return new DashboardTariffSummary(
            label: $version->label,
            effectiveLine: __('admin.dashboard.tariff.active_version', ['date' => $version->effectiveFrom]),
            itemCount: count($result->items),
        );
    }

    /**
     * @return list<DashboardActivityItem>
     */
    private function resolveActivity(User $user, ?array $scopedCentreIds): array
    {
        $events = collect();

        if ($user->can('viewAny', AppointmentRequest::class)) {
            AppointmentRequest::query()
                ->with('centre')
                ->when($scopedCentreIds !== null, fn (Builder $q) => $q->whereIn('centre_id', $scopedCentreIds))
                ->latest('created_at')
                ->limit(5)
                ->get()
                ->each(function (AppointmentRequest $request) use ($events): void {
                    $locale = AdminLocale::current()->value;
                    $events->push([
                        'at' => $request->created_at,
                        'item' => new DashboardActivityItem(
                            title: __('admin.dashboard.activity.appointment_received', [
                                'reference' => $request->public_reference,
                            ]),
                            meta: $request->created_at?->locale($locale)->diffForHumans().' · '.($request->centre?->translatedName(AdminLocale::current()) ?? '—'),
                            isDone: true,
                        ),
                    ]);
                });
        }

        if ($user->can('viewAny', ContactMessage::class)) {
            ContactMessage::query()
                ->when($scopedCentreIds !== null, fn (Builder $q) => $q->where(function (Builder $inner) use ($scopedCentreIds): void {
                    $inner->whereNull('centre_id')
                        ->orWhereIn('centre_id', $scopedCentreIds);
                }))
                ->latest('created_at')
                ->limit(5)
                ->get()
                ->each(function (ContactMessage $message) use ($events): void {
                    $locale = AdminLocale::current()->value;
                    $events->push([
                        'at' => $message->created_at,
                        'item' => new DashboardActivityItem(
                            title: __('admin.dashboard.activity.contact_message', [
                                'status' => AdminLabels::contactStatus($message->status),
                            ]),
                            meta: $message->created_at?->locale($locale)->diffForHumans(),
                            isDone: $message->status === ContactStatus::Resolved,
                        ),
                    ]);
                });
        }

        return $events
            ->sortByDesc('at')
            ->take(6)
            ->pluck('item')
            ->values()
            ->all();
    }

    /**
     * @param  Collection<int, AppointmentRequest>  $requests
     */
    private function formatCentreSplit(Collection $requests): string
    {
        if ($requests->isEmpty()) {
            return __('admin.dashboard.stats.no_requests_today');
        }

        return $requests
            ->groupBy('centre_id')
            ->map(function (Collection $group): string {
                $name = $group->first()?->centre?->translatedName(AdminLocale::current())
                    ?? __('admin.dashboard.centres.centre_fallback');

                return $group->count().' '.$name;
            })
            ->implode(' · ');
    }

    /**
     * @param  Collection<int, Centre>  $centres
     * @param  array<int, CentreAvailabilitySnapshot>  $snapshots
     */
    private function formatClosingTimesMeta(Collection $centres, array $snapshots): string
    {
        if ($centres->isEmpty()) {
            return '—';
        }

        $times = $centres
            ->map(function (Centre $centre) use ($snapshots): ?string {
                $closeAt = $snapshots[$centre->id]?->nextCloseAt;

                return $closeAt !== null
                    ? $centre->translatedName(AdminLocale::current()).' '.$closeAt->format('H:i')
                    : null;
            })
            ->filter()
            ->values();

        if ($times->isEmpty()) {
            return __('admin.dashboard.stats.variable_hours');
        }

        return __('admin.dashboard.stats.next_closings', [
            'times' => $times->implode(' / '),
        ]);
    }

    private function formatRequester(?string $name, ?string $phoneE164): string
    {
        $shortName = $this->shortName($name);
        $maskedPhone = $this->maskPhone($phoneE164);

        return $shortName.' · '.$maskedPhone;
    }

    private function shortName(?string $name): string
    {
        if (blank($name)) {
            return '—';
        }

        $parts = preg_split('/\s+/', trim($name), 2);

        if ($parts === false || count($parts) === 1) {
            return $parts[0];
        }

        return $parts[0].' '.mb_substr($parts[1], 0, 1).'.';
    }

    private function maskPhone(?string $e164): string
    {
        if (blank($e164)) {
            return '—';
        }

        $digits = preg_replace('/\D/', '', $e164) ?? '';

        if (str_starts_with($e164, '+237') && strlen($digits) > 3) {
            $national = substr($digits, 3);

            return substr($national, 0, 3).'…'.substr($national, -3);
        }

        if (strlen($digits) < 6) {
            return $e164;
        }

        return substr($digits, 0, 3).'…'.substr($digits, -3);
    }

    private function formatCentreHoursLine(
        Centre $centre,
        ?CentreAvailabilitySnapshot $snapshot,
        CarbonImmutable $now,
    ): string {
        $weekly = $this->summarizeWeeklyHours($centre);

        if ($snapshot?->isOpenNow && $snapshot->nextCloseAt !== null) {
            return __('admin.dashboard.centres.closing_at', [
                'time' => $snapshot->nextCloseAt->format('H:i'),
                'weekly' => $weekly,
            ]);
        }

        if ($snapshot?->nextOpenAt !== null) {
            return __('admin.dashboard.centres.next_opening_at', [
                'when' => $snapshot->nextOpenAt->locale(AdminLocale::current()->value)->isoFormat('ddd HH:mm'),
                'weekly' => $weekly,
            ]);
        }

        return $weekly;
    }

    private function summarizeWeeklyHours(Centre $centre): string
    {
        $openDays = $centre->weeklyHours
            ->filter(fn ($row) => $row->is_open)
            ->sortBy('weekday');

        if ($openDays->isEmpty()) {
            return __('admin.dashboard.centres.hours_undefined');
        }

        $opensAt = substr((string) $openDays->first()->opens_at, 0, 5);
        $closesAt = substr((string) $openDays->max('closes_at'), 0, 5);

        $weekdays = $openDays->pluck('weekday')->map(fn ($day) => $day->value)->all();
        $dayRange = $this->formatWeekdayRange($weekdays);

        return $dayRange.' '.$opensAt.'–'.$closesAt;
    }

    /**
     * @param  list<int>  $weekdays
     */
    private function formatWeekdayRange(array $weekdays): string
    {
        sort($weekdays);

        if ($weekdays === [1, 2, 3, 4, 5, 6]) {
            return __('admin.dashboard.weekdays.mon_sat');
        }

        if ($weekdays === [1, 2, 3, 4, 5]) {
            return __('admin.dashboard.weekdays.mon_fri');
        }

        if ($weekdays === [1, 2, 3, 4, 5, 6, 7]) {
            return __('admin.dashboard.weekdays.mon_sun');
        }

        $labels = [
            1 => __('admin.dashboard.weekdays.mon'),
            2 => __('admin.dashboard.weekdays.tue'),
            3 => __('admin.dashboard.weekdays.wed'),
            4 => __('admin.dashboard.weekdays.thu'),
            5 => __('admin.dashboard.weekdays.fri'),
            6 => __('admin.dashboard.weekdays.sat'),
            7 => __('admin.dashboard.weekdays.sun'),
        ];

        return implode(', ', array_map(fn (int $day): string => $labels[$day] ?? (string) $day, $weekdays));
    }
}
