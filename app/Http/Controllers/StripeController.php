<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateStripeCustomerRequest;
use App\Http\Requests\CreateStripeSubscriptionRequest;
use Illuminate\Http\Request;
use Stripe\StripeClient;

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
//        $inputs['items']['price']= 'price_1N3cIwIMylWAuAdOtpBTlGre' ;
        $stripe = new StripeClient(
            'sk_test_51N3az7IMylWAuAdOiqH5AnAZoVVbUYI0VMrj0pn75NriAJwDlXgVvI7csLKpP91unpoc4GGXC1z4CTeFNEG41Tzq00HPKOarLL'
        );
        $data =  $stripe->subscriptions->create($inputs);
        return response()->json($data);
    }

    public function pay() {
        \Stripe\Stripe::setApiKey('sk_test_51N3az7IMylWAuAdOiqH5AnAZoVVbUYI0VMrj0pn75NriAJwDlXgVvI7csLKpP91unpoc4GGXC1z4CTeFNEG41Tzq00HPKOarLL');

        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'subscription_data' => [
                'items' => [
                    ['plan' => 'price_1N3cIwIMylWAuAdOtpBTlGre']
                ],
            ],
            'success_url' => 'https://google.com',
            'cancel_url' => 'https://facebook.com',
        ]);
        echo 'Location: https://checkout.stripe.com/checkout.js?sessionId=' . $session->id;exit;

        header();

    }
}
