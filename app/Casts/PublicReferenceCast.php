<?php

namespace App\Casts;

use App\Domain\ValueObjects\PublicReference;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

/**
 * @implements CastsAttributes<PublicReference, string>
 */
final class PublicReferenceCast implements CastsAttributes
{
    public function get(Model $model, string $key, mixed $value, array $attributes): ?PublicReference
    {
        if ($value === null) {
            return null;
        }

        return PublicReference::fromString((string) $value);
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): ?string
    {
        if ($value === null) {
            return null;
        }

        if ($value instanceof PublicReference) {
            return $value->value;
        }

        if (is_string($value)) {
            return PublicReference::fromString($value)->value;
        }

        throw new InvalidArgumentException('Public reference must be a string or PublicReference instance.');
    }
}
