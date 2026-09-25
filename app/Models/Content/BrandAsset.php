<?php

namespace App\Models\Content;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class BrandAsset extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'code',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('brand')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp']);
    }
}
