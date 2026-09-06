<?php

namespace App\Models\Contact;

use App\Domain\Enums\ContactIntent;
use App\Domain\Enums\ContactStatus;
use App\Domain\Enums\Locale;
use App\Models\Centre\Centre;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ContactMessage extends Model
{
    protected $fillable = [
        'intent',
        'status',
        'name',
        'phone_e164',
        'email',
        'subject',
        'centre_id',
        'message',
        'locale',
        'resolved_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'intent' => ContactIntent::class,
            'status' => ContactStatus::class,
            'locale' => Locale::class,
            'resolved_at' => 'datetime',
        ];
    }

    public function centre(): BelongsTo
    {
        return $this->belongsTo(Centre::class);
    }

    public function internalNotes(): HasMany
    {
        return $this->hasMany(ContactInternalNote::class)->orderBy('created_at');
    }
}
