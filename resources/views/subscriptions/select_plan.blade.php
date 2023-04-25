<!-- select_plan.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Select a Subscription Plan</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('subscriptions.process_subscription') }}">
                            @csrf

                            <div class="form-group">
                                <label for="plan">Select Plan:</label>
                                <select id="plan" name="plan" class="form-control">
                                    @foreach($plans as $plan)
                                        <option value="{{ $plan->id }}">{{ $plan->name }} - ${{ $plan->price }}/month</option>
                                    @endforeach
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary">Subscribe</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
