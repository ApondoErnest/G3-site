<?php

namespace App\Domain\ValueObjects;

use App\Domain\Enums\Locale;
use InvalidArgumentException;

final readonly class TranslatableCopy
{
    public function __construct(
        public string $fr,
        public string $en,
    ) {
        if ($this->fr === '' || $this->en === '') {
            throw new InvalidArgumentException('Both French and English copy are required.');
        }
    }

    /**
     * @param  array<string, mixed>  $value
     */
    public static function fromArray(array $value): self
    {
        if (! isset($value['fr'], $value['en']) || ! is_string($value['fr']) || ! is_string($value['en'])) {
            throw new InvalidArgumentException('Translatable copy must include fr and en string keys.');
        }

        return new self($value['fr'], $value['en']);
    }

    public function for(Locale $locale): string
    {
        return match ($locale) {
            Locale::Fr => $this->fr,
            Locale::En => $this->en,
        };
    }

    /**
     * @return array{fr: string, en: string}
     */
    public function toArray(): array
    {
        return [
            'fr' => $this->fr,
            'en' => $this->en,
        ];
    }
}
