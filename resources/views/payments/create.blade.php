@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Create Payment</h2>
        <form method="POST" action="{{ route('payments.store') }}">
            @csrf
            <div class="form-group">
                <label for="user_id">User</label>
                <select class="form-control" id="user_id" name="user_id">
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="subscriber_id">Subscriber</label>
                <select class="form-control" id="subscriber_id" name="subscriber_id">
                    @foreach($subscribers as $subscriber)
                        <option value="{{ $subscriber->id }}">{{ $subscriber->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="content_id">Content</label>
                <select class="form-control" id="content_id" name="content_id">
                    @foreach($contents as $content)
                        <option value="{{ $content->id }}">{{ $content->title }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="amount">Amount</label>
                <input type="text" class="form-control" id="amount" name="amount">
            </div>
            <div class="form-group">
                <label for="transaction_id">Transaction ID</label>
                <input type="text" class="form-control" id="transaction_id" name="transaction_id">
            </div>
            <div class="form-group">
                <label for="paid_at">Paid At</label>
                <input type="date" class="form-control" id="paid_at" name="paid_at">
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
@endsection
