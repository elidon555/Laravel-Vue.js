<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateStripeCustomerRequest;
use App\Http\Requests\CreateStripeSubscriptionRequest;
use App\Models\Payment;
use App\Models\SubscriptionPlan;
use App\Models\UserDetail;
use Stripe\Stripe;

class StripeController extends Controller
{
    public function __construct(){
    }
    public function createCustomer(CreateStripeCustomerRequest $request)
    {
        $inputs = $request->validated();

        $response = auth()->user()->createOrGetStripeCustomer($inputs);

        $data = [
            'name'=>$inputs['name'],
            'email'=>$inputs['email'],
            'address'=>$inputs['line1'],
            'city'=>$inputs['city'],
            'country'=>$inputs['country'],
            'state'=>$inputs['state'],
            'postal_code'=>$inputs['postal_code'],
        ];

        UserDetail::create($data);

        return response()->json($response);
    }

    public function updateCustomer(CreateStripeCustomerRequest $request,UserDetail $userDetail)
    {
        $inputs = $request->validated();
        $user = auth()->user();

        $response = $user->updateStripeCustomer($inputs);

        $data = [
            'name'=>$inputs['name'],
            'email'=>$inputs['email'],
            'address'=>$inputs['shipping']['address']['line1'],
            'city'=>$inputs['shipping']['address']['city'],
            'country'=>$inputs['shipping']['address']['country'],
            'state'=>$inputs['shipping']['address']['state'],
            'postal_code'=>$inputs['shipping']['address']['postal_code'],
        ];

        UserDetail::where('user_id',$user->id)->update($data);

        return response()->json($response);
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

        $subscriptionPlan = SubscriptionPlan::query()->where('price_id',$inputs['priceId'])->first();
        $response = $user->newSubscription(
            $subscriptionPlan->user_id,$inputs['priceId']
        )->create($inputs['paymentMethodId']);

        $paymentData = [
            'user_id' => $user->id,
            'price_id' => $inputs['paymentMethodId'],
            'amount' => $subscriptionPlan->price
        ];
        Payment::create($paymentData);
        return response()->json($response);
    }

}
