<?php

namespace App\Actions\Tariff\Data;

use App\Models\User;

final readonly class UpdateTariffVersionItemsData
{
    /**
     * @param  list<TariffItemData>  $items
     */
    public function __construct(
        public int $tariffVersionId,
        public array $items,
        public User $actor,
    ) {}
}
