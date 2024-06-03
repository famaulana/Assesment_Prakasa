<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Lib\GlobalFunction;
use App\Lib\ReturnCode;
use App\Repositories\Accounts\AccountRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($credentials->fails()) {
            return response()->json(GlobalFunction::responseFormat(ReturnCode::VALIDATION_ERROR, $credentials->messages(), null));
        }

        if (!$token = auth()->attempt($request->all())) {
            return response()->json(GlobalFunction::responseFormat(ReturnCode::FAILED, [], "Invalid Credentials."), 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(GlobalFunction::responseFormat(ReturnCode::SUCCESS, AccountRepository::getDetail(Auth::user()->id, null), null));
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(GlobalFunction::responseFormat(ReturnCode::SUCCESS, [], null));
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh(true, true));
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json(GlobalFunction::responseFormat(ReturnCode::SUCCESS, [
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ], null));
    }
}
