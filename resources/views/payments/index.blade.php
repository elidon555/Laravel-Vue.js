@extends('layouts.admin')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">{{ __('Payments') }}</div>

                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">User</th>
                                    <th scope="col">Subscriber</th>
                                    <th scope="col">Content</th>
                                    <th scope="col">Amount</th>
                                    <th scope="col">Transaction ID</th>
                                    <th scope="col">Paid At</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($payments as $payment)
                                    <tr>
                                        <th scope="row">{{ $payment->id }}</th>
                                        <td>{{ $payment->user->name }}</td>
                                        <td>{{ $payment->subscriber->name }}</td>
                                        <td>{{ $payment->content->title }}</td>
                                        <td>{{ $payment->amount }}</td>
                                        <td>{{ $payment->transaction_id }}</td>
                                        <td>{{ $payment->paid_at->format('Y-m-d') }}</td>
                                        <td>
                                            <a href="{{ route('admin.payments.show', $payment->id) }}" class="btn btn-sm btn-info">{{ __('View') }}</a>
                                            <form action="{{ route('admin.payments.destroy', $payment->id) }}" method="POST" style="display: inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">{{ __('Delete') }}</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
