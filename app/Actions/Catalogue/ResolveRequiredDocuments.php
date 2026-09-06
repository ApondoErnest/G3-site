<?php

namespace App\Actions\Catalogue;

use App\Actions\Catalogue\Data\RequiredDocumentEntry;
use App\Models\Catalogue\RequiredDocument;

final class ResolveRequiredDocuments
{
    /**
     * @return list<RequiredDocumentEntry>
     */
    public function __invoke(int $serviceId, int $vehicleCategoryId): array
    {
        return RequiredDocument::query()
            ->forBooking($serviceId, $vehicleCategoryId)
            ->get()
            ->map(fn (RequiredDocument $document) => new RequiredDocumentEntry(
                id: $document->id,
                label: $document->label,
                vehicleCategoryId: $document->vehicle_category_id,
                serviceId: $document->service_id,
            ))
            ->values()
            ->all();
    }
}
