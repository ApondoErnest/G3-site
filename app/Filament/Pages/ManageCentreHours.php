<?php

namespace App\Filament\Pages;

use App\Actions\Schedule\ChangeCentreWeeklyHours;
use App\Actions\Schedule\Data\ChangeCentreWeeklyHoursData;
use App\Actions\Schedule\Data\WeeklyHoursData;
use App\Domain\Enums\Weekday;
use App\Models\Centre\Centre;
use App\Models\Centre\CentreWeeklyHours;
use App\Models\User;
use App\Support\AdminLocale;
use App\Support\CentreAccess;
use App\Support\Clock;
use App\Support\DisplayTime;
use BackedEnum;
use Carbon\CarbonImmutable;
use Filament\Actions\Action;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Pages\Concerns\CanUseDatabaseTransactions;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\Exceptions\Halt;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;
use Throwable;

/**
 * @property-read Schema $form
 */
class ManageCentreHours extends Page
{
    use CanUseDatabaseTransactions;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClock;

    protected static ?int $navigationSort = 20;

    protected string $view = 'filament.pages.manage-centre-hours';

    /**
     * @var array<string, mixed>|null
     */
    public ?array $data = [];

    public static function getNavigationGroup(): ?string
    {
        return __('admin.nav.groups.centres');
    }

    public static function getNavigationLabel(): string
    {
        return __('admin.hours.navigation');
    }

