<?php

namespace App\Http\Controllers\Api_mobile\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api_Mobile\ShopResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\Response;

use Intervention\Image\Facades\Image;
use JWTAuth;
use Illuminate\Support\Facades\Validator;

class AuthContoller extends Controller
{
    use Response;

    /**
     * login
     *
     * @param mixed $request
     * @return void
     */
    public function login(Request $request)
    {
        // Make a validation of login
        $validator = Validator::make($request->all(), [
            'code' => 'required',
            'password' => 'required'
        ]);
        // check if find the errors
        if ($validator->fails()) {
            return $this->responseError('Validation Error.', $validator->errors());
        }

        // check the tocken:
        if (!$token = auth('api')->attempt($request->only('code', 'password'))) {
            return $this->responseError('the code or password is wrong, try again', [], 401);
        }

        $user=auth('api')->user();
        return response()->json([
            'status' => 'success',
            'message'=>trans('message.User login successfully.'),
            'token' => $token,
            'type' => 'bearer',
            'data'=>$user,
        ])->withCookie(cookie('jwt_token', $token, config('jwt.ttl')));


    }


    public function register(Request $request)
    {
        // Make a validation of login
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'phone' => 'required|min:9|unique:users',
            'password' => 'required|min:6',
            'c_password' => 'nullable|same:password',
        ]);
        // check if find the errors
        if ($validator->fails()) {
            return $this->responseError('Validation Error.', $validator->errors());
        }
        $input=$request->except('password','c_password');
        $input['password']=bcrypt($request->password);

        $user = User::create($input);
//        $token = auth('api')->attempt($request->only('email', 'password'));
//        return $this->responseSuccess(trans('message.User register successfully.'), $user, 200);
//       $user['token'] = JWTAuth::parseToken()->authenticate();
        return response()->json([
            'success' => true,
            'message' => trans( 'message.User register successfully.'),
            'data' => $user
        ]);

    }

    public function updateProfile(Request $request)
    {
//        return $request;
        // Make a validation of login
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'address' => 'required',

        ]);
        // check if find the errors
        if ($validator->fails()) {
            return $this->responseError('Validation Error.', $validator->errors());
        }
        $input=$request->all();
        $input['password']=bcrypt($request->password);
        $user=Auth::user();
        $user->update($input);


        $time = time();

        $image_profile_Path = $request->file('image_profile');

        // uploaded image profile:
        if(!empty($image_profile_Path)) {


            $image_profile_Name = "image-profile-{$user->id}-{$time}.{$image_profile_Path->getClientOriginalExtension()}";
            $image_profile_Path->storeAs('public/users/profiles', $image_profile_Name);
            $user->image_profile = $image_profile_Name;

            $user->save();
        }
        return response()->json([
            'success' => true,
            'message' => trans( 'message.User updated successfully'),
            'data' => $user
        ]);

    }

    /**
     * logout
     *
     * @return void
     */
    public function logout()
    {
        Auth::guard('api')->logout();
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
