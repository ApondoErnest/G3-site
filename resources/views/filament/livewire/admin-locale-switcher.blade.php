<div class="g3-locale-switcher" role="navigation" aria-label="{{ __('admin.locale.label') }}">
    @foreach (App\Support\AdminLocale::supported() as $locale)
        <button
            type="button"
            wire:click="switchLocale('{{ $locale }}')"
            wire:loading.attr="disabled"
            wire:target="switchLocale"
            @class([
                'g3-locale-switcher__link',
                'g3-locale-switcher__link--active' => App\Support\AdminLocale::isActive($locale),
            ])
            lang="{{ $locale }}"
        >
            {{ strtoupper($locale) }}
        </button>
    @endforeach
</div>
