@php
    use App\Models\Appointment\AppointmentRequest;
    use App\Models\Contact\ContactMessage;
    use App\Models\Tariff\TariffVersion;

    $stats = $dashboard->stats;
@endphp

<div class="g3-dashboard">
    @if ($stats)
        <div class="g3-stats-grid">
            @can('viewAny', AppointmentRequest::class)
                <article class="g3-stat-card g3-stat-card--accent">
                    <p class="g3-stat-card__label">{{ __('admin.dashboard.stats.new_requests') }}</p>
                    <p class="g3-stat-card__value g3-num">{{ $stats->newRequestsToday }}</p>
                    <p class="g3-stat-card__meta">{{ $stats->newRequestsMeta }}</p>
                </article>
                <article class="g3-stat-card">
                    <p class="g3-stat-card__label">{{ __('admin.dashboard.stats.in_processing') }}</p>
                    <p class="g3-stat-card__value g3-num">{{ $stats->inProcessing }}</p>
                    <p class="g3-stat-card__meta">{{ $stats->inProcessingMeta }}</p>
                </article>
            @endcan

            @can('viewAny', \App\Models\Centre\Centre::class)
                <article class="g3-stat-card g3-stat-card--success">
                    <p class="g3-stat-card__label">{{ __('admin.dashboard.stats.centres_open') }}</p>
                    <p class="g3-stat-card__value g3-num">{{ $stats->centresOpen }}/{{ $stats->centresTotal }}</p>
                    <p class="g3-stat-card__meta">{{ $stats->centresMeta }}</p>
                </article>
            @endcan

            @can('viewAny', ContactMessage::class)
                <article class="g3-stat-card">
                    <p class="g3-stat-card__label">{{ __('admin.dashboard.stats.messages') }}</p>
                    <p class="g3-stat-card__value g3-num">{{ $stats->unhandledMessages }}</p>
                    <p class="g3-stat-card__meta">{{ $stats->messagesMeta }}</p>
                </article>
            @endcan
        </div>
    @endif

    <div class="g3-dashboard-grid g3-dashboard-grid--2-1">
        @can('viewAny', AppointmentRequest::class)
            <section class="g3-panel">
                <header class="g3-panel__head">
                    <h2>{{ __('admin.dashboard.queue.title') }}</h2>
                    <span class="g3-panel__hint">{{ __('admin.dashboard.queue.hint') }}</span>
                </header>
                <div class="g3-panel__body g3-panel__body--flush">
                    @if ($dashboard->queue === [])
                        <p class="g3-empty">{{ __('admin.dashboard.queue.empty') }}</p>
                    @else
                        <table class="g3-data-table">
                            <thead>
                                <tr>
                                    <th>{{ __('admin.dashboard.queue.reference') }}</th>
                                    <th>{{ __('admin.dashboard.queue.requester') }}</th>
                                    <th>{{ __('admin.dashboard.queue.centre') }}</th>
                                    <th>{{ __('admin.dashboard.queue.status') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dashboard->queue as $item)
                                    <tr>
                                        <td class="g3-mono">{{ $item->reference }}</td>
                                        <td>{{ $item->requester }}</td>
                                        <td>{{ $item->centreName }}</td>
                                        <td>
                                            <span @class(['g3-status', $item->statusClass])>
                                                <span class="g3-status__dot" aria-hidden="true"></span>
                                                {{ $item->statusLabel }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </section>
        @endcan

        @can('viewAny', \App\Models\Centre\Centre::class)
            <section class="g3-panel">
                <header class="g3-panel__head">
                    <h2>{{ __('admin.dashboard.centres.title') }}</h2>
                    <span class="g3-panel__hint">{{ __('admin.dashboard.centres.hint') }}</span>
                </header>
                <div class="g3-centre-status-grid">
                    @foreach ($dashboard->centres as $centre)
                        <article class="g3-centre-status">
                            <h3 class="g3-centre-status__name">{{ $centre->name }}</h3>
                            <span @class(['g3-status', $centre->isOpen ? 'g3-status--open' : 'g3-status--closed'])>
                                <span class="g3-status__dot" aria-hidden="true"></span>
                                {{ $centre->statusLabel }}
                            </span>
                            <p class="g3-centre-status__hours">{{ $centre->hoursLine }}</p>
                        </article>
                    @endforeach
                </div>
                @if ($dashboard->nextException)
                    <footer class="g3-panel__footer">
                        <p class="g3-panel__footer-label">{{ $dashboard->nextException->label }}</p>
                        <p class="g3-panel__footer-text">{{ $dashboard->nextException->detail }}</p>
                    </footer>
                @endif
            </section>
        @endcan
    </div>

    <div class="g3-dashboard-grid g3-dashboard-grid--1-1">
        @if ($dashboard->tariff && auth()->user()->can('viewAny', TariffVersion::class))
            <section class="g3-panel">
                <header class="g3-panel__head">
                    <h2>{{ __('admin.dashboard.tariff.title') }}</h2>
                    <span class="g3-panel__hint">{{ __('admin.dashboard.tariff.hint') }}</span>
                </header>
                <div class="g3-panel__body g3-panel__body--padded">
                    <div class="g3-tariff-summary">
                        <span class="g3-status g3-status--published">
                            <span class="g3-status__dot" aria-hidden="true"></span>
                            {{ __('admin.dashboard.tariff.published') }}
                        </span>
                        <span class="g3-tariff-summary__meta">{{ $dashboard->tariff->effectiveLine }}</span>
                    </div>
                    <p class="g3-tariff-summary__detail">
                        @if ($dashboard->tariff->itemCount > 0)
                            {{ __('admin.dashboard.tariff.matrix', ['count' => $dashboard->tariff->itemCount]) }}
                        @else
                            {{ $dashboard->tariff->label }}
                        @endif
                    </p>
                </div>
            </section>
        @endif

        @if ($dashboard->activity !== [])
            <section class="g3-panel">
                <header class="g3-panel__head">
                    <h2>{{ __('admin.dashboard.activity.title') }}</h2>
                    <span class="g3-panel__hint">{{ __('admin.dashboard.activity.hint') }}</span>
                </header>
                <div class="g3-timeline">
                    @foreach ($dashboard->activity as $event)
                        <div class="g3-timeline__item">
                            <div @class(['g3-timeline__dot', 'g3-timeline__dot--done' => $event->isDone])>
                                @if ($event->isDone)
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M16.704 5.29a1 1 0 0 1 0 1.42l-8 8a1 1 0 0 1-1.42 0l-4-4a1 1 0 1 1 1.42-1.42L8 12.59l7.29-7.29a1 1 0 0 1 1.414 0Z" clip-rule="evenodd"/></svg>
                                @endif
                            </div>
                            <div>
                                <p class="g3-timeline__title">{{ $event->title }}</p>
                                <p class="g3-timeline__meta">{{ $event->meta }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif
    </div>
</div>
