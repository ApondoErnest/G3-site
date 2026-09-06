<?php

namespace App\Models\Centre;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CentrePhone extends Model
{
    protected $fillable = [
        'centre_id',
        'label',
        'e164',
        'is_whatsapp',
        'sort_order',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_whatsapp' => 'boolean',
        ];
    }

    public function centre(): BelongsTo
    {
        return $this->belongsTo(Centre::class);
    }
}
