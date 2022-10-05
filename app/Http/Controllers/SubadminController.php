<?php

namespace App\Http\Controllers;

use App\Models\Subadmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class SubadminController extends Controller
{
    /**
     * @throws ValidationException
     */
    public function register(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(),[
            'name'=>'required|string|between:1,100',
            'email'=>'required|string|email|max:100|unique:subadmins',
//            'password'=>'required|string|confirmed|min:6',
            'password'=>['required','confirmed', Password::min(8)
                                                        ->mixedCase()
                                                        ->numbers()
                                                        ->symbols()
                                                        ->uncompromised(3),
            ],
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $sadmin = Subadmin::create(array_merge(
            $validator->validated(),
            ['password' => bcrypt($request->password)]
        ));

        return response()->json([
            __('word.message') => __('word.success_sadmin'),
            'Subadmin' => $sadmin
        ],201);

    }

    /**
     * @throws ValidationException
     */
    public function login(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator =  Validator::make($request->all(),[
            'email'=>'required|max:100|exists:subadmins,email',
            'password'=>['required','string','min:6']
        ]);
        if($validator->fails()){
            return response()->json($validator->errors(), 422);
        }
        if(! $token = auth('subadmin-api')->attempt($validator->validated())){
            return response()->json([__('word.error') => __('word.unauthorized')], 401);
        }
        return $this->createNewToken($token);
    }
    public function userProfile(): \Illuminate\Http\JsonResponse
    {
        return response()->json(auth('subadmin-api')->user());
    }

    public function logout(): \Illuminate\Http\JsonResponse
    {
        auth('subadmin-api')->logout();
        return response()->json([__('word.message') => __('word.success_s_logged_out')]);
    }


    public function refresh(): \Illuminate\Http\JsonResponse
    {
        return $this->createNewToken(auth('subadmin-api')->refresh());
    }

    public function createNewToken($token): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => strtotime(date('Y-m-d H:i:s', strtotime("+60 min"))),
            'Admin' => auth('subadmin-api')->user()
        ]);
    }
}
