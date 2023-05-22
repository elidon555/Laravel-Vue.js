<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\SubscriptionPlan;
use App\Models\User;

class SubscriptionPlanPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, SubscriptionPlan $subscriptionPlan): bool
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('user','admin');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, SubscriptionPlan $subscriptionPlan): bool
    {
        return $user->hasRole('user','finance');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, SubscriptionPlan $subscriptionPlan): bool
    {
        return $user->hasRole('admin','finance');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, SubscriptionPlan $subscriptionPlan): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, SubscriptionPlan $subscriptionPlan): bool
    {
        //
    }
}
