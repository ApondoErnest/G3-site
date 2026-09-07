<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;

final class CatalogueCache
{
    public static function forgetPublished(): void
    {
        Cache::forget(CacheKeys::catalogueServices());
        Cache::forget(CacheKeys::catalogueCategories());
    }
}