    public function getTitle(): string|Htmlable
    {
        return __('admin.hours.title');
    }

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user instanceof User
            && $user->hasRole(['super_admin', 'operations_admin', 'centre_manager']);
    }

    public function mount(): void
    {
        abort_unless(static::canAccess(), 403);

        $this->form->fill([
            'centre_id' => $this->defaultCentreId(),
            'weekly_hours' => $this->weeklyHoursState($this->defaultCentreId()),
        ]);
    }

    public function updatedDataCentreId(?int $centreId): void
    {
        if ($centreId === null) {
            return;
        }

        $this->data['weekly_hours'] = $this->weeklyHoursState($centreId);
    }

    public function defaultForm(Schema $schema): Schema
    {
        return $schema
            ->statePath('data');
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('admin.hours.sections.centre'))
                    ->schema([
                        Select::make('centre_id')
                            ->label(__('admin.hours.fields.centre'))
                            ->options(fn (): array => $this->centreOptions())
                            ->required()
                            ->live()
                            ->searchable(),
                    ]),
                Section::make(__('admin.hours.sections.week'))
                    ->schema([
                        Repeater::make('weekly_hours')
                            ->label(__('admin.hours.sections.week'))
                            ->schema([
                                Select::make('weekday')
                                    ->label(__('admin.hours.fields.weekday'))
                                    ->options(self::weekdayOptions())
                                    ->disabled()
                                    ->dehydrated(),
                                Toggle::make('is_open')
                                    ->label(__('admin.hours.fields.is_open'))
                                    ->live(),
                                Select::make('opens_at')
                                    ->label(__('admin.hours.fields.opens_at'))
                                    ->options(self::timeOptions())
                                    ->searchable()
                                    ->native(false)
                                    ->visible(fn (Get $get): bool => (bool) $get('is_open'))
                                    ->required(fn (Get $get): bool => (bool) $get('is_open')),
                                Select::make('closes_at')
                                    ->label(__('admin.hours.fields.closes_at'))
                                    ->options(self::timeOptions())
                                    ->searchable()
                                    ->native(false)
                                    ->visible(fn (Get $get): bool => (bool) $get('is_open'))
                                    ->required(fn (Get $get): bool => (bool) $get('is_open')),
                            ])
                            ->columns(4)
                            ->addable(false)
                            ->deletable(false)
                            ->reorderable(false),
                    ]),
            ]);
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                Form::make([EmbeddedSchema::make('form')])
                    ->id('form')
                    ->livewireSubmitHandler('save')
                    ->footer([
                        Actions::make([
                            Action::make('save')
                                ->label(__('admin.hours.actions.save'))
                                ->submit('save'),
                        ]),
                    ]),
            ]);
    }

    public function save(): void
    {
        try {
            $this->beginDatabaseTransaction();

            $data = $this->form->getState();
            $centreId = (int) $data['centre_id'];
            $centre = Centre::query()->findOrFail($centreId);

            abort_unless(
                auth()->user() instanceof User && CentreAccess::userCanAccessCentre(auth()->user(), $centre),
                403,
            );

            $weeklyHours = collect($data['weekly_hours'] ?? [])
                ->map(fn (array $row): WeeklyHoursData => new WeeklyHoursData(
                    weekday: Weekday::from((int) $row['weekday']),
                    isOpen: (bool) ($row['is_open'] ?? false),
                    opensAt: filled($row['opens_at'] ?? null) ? self::formatTime($row['opens_at']) : null,
                    closesAt: filled($row['closes_at'] ?? null) ? self::formatTime($row['closes_at']) : null,
                ))
                ->all();

            app(ChangeCentreWeeklyHours::class)(new ChangeCentreWeeklyHoursData(
                centreId: $centreId,
                weeklyHours: $weeklyHours,
                actor: auth()->user(),
            ));

            $this->commitDatabaseTransaction();
        } catch (Halt $exception) {
            $exception->shouldRollbackDatabaseTransaction()
                ? $this->rollBackDatabaseTransaction()
                : $this->commitDatabaseTransaction();

            return;
        } catch (Throwable $exception) {
            $this->rollBackDatabaseTransaction();

            throw $exception;
        }

        Notification::make()
            ->success()
            ->title(__('admin.hours.notifications.saved'))
            ->send();
    }

    /**
     * @return array<int, string>
     */
    private function centreOptions(): array
    {
        $user = auth()->user();

        if (! $user instanceof User) {
            return [];
        }

        return Centre::query()
            ->active()
            ->orderBy('sort_order')
            ->get()
            ->filter(fn (Centre $centre): bool => CentreAccess::userCanAccessCentre($user, $centre))
            ->mapWithKeys(fn (Centre $centre): array => [
                $centre->id => $centre->translatedName(AdminLocale::current()),
            ])
            ->all();
    }

    private function defaultCentreId(): int
    {
        $options = $this->centreOptions();

        return (int) array_key_first($options);
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function weeklyHoursState(int $centreId): array
    {
        $existing = CentreWeeklyHours::query()
            ->where('centre_id', $centreId)
            ->get()
            ->keyBy(fn (CentreWeeklyHours $hours) => $hours->weekday->value);

        return collect(Weekday::cases())
            ->map(function (Weekday $weekday) use ($existing): array {
                $hours = $existing->get($weekday->value);

                return [
                    'weekday' => $weekday->value,
                    'is_open' => $hours?->is_open ?? false,
                    'opens_at' => $hours?->opens_at ? substr((string) $hours->opens_at, 0, 5) : null,
                    'closes_at' => $hours?->closes_at ? substr((string) $hours->closes_at, 0, 5) : null,
                ];
            })
            ->all();
    }

    /**
     * @return array<int, string>
     */
    private static function weekdayOptions(): array
    {
        return collect(Weekday::cases())
            ->mapWithKeys(fn (Weekday $weekday): array => [
                $weekday->value => __('admin.hours.weekdays.'.$weekday->value),
            ])
            ->all();
    }

    /**
     * @return array<string, string>
     */
    private static function timeOptions(): array
    {
        $locale = AdminLocale::current();
        $options = [];
        $cursor = CarbonImmutable::parse('00:00:00', Clock::displayTimezone());

        for ($step = 0; $step < 96; $step++) {
            $options[$cursor->format('H:i')] = DisplayTime::format($cursor, $locale);
            $cursor = $cursor->addMinutes(15);
        }

        return $options;
    }

    private static function formatTime(mixed $value): ?string
    {
        if (! filled($value)) {
            return null;
        }

        return strlen((string) $value) === 5
            ? (string) $value.':00'
            : (string) $value;
    }
}
