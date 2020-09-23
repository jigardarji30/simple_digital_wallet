@extends('layouts.app')

@section('content')
<div class="container">
    <div class='row'>


        <div class='col-md-4'></div>
        <div class='col-md-4'>
            <div class="flash-message">

                @if ($errors->any())
                @foreach ($errors->all() as $error)
                <div class="alert alert-danger">{{$error}}</div>
                @endforeach
                @endif

            </div>
            <div class='panel panel-default'>
                <div class="panel-heading" style="margin-bottom:10px;">Add Money</div>
                <div class="panel-body">
                    <form class="form-horizontal" method="POST" id="payment-form" role="form" action="/addmoney/stripe">
                        {{ csrf_field() }}
                        <div class='form-row'>
                            <div class='col-md-12 form-group card required'>
                                <label class='control-label'>Card Number</label>
                                <input autocomplete='off' class='form-control card-number' size='20' type='text' name="card_no">
                            </div>
                        </div>
                        <div class='form-row'>
                            <div class='col-md-4 form-group cvc required'>
                                <label class='control-label'>CVV</label>
                                <input autocomplete='off' class='form-control card-cvc' placeholder='ex. 311' size='4' type='text' name="cvvNumber">
                            </div>
                            <div class='col-md-4 form-group expiration required'>
                                <label class='control-label'>Expiration</label>
                                <input class='form-control card-expiry-month' placeholder='MM' size='4' type='text' name="ccExpiryMonth">
                            </div>
                            <div class='col-md-4 form-group expiration required' style="margin-top:8px;">
                                <label class='control-label'> </label>
                                <input class='form-control card-expiry-year' placeholder='YYYY' size='4' type='text' name="ccExpiryYear">
                            </div>
                        </div>
                        <div class='form-row'>
                            <div class='col-md-12'>
                                <input class='form-control' placeholder='Amount' type='integer' name="amount">
                            </div>
                        </div>
                        <div class='form-row'>
                            <div class='col-md-12 form-group'>
                                <button class='form-control btn btn-success' style="margin-top: 10px" type='submit'>Add</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class='col-md-4'></div>
    </div>
</div>
</body>
@endsection