<?php

namespace App\Actions\Content;

use App\Actions\Content\Data\PublicTeamMemberEntry;
use App\Models\Content\TeamMember;
use App\Support\CacheKeys;
use Illuminate\Support\Facades\Cache;

final class ResolvePublicTeamMembers
{
    /**
     * @return list<PublicTeamMemberEntry>
     */
    public function __invoke(): array
    {
        /** @var list<PublicTeamMemberEntry> $entries */
        $entries = Cache::remember(
            CacheKeys::teamPublic(),
            CacheKeys::contentTtlSeconds(),
            fn () => TeamMember::query()
                ->displayPublicly()
                ->ordered()
                ->get()
                ->map(fn (TeamMember $member) => new PublicTeamMemberEntry(
                    id: $member->id,
                    name: $member->name,
                    roleTitle: $member->role_title,
                    bio: $member->bio,
                ))
                ->values()
                ->all(),
        );

        return $entries;
    }
}
