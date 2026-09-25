<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('public pages expose one heading, the page language, and named embeds', function (string $locale, string $path) {
    $html = $this->get($path)->assertOk()->getContent();

    expect($html)
        ->toContain('<html lang="'.$locale.'"')
        ->toContain('href="#main-content"')
        ->toContain('id="main-content"');

    preg_match_all('/<h1\b/i', $html, $headings);

    expect($headings[0])->toHaveCount(1);

    preg_match_all('/<iframe\b[^>]*>/i', $html, $iframes);

    foreach ($iframes[0] as $iframe) {
        expect($iframe)->toMatch('/\btitle="[^"]+"/');
    }

    preg_match_all('/<img\b[^>]*>/i', $html, $images);

    foreach ($images[0] as $image) {
        expect($image)->toContain('alt=');
    }
})->with(function () {
    $locale = require dirname(__DIR__, 3).'/config/locale.php';
    $cases = [];

    foreach ($locale['supported'] as $code) {
        foreach ($locale['pages'] as $slugs) {
            $cases[] = [$code, '/'.$code.'/'.$slugs[$code]];
        }
    }

    return $cases;
});
