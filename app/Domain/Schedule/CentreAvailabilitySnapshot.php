<?php

namespace App\Domain\Schedule;

use App\Domain\Enums\LiveCentreState;
use Carbon\CarbonImmutable;

final readonly class CentreAvailabilitySnapshot
{
    public function __construct(
        public LiveCentreState $state,
        public bool $isOpenNow,
        public ?CarbonImmutable $nextCloseAt,
        public ?CarbonImmutable $nextOpenAt,
        public ?string $reasonKey = null,
        /** @var array{fr?: string, en?: string}|null */
        public ?array $reason = null,
    ) {}

    /**
     * @return array{
     *     state: string,
     *     isOpenNow: bool,
     *     nextCloseAt: string|null,
     *     nextOpenAt: string|null,
     *     reasonKey: string|null,
     *     reason: array{fr?: string, en?: string}|null
     * }
     */
    public function toCacheArray(): array
    {
        return [
            'state' => $this->state->value,
            'isOpenNow' => $this->isOpenNow,
            'nextCloseAt' => $this->nextCloseAt?->toIso8601String(),
            'nextOpenAt' => $this->nextOpenAt?->toIso8601String(),
            'reasonKey' => $this->reasonKey,
            'reason' => $this->reason,
        ];
    }

    /**
     * @param  array{
     *     state: string,
     *     isOpenNow: bool,
     *     nextCloseAt?: string|null,
     *     nextOpenAt?: string|null,
     *     reasonKey?: string|null,
     *     reason?: array{fr?: string, en?: string}|null
     * }  $payload
     */
    public static function fromCacheArray(array $payload): self
    {
        return new self(
            state: LiveCentreState::from($payload['state']),
            isOpenNow: (bool) $payload['isOpenNow'],
            nextCloseAt: filled($payload['nextCloseAt'] ?? null)
                ? CarbonImmutable::parse($payload['nextCloseAt'])
                : null,
            nextOpenAt: filled($payload['nextOpenAt'] ?? null)
                ? CarbonImmutable::parse($payload['nextOpenAt'])
                : null,
            reasonKey: $payload['reasonKey'] ?? null,
            reason: $payload['reason'] ?? null,
        );
    }
}
