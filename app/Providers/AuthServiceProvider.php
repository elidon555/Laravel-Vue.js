<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\Content;
use App\Models\Payment;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\UserDetail;
use App\Policies\ContentPolicy;
use App\Policies\PaymentPolicy;
use App\Policies\SubscriptionPlanPolicy;
use App\Policies\SubscriptionPolicy;
use App\Policies\UserDetailPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Subscription::class => SubscriptionPolicy::class,
        Payment::class => PaymentPolicy::class,
        SubscriptionPlan::class => SubscriptionPlanPolicy::class,
        UserDetail::class => UserDetailPolicy::class,
        Content::class => ContentPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
    }
}
