<?php

namespace App\Http\Controllers\API;

use Validator;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * login wallet account api
     *
     * @param $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                "statusCode" => "0",
                "errors" => $validator->errors(),
                "message" => "Failed.",
            ], 401);
        }
        if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
            $user = Auth::user();
            $success['token'] =  $user->createToken('token')->accessToken;
            return response()->json([
                "statusCode" => "1",
                "message" => "login successfully",
                "data" => $success
            ], 200);
        } else {
            return response()->json([
                "statusCode" => "0",
                "errors" => 'Unauthorised',
                "message" => "Failed.",
            ], 401);
        }
    }

    /**
     * create wallet account api
     *
     * @param $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                "statusCode" => "0",
                "errors" => $validator->errors(),
                "message" => "Failed.",
            ], 401);
        }
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $userData = $this->user->createAccount($input);
        $success['token'] =  $userData->createToken('token')->accessToken;
        $success['name'] =  $userData->name;
        return response()->json([
            "statusCode" => "1",
            "message" => "add wallet account successfully",
            "data" => $success
        ], 200);
    }

    /**
     * get wallet account details api
     *
     * @param $request
     * @return \Illuminate\Http\Response
     */
    public function getData($id)
    {
        $userData = $this->user->getData($id);
        return response()->json([
            "statusCode" => "1",
            "message" => "get wallet details successfully",
            "data" => $userData
        ], 200);
    }
}
