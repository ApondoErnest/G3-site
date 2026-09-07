<?php

namespace App\Support;

use App\Domain\Enums\AppointmentStatus;
use App\Domain\Enums\ContactStatus;

final class AdminLabels
{
    public static function appointmentStatus(AppointmentStatus $status): string
    {
        return match ($status) {
            AppointmentStatus::Received => __('admin.status.appointment.received'),
            AppointmentStatus::UnderReview => __('admin.status.appointment.under_review'),
            AppointmentStatus::Confirmed => __('admin.status.appointment.confirmed'),
            AppointmentStatus::ModificationRequested => __('admin.status.appointment.modification_requested'),
            AppointmentStatus::Completed => __('admin.status.appointment.completed'),
            AppointmentStatus::Cancelled => __('admin.status.appointment.cancelled'),
        };
    }

    public static function appointmentStatusClass(AppointmentStatus $status): string
    {
        return match ($status) {
            AppointmentStatus::Received => 'g3-status--received',
            AppointmentStatus::UnderReview, AppointmentStatus::ModificationRequested => 'g3-status--review',
            AppointmentStatus::Confirmed => 'g3-status--confirmed',
            AppointmentStatus::Completed => 'g3-status--confirmed',
            AppointmentStatus::Cancelled => 'g3-status--cancelled',
        };
    }

    public static function contactStatus(ContactStatus $status): string
    {
        return match ($status) {
            ContactStatus::New => __('admin.status.contact.new'),
            ContactStatus::InProgress => __('admin.status.contact.in_progress'),
            ContactStatus::Resolved => __('admin.status.contact.resolved'),
        };
    }
}
