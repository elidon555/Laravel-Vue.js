<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateStripeCustomerRequest;
use App\Http\Requests\CreateStripeSubscriptionRequest;
use App\Http\Requests\PayStripeSubscriptionRequest;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\StripeClient;
use Stripe\Checkout\Session;

class StripeController extends Controller
{
    public function createCustomer(CreateStripeCustomerRequest $request)
    {
        $inputs = $request->validated();
        $stripe = new StripeClient(env('STRIPE_SECRET'));
        $data =  $stripe->customers->create($inputs);

        return response()->json($data);
    }

    public function createSubscription(CreateStripeSubscriptionRequest $request)
    {

        $inputs = $request->validated();

        $stripe = new StripeClient(env('STRIPE_SECRET'));

        $data = $this->transformCreateSubscription($inputs);

        $response =  $stripe->subscriptions->create($data);
        return response()->json($response);
    }

    private function transformCreateSubscription($inputs) {
        return  [
            'customer' => $inputs['customerId'],
            'items' => [
                ['price' => env("PLAN_".$inputs['planName'])]
            ],
            'payment_behavior' => 'default_incomplete',
            'payment_settings' => [
                'save_default_payment_method' => 'on_subscription',
            ],
            'expand' => ['latest_invoice.payment_intent'],
        ];


    }

    public function paySubscription(Request $request) {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $data = $this->transformPaymentRequest($request->planId);
        $session = Session::create($data);

        return response()->json(["url"=>$session->url]);
    }
    private function transformPaymentRequest($planId) {
        return [
            'payment_method_types' => ['card'],
            'subscription_data' => [
                'items' => [
                    ['plan' => $planId]
                ],
            ],
            'success_url' => 'http://localhost:3000/app/profile',
        ];
    }
}
