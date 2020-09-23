<?php

namespace App\Http\Controllers;

use Validator;
use App\Models\User;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StripeController extends Controller
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * get payment stripe page
     * 
     * @param
     * @return \Illuminate\Http\Response
     */
    public function paymentStripe()
    {
        return view('paymentstripe');
    }

    /**
     * payment stripe
     * 
     * @param $request
     * @return 
     */
    public function postPaymentStripe(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'card_no' => 'required',
            'ccExpiryMonth' => 'required',
            'ccExpiryYear' => 'required',
            'cvvNumber' => 'required',
            'amount' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('/addmoney/stripe')->withErrors($validator);
        }


        $stripe = \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        try {
            $token = \Stripe\Token::create([
                'card' => [
                    'number' => $request->card_no,
                    'exp_month' => $request->ccExpiryMonth,
                    'exp_year' => $request->ccExpiryYear,
                    'cvc' => $request->cvvNumber,
                ],
            ]);
            if (!isset($token['id'])) {
                return redirect('/addmoney/stripe');
            }

            $charge = \Stripe\Charge::create([
                'amount' => $request->amount,
                'currency' => 'inr',
                'source' => $token['id'],
                'description' => 'wallet',
            ]);

            if ($charge['status'] == 'succeeded') {
                $saved = $this->user->storeBalance(Auth()->user()->id, $request->amount);

                $request->session()->flash('alert-success', 'Money added succesfully');
                return redirect('/home');
            } else {
                $request->session()->flash('alert-error', 'Money not add in wallet!!');
                return redirect('/home');
            }
        } catch (Exception $e) {
            \Session::put('error', $e->getMessage());
            return redirect('/addmoney/stripe');
        } catch (\Cartalyst\Stripe\Exception\CardErrorException $e) {

            \Session::put('error', $e->getMessage());
            return redirect('/addmoney/stripe');
        } catch (\Cartalyst\Stripe\Exception\MissingParameterException $e) {

            \Session::put('error', $e->getMessage());
            return redirect('/addmoney/stripe');
        }
    }
}
