<?php

test('locale files keep french and english keys in parity', function (): void {
    $files = ['public.php', 'admin.php'];

    foreach ($files as $file) {
        $french = flattenLocale(require lang_path('fr/'.$file));
        $english = flattenLocale(require lang_path('en/'.$file));

        expect(array_diff(array_keys($french), array_keys($english)))
            ->toBe([], "Missing English keys in {$file}");

        expect(array_diff(array_keys($english), array_keys($french)))
            ->toBe([], "Missing French keys in {$file}");
    }
});

test('localized placeholders match between french and english copy', function (): void {
    $files = ['public.php', 'admin.php'];

    foreach ($files as $file) {
        $french = flattenLocale(require lang_path('fr/'.$file));
        $english = flattenLocale(require lang_path('en/'.$file));

        foreach ($french as $key => $frenchValue) {
            $englishValue = $english[$key] ?? '';

            expect(localePlaceholders($englishValue))
                ->toBe(localePlaceholders($frenchValue), "Placeholder mismatch for {$file}:{$key}");
        }
    }
});

test('english ui copy follows the project glossary avoided terms', function (): void {
    $english = flattenLocale(require lang_path('en/public.php'))
        + flattenLocale(require lang_path('en/admin.php'));

    $copy = implode("\n", $english);

    expect($copy)
        ->not->toContain('MOT')
        ->not->toContain('Accreditation')
        ->not->toContain('accreditation')
        ->not->toContain('Center')
        ->not->toContain('center')
        ->not->toContain('Centers')
        ->not->toContain('centers')
        ->not->toContain('License')
        ->not->toContain('license')
        ->not->toContain('License plate')
        ->not->toContain('license plate')
        ->not->toContain('Tires')
        ->not->toContain('tires')
        ->not->toContain('Re-inspection')
        ->not->toContain('re-inspection')
        ->not->toContain('Bank holidays')
        ->not->toContain('bank holidays')
        ->not->toContain('Price list')
        ->not->toContain('price list')
        ->not->toContain('Booking a slot')
        ->not->toContain('booking a slot')
        ->not->toContain('Canceled')
        ->not->toContain('canceled')
        ->not->toContain('Garage')
        ->not->toContain('garage');
});

/**
 * @param  array<string, mixed>  $locale
 * @return array<string, string>
 */
function flattenLocale(array $locale, string $prefix = ''): array
{
    $flattened = [];

    foreach ($locale as $key => $value) {
        $path = $prefix === '' ? (string) $key : $prefix.'.'.$key;

        if (is_array($value)) {
            $flattened += flattenLocale($value, $path);

            continue;
        }

        $flattened[$path] = (string) $value;
    }

    return $flattened;
}

/**
 * @return list<string>
 */
function localePlaceholders(string $value): array
{
    preg_match_all('/:([A-Za-z_][A-Za-z0-9_]*)/', $value, $matches);

    $placeholders = array_values(array_unique($matches[1] ?? []));
    sort($placeholders);

    return $placeholders;
}
