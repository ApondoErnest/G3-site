@extends('layouts.public')

@section('content')
    <section class="border-b border-g3-border bg-g3-wall">
        <div class="g3-container py-14 lg:py-20">
            <div class="max-w-2xl">
                <p class="mb-3 inline-flex items-center gap-2 text-xs font-semibold uppercase tracking-widest text-g3-royal">
                    <span class="inline-block h-[3px] w-8 rounded-full bg-g3-orange" aria-hidden="true"></span>
                    {{ $pageTitle }}
                </p>

                <h1 class="font-display text-3xl font-bold tracking-tight text-g3-blue-deep sm:text-4xl">
                    {{ __('public.shell.placeholder_title') }}
                </h1>

                <p class="mt-4 text-base leading-relaxed text-g3-muted">
                    {{ __('public.shell.placeholder_body') }}
                </p>

                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="{{ \App\Support\PublicNavigation::pageUrl('appointment', $locale) }}" class="g3-btn-primary">
                        {{ __('public.cta.appointment') }}
                    </a>
                    <a href="{{ \App\Support\PublicNavigation::pageUrl('fees', $locale) }}" class="g3-btn-ghost">
                        {{ \App\Support\PublicNavigation::navLabel('fees') }}
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection
