@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Edit Payment</div>

                    <div class="panel-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form class="form-horizontal" method="POST" action="{{ route('payments.update', $payment->id) }}">
                            {{ csrf_field() }}
                            {{ method_field('PUT') }}

                            <div class="form-group{{ $errors->has('user_id') ? ' has-error' : '' }}">
                                <label for="user_id" class="col-md-4 control-label">User</label>

                                <div class="col-md-6">
                                    <select id="user_id" name="user_id" class="form-control">
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" {{ $user->id == $payment->user_id ? 'selected' : '' }}>{{ $user->name }}</option>
                                        @endforeach
                                    </select>

                                    @if ($errors->has('user_id'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('user_id') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('subscriber_id') ? ' has-error' : '' }}">
                                <label for="subscriber_id" class="col-md-4 control-label">Subscriber</label>

                                <div class="col-md-6">
                                    <select id="subscriber_id" name="subscriber_id" class="form-control">
                                        @foreach($subscribers as $subscriber)
                                            <option value="{{ $subscriber->id }}" {{ $subscriber->id == $payment->subscriber_id ? 'selected' : '' }}>{{ $subscriber->name }}</option>
                                        @endforeach
                                    </select>

                                    @if ($errors->has('subscriber_id'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('subscriber_id') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('content_id') ? ' has-error' : '' }}">
                                <label for="content_id" class="col-md-4 control-label">Content</label>

                                <div class="col-md-6">
                                    <select id="content_id" name="content_id" class="form-control">
                                        @foreach($contents as $content)
                                            <option value="{{ $content->id }}" {{ $content->id == $payment->content_id ? 'selected' : '' }}>{{ $content->title }}</option>
                                        @endforeach
                                    </select>

                                    @if ($errors->has('content_id'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('content_id') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('amount') ? ' has-error' : '' }}">
                                <label for="amount" class="col-md-4 control-label">Amount</label>

                                <div class="col-md-6">
                                    <input type="text" class="form-control" id="amount" name="amount" value="{{ old('amount', $payment->amount) }}" required autofocus>
                                    
                                    @if ($errors->has('amount'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('amount') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                        <div class="form-group{{ $errors->has('payment_date') ? ' has-error' : '' }}">
                            <label for="payment_date" class="col-md-4 control-label">Payment Date</label>

                            <div class="col-md-6">
                                <input
                                    type="text"
                                    class="form-control datepicker"
                                    id="payment_date"
                                    name="payment_date"
                                    value="{{ old('payment_date', $payment->payment_date ? $payment->payment_date->format('m/d/Y') : '') }}"
                                    required>

                                @if ($errors->has('payment_date'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('payment_date') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Update Payment
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection