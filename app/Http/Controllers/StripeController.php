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
        $stripe = new StripeClient(
            'sk_test_51N3az7IMylWAuAdOiqH5AnAZoVVbUYI0VMrj0pn75NriAJwDlXgVvI7csLKpP91unpoc4GGXC1z4CTeFNEG41Tzq00HPKOarLL'
          );
        $data =  $stripe->customers->create($inputs);

        return response()->json($data);
    }

    public function createSubscription(CreateStripeSubscriptionRequest $request)
    {

        $inputs = $request->validated();
        $stripe = new StripeClient(
            env('STRIPE_KEY')
        );
        $data =  $stripe->subscriptions->create($inputs);
        return response()->json($data);
    }

    public function paySubscription(Request $request) {
        Stripe::setApiKey(env('STRIPE_KEY'));

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
