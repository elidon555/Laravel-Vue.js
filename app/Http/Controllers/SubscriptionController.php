<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $subscriptions = Subscription::all();

        return view('subscriptions.index', compact('subscriptions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('subscriptions.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'price' => 'required|numeric|min:0',
            'description' => 'required',
        ]);

        $subscription = new Subscription();
        $subscription->name = $validated['name'];
        $subscription->price = $validated['price'];
        $subscription->description = $validated['description'];
        $subscription->is_active = $request->has('is_active') ? true : false;
        $subscription->start_date = $request->has('start_date') ? $request->input('start_date') : null;
        $subscription->end_date = $request->has('end_date') ? $request->input('end_date') : null;
        $subscription->creator_id = auth()->id();
        $subscription->save();

        return redirect()->route('subscriptions.index')->with('success', 'Subscription created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $subscription = Subscription::findOrFail($id);

        return view('subscriptions.show', compact('subscription'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $subscription = Subscription::findOrFail($id);

        return view('subscriptions.edit', compact('subscription'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required',
            'price' => 'required|numeric|min:0',
            'description' => 'required',
        ]);

        $subscription = Subscription::findOrFail($id);
        $subscription->name = $validated['name'];
        $subscription->price = $validated['price'];
        $subscription->description = $validated['description'];
        $subscription->is_active = $request->has('is_active') ? true : false;
        $subscription->start_date = $request->has('start_date') ? $request->input('start_date') : null;
        $subscription->end_date = $request->has('end_date') ? $request->input('end_date') : null;
        $subscription->save();

        return redirect()->route('subscriptions.index')->with('success', 'Subscription updated successfully.');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $subscription = Subscription::findOrFail($id);
        $subscription->delete();

        return redirect()->route('subscriptions.index')->with('success', 'Subscription deleted successfully.');
    }


    // SubscriptionController.php

    public function selectPlan()
    {
        $plans = SubscriptionPlan::all();
        return view('subscriptions.select_plan', compact('plans'));
    }

    public function makePayment(Request $request)
    {
        $validated = $request->validate([
            'subscription_id' => 'required',
        ]);

        $subscription = Subscription::findOrFail($validated['subscription_id']);

        // Create a new invoice with the payment amount
        $invoice = new \BTCPayServer\Invoice();
        $invoice->setPrice($subscription->price);
        $invoice->setCurrency("USD");
        $invoice->setOrderId(Str::random(10)); // generate a random order ID

        // Set the redirect URLs for success and failure
        $url = route('payments.callback');
        $invoice->setNotificationUrl($url . '?status=success');
        $invoice->setRedirectUrl($url . '?status=failed');

        // Get the BTCPayServer client instance
        $client = new \BTCPayServer\Client(env('BTCPAYSERVER_HOST'), env('BTCPAYSERVER_PORT'), env('BTCPAYSERVER_PAIRING_CODE'));
        $token = new \BTCPayServer\Token();
        $token->setToken(env('BTCPAYSERVER_TOKEN'));
        $client->setToken($token);

        // Create the invoice on BTCPayServer
        $invoice = $client->createInvoice($invoice);

        // Save the invoice ID to the database
        $payment = new Payment();
        $payment->subscription_id = $subscription->id;
        $payment->invoice_id = $invoice->getId();
        $payment->save();

        // Redirect the user to the payment URL
        return redirect($invoice->getUrl());
    }
}

    