<?php

use App\Domain\Content\BilingualFields;
use App\Models\Content\FaqEntry;
use App\Models\Content\RoadSafetySection;

test('bilingual fields require both french and english strings BR-LANG-001', function () {
    expect(BilingualFields::isComplete(['fr' => 'Bonjour', 'en' => 'Hello']))->toBeTrue()
        ->and(BilingualFields::isComplete(['fr' => 'Bonjour', 'en' => '']))->toBeFalse()
        ->and(BilingualFields::isComplete(['fr' => 'Bonjour']))->toBeFalse()
        ->and(BilingualFields::isComplete(null))->toBeFalse();
});

test('faq entry is ready to publish only with complete bilingual question and answer', function () {
    $ready = new FaqEntry([
        'question' => ['fr' => 'Q?', 'en' => 'Q?'],
        'answer' => ['fr' => 'A', 'en' => 'A'],
    ]);
    $notReady = new FaqEntry([
        'question' => ['fr' => 'Q?', 'en' => ''],
        'answer' => ['fr' => 'A', 'en' => 'A'],
    ]);

    expect($ready->isReadyToPublish())->toBeTrue()
        ->and($notReady->isReadyToPublish())->toBeFalse();
});

test('road safety section is ready to publish only with complete bilingual title and body', function () {
    $ready = new RoadSafetySection([
        'title' => ['fr' => 'Freinage', 'en' => 'Braking'],
        'body' => ['fr' => 'Texte', 'en' => 'Text'],
    ]);
    $notReady = new RoadSafetySection([
        'title' => ['fr' => 'Freinage', 'en' => 'Braking'],
        'body' => ['fr' => 'Texte', 'en' => ''],
    ]);

    expect($ready->isReadyToPublish())->toBeTrue()
        ->and($notReady->isReadyToPublish())->toBeFalse();
});
