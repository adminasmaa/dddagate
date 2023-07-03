<?php

namespace App\Http\Controllers\Api_Dashboard\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\Response;
use Illuminate\Support\Facades\Validator;

class AuthContoller extends Controller
{
    use Response;

    /**
     * login
     *
     * @param  mixed $request
     * @return void
     */
    public function login(Request $request)
    {
        // Make a validation of login
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required'
        ]);
        // check if find the errors
        if ($validator->fails()) {
            return $this->responseError('Validation Error.', $validator->errors());
        }

        // check the tocken:
        if (! $token = auth('admin')->attempt($request->only('email', 'password'))) {
            return $this->responseError('the email or password is wrong, try again', [], 401);
        }

        return response()->json([
                'status' => 'success',
                'token' => $token,
                'type' => 'bearer',
        ])->withCookie(cookie('jwt_token', $token, config('jwt.ttl')));


    }


    /**
     * logout
     *
     * @return void
     */
    public function logout()
    {
        Auth::guard('admin')->logout();
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out',
        ]);
    }

    /**
     * refresh
     *
     * @return void
     */
    public function refresh()
    {
        return response()->json([
            'status' => 'success',
            'user' => Auth::user(),
            'authorisation' => [
                'token' => Auth::guard('admin')->refresh(),
                'type' => 'bearer',
            ]
        ]);
    }


}
