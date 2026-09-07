<?php

namespace App\Filament\Resources\Content\ContentBlocks\ContentBlocks\Pages;

use App\Filament\Resources\Content\ContentBlocks\ContentBlocks\ContentBlockResource;
use Filament\Resources\Pages\CreateRecord;

class CreateContentBlock extends CreateRecord
{
    protected static string $resource = ContentBlockResource::class;

    /**
     * @param  array<string, mixed>  $data
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $content = [
            'fr' => [
                'headline' => (string) ($data['content']['fr']['headline'] ?? ''),
                'body' => (string) ($data['content']['fr']['body'] ?? ''),
            ],
            'en' => [
                'headline' => (string) ($data['content']['en']['headline'] ?? ''),
                'body' => (string) ($data['content']['en']['body'] ?? ''),
            ],
        ];

        $data['content'] = $content;
        $data['schema_version'] = 1;
        $data['locale_status'] = [
            'fr' => filled($content['fr']['headline']) ? 'complete' : 'incomplete',
            'en' => filled($content['en']['headline']) ? 'complete' : 'incomplete',
        ];
        $data['is_published'] = false;

        return $data;
    }
}
