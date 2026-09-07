<?php

namespace App\Filament\Resources\Operations\AppointmentRequests\AppointmentRequests\Pages;

use App\Filament\Resources\Operations\AppointmentRequests\AppointmentRequests\AppointmentRequestResource;
use Filament\Resources\Pages\ListRecords;

class ListAppointmentRequests extends ListRecords
{
    protected static string $resource = AppointmentRequestResource::class;
}
