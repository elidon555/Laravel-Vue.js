@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Payment Details</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <th>Transaction ID</th>
                                    <td>{{ $payment->transaction_id }}</td>
                                </tr>
                                <tr>
                                    <th>User</th>
                                    <td>{{ $payment->user->name }}</td>
                                </tr>
                                <tr>
                                    <th>Subscriber</th>
                                    <td>{{ $payment->subscriber->name }}</td>
                                </tr>
                                <tr>
                                    <th>Content</th>
                                    <td>{{ $payment->content->title }}</td>
                                </tr>
                                <tr>
                                    <th>Amount</th>
                                    <td>{{ $payment->amount }}</td>
                                </tr>
                                <tr>
                                    <th>Date Paid</th>
                                    <td>{{ $payment->paid_at }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
