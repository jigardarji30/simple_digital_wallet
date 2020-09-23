@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('E-Wallet') }}</div>

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif
                    <div class="card" style="width: 18rem;">
                        <div class="flash-message">
                            @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                            @if(Session::has('alert-' . $msg))

                            <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
                            @endif
                            @endforeach
                        </div>
                        <div class="card-body">
                            <h5 class="card-title"><b>Balance</b></h5>
                            <p class="card-text">Customer Name: {{ Auth::user()->name }}</p>
                            <p class="card-text">Balance: {{ (Auth::user()->balance != null ? Auth::user()->balance : 0) }}</p>
                            <a href="/addmoney/stripe" class="btn btn-primary">Add Money</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection