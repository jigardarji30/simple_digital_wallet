<?php

namespace App\Http\Controllers\API;

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
            return response()->json([
                "statusCode" => "0",
                "errors" => $validator->errors(),
                "message" => "Failed.",
            ]);
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
                return response()->json([
                    "statusCode" => "0",
                    "errors" => 'token not set',
                    "message" => "Failed.",
                ]);
            }

            $charge = \Stripe\Charge::create([
                'amount' => $request->amount,
                'currency' => 'inr',
                'source' => $token['id'],
                'description' => 'wallet',
            ]);

            if ($charge['status'] == 'succeeded') {

                $saved = $this->user->storeBalance(Auth()->user()->id, $request->amount);

                return response()->json([
                    "statusCode" => "1",
                    "message" => "Money added succesfully",
                    "data" =>  $this->user->getData(Auth()->user()->id)
                ]);
            } else {
                return response()->json([
                    "statusCode" => "0",
                    "errors" => 'Money not add in wallet!!',
                    "message" => "Failed.",
                ]);
            }
        } catch (Exception $e) {
            return response()->json([
                "statusCode" => "0",
                "errors" => $e->getMessage(),
                "message" => "Failed.",
            ]);
        } catch (\Cartalyst\Stripe\Exception\CardErrorException $e) {
            return response()->json([
                "statusCode" => "0",
                "errors" => $e->getMessage(),
                "message" => "Failed.",
            ]);
        } catch (\Cartalyst\Stripe\Exception\MissingParameterException $e) {
            return response()->json([
                "statusCode" => "0",
                "errors" => $e->getMessage(),
                "message" => "Failed.",
            ]);
        }
    }
}
