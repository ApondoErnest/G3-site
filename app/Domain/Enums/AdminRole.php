<?php

namespace App\Domain\Enums;

enum AdminRole: string
{
    case SuperAdmin = 'super_admin';
    case OperationsAdmin = 'operations_admin';
    case CentreManager = 'centre_manager';
    case ReceptionOfficer = 'reception_officer';
    case ContentEditor = 'content_editor';

    /**
     * @return list<string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
