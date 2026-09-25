<?php

namespace App\Filament\Resources\Operations\ContactMessages\ContactMessages\Pages;

use App\Filament\Resources\Operations\ContactMessages\ContactMessages\ContactMessageResource;
use Filament\Resources\Pages\ListRecords;

class ListContactMessages extends ListRecords
{
    protected static string $resource = ContactMessageResource::class;
}
