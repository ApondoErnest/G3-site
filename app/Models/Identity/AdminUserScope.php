<?php

namespace App\Models\Identity;

use App\Models\Centre\Centre;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdminUserScope extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'centre_id',
        'created_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function centre(): BelongsTo
    {
        return $this->belongsTo(Centre::class);
    }
}
