<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateStripeCustomerRequest;
use App\Http\Requests\CreateStripeSubscriptionRequest;
use Stripe\Stripe;

class StripeController extends Controller
{
    public function __construct(){
    }
    public function createCustomer(CreateStripeCustomerRequest $request)
    {
        $inputs = $request->validated();
        $data = auth()->user()->createOrGetStripeCustomer($inputs);

        return response()->json($data);
    }

    public function createPaymentIntent()
    {
        $payment =  auth()->user()->createSetupIntent();

        return response()->json(['clientSecret'=>$payment->client_secret]);
    }
    public function createSubscription(CreateStripeSubscriptionRequest $request)
    {
        $inputs = $request->validated();
        $user = auth()->user();

        if (!$user->hasDefaultPaymentMethod()){
            $user->updateDefaultPaymentMethod($inputs['paymentMethodId']);
        }

        $response = $user->newSubscription(
            'default', env('PLAN_'.$inputs['name'])
        )->create($inputs['paymentMethodId']);
        return response()->json($response,500);
    }

}
