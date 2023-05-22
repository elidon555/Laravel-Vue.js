<?php


namespace App\Http\Controllers\Api;

use App\Enums\StripeStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateSubscriptionRequest;
use App\Http\Resources\SubscriptionResource;
use App\Models\Payment;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\User;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $perPage = request('per_page', 10);
        $sortField = request('sort_field', 'updated_at');
        $sortDirection = request('sort_direction', 'asc');

        $query = Subscription::with(['user', 'subscribedUser', 'plan'])
            ->orderBy($sortField, $sortDirection)
            ->paginate($perPage);

        return (new SubscriptionResource($query, 'test'))->collection($query);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSubscriptionRequest $request, Subscription $subscription)
    {
        $inputs = $request->validated();
        $user = User::findOrFail($inputs['userId']);
        $subscriptionPlan = SubscriptionPlan::query()
            ->where('user_id', '=', $inputs['contentCreator'])
            ->whereId($inputs['subscriptionPlan'])
            ->firstOrFail();

        if ($subscription->name == $inputs['contentCreator']) {
            $user->subscription($inputs['contentCreator'])->swap($subscriptionPlan->price_id);
        } else {
            if (Subscription::query()
                ->where('name', $inputs['contentCreator'])
                ->where('stripe_status', StripeStatusEnum::Active->value)
                ->where('user_id', $inputs['userId'])->count()) {
                return response()->json(['message' => 'User is already subscribed to this content creator'], 403);
            }
            if ($user->hasDefaultPaymentMethod()) {
                $response = $user->newSubscription(
                    $subscriptionPlan->user_id, $subscriptionPlan->price_id
                )->create($user->defaultPaymentMethod()->id);

                $paymentData = [
                    'user_id' => $user->id,
                    'price_id' => $subscriptionPlan->price_id,
                    'amount' => $subscriptionPlan->price
                ];
                Payment::create($paymentData);
            }


        }
        return response()->json();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(Subscription $subscription)
    {
        $subscription->user->subscription($subscription->name)->cancelNow();

        return response()->noContent();
    }
}
