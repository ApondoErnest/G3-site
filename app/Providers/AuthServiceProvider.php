<?php

namespace App\Providers;

use App\Models\Appointment\AppointmentRequest;
use App\Models\Catalogue\RequiredDocument;
use App\Models\Catalogue\Service;
use App\Models\Catalogue\VehicleCategory;
use App\Models\Centre\Centre;
use App\Models\Centre\OperationalAlert;
use App\Models\Centre\ScheduleException;
use App\Models\Contact\ContactMessage;
use App\Models\Content\ContentBlock;
use App\Models\Content\FaqEntry;
use App\Models\Content\PageSeo;
use App\Models\Content\RoadSafetySection;
use App\Models\Content\TeamMember;
use App\Models\Tariff\TariffVersion;
use App\Models\User;
use App\Policies\AppointmentRequestPolicy;
use App\Policies\CentrePolicy;
use App\Policies\ContactMessagePolicy;
use App\Policies\ContentBlockPolicy;
use App\Policies\FaqEntryPolicy;
use App\Policies\OperationalAlertPolicy;
use App\Policies\PageSeoPolicy;
use App\Policies\RequiredDocumentPolicy;
use App\Policies\RoadSafetySectionPolicy;
use App\Policies\ScheduleExceptionPolicy;
use App\Policies\ServicePolicy;
use App\Policies\TariffVersionPolicy;
use App\Policies\TeamMemberPolicy;
use App\Policies\UserPolicy;
use App\Policies\VehicleCategoryPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * @var array<class-string, class-string>
     */
    protected $policies = [
        AppointmentRequest::class => AppointmentRequestPolicy::class,
        ContactMessage::class => ContactMessagePolicy::class,
        Centre::class => CentrePolicy::class,
        ScheduleException::class => ScheduleExceptionPolicy::class,
        OperationalAlert::class => OperationalAlertPolicy::class,
        TariffVersion::class => TariffVersionPolicy::class,
        Service::class => ServicePolicy::class,
        VehicleCategory::class => VehicleCategoryPolicy::class,
        RequiredDocument::class => RequiredDocumentPolicy::class,
        ContentBlock::class => ContentBlockPolicy::class,
        FaqEntry::class => FaqEntryPolicy::class,
        TeamMember::class => TeamMemberPolicy::class,
        RoadSafetySection::class => RoadSafetySectionPolicy::class,
        PageSeo::class => PageSeoPolicy::class,
        User::class => UserPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
