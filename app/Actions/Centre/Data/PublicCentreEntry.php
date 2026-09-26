<?php

namespace App\Actions\Centre\Data;

final readonly class PublicCentreEntry
{
    /**
     * @param  list<string>  $phonesDisplay
     * @param  list<string>  $phonesE164
     * @param  list<string>  $emails
     */
    public function __construct(
        public int $id,
        public string $key,
        public string $name,
        public string $shortName,
        public string $address,
        public string $landmark,
        public string $displayAddress,
        public ?string $primaryPhoneE164,
        public ?string $primaryPhoneDisplay,
        public string $phonesDisplayLine,
        public array $phonesDisplay,
        public array $phonesE164,
        public array $emails,
        public string $weekdayHours,
        public string $sundayHours,
        public ?string $closeTime,
        public string $holidayHours,
        public float $latitude,
        public float $longitude,
        public string $coordinates,
        public string $directionsUrl,
    ) {}
}
