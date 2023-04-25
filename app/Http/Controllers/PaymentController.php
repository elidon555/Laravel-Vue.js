<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\User;
use App\Models\Subscription;
use App\Models\Content;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with(['user', 'subscriber', 'content'])->get();
        return view('payments.index', compact('payments'));
    }

    public function create()
    {
        $users = User::all();
        $subscribers = Subscription::all();
        $contents = Content::all();
        return view('payments.create', compact('users', 'subscribers', 'contents'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'subscriber_id' => 'required|exists:subscriptions,id',
            'content_id' => 'required|exists:contents,id',
            'amount' => 'required|numeric|min:0',
            'transaction_id' => 'required|unique:payments,transaction_id|max:36',
            'paid_at' => 'required|date',
        ]);

        Payment::create($validated);

        return redirect()->route('payment.index')->with('success', 'Payment created successfully.');
    }

    public function show($id)
    {
        $payment = Payment::findOrFail($id);

        return view('admin.payments.show', compact('payment'));
    }

    public function edit($id)
    {
        $payment = Payment::findOrFail($id);
        $users = User::all();
        $subscribers = Subscription::all();
        $contents = Content::all();
        return view('payments.edit', compact('payment', 'users', 'subscribers', 'contents'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'subscriber_id' => 'required|exists:subscriptions,id',
            'content_id' => 'required|exists:contents,id',
            'amount' => 'required|numeric|min:0',
            'transaction_id' => 'required|max:36|unique:payments,transaction_id,' . $id,
            'paid_at' => 'required|date',
        ]);

        $payment = Payment::findOrFail($id);
        $payment->update($validated);

        return redirect()->route('payment.index')->with('success', 'Payment updated successfully.');
    }

    public function destroy($id)
    {
        $payment = Payment::findOrFail($id);
        $payment->delete();
        return redirect()->route('payment.index')->with('success', 'Payment deleted successfully.');
    }
}




