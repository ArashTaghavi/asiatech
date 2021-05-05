<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{

    /**
     * @param Request $request
     * @return JsonResource
     * @throws \Illuminate\Validation\ValidationException
     */
    public function register(Request $request): JsonResource
    {
        $this->validate($request, [
            'mobile' => 'required|min:11|max:11|unique:users',
            'password' => 'required'
        ]);

        $user = new User();
        $user->mobile = $request->input('mobile');
        $user->password = Hash::make($request->input('password'));

        $user->save();

        auth()->attempt(['mobile' => $request->input('mobile')]);

        $token = auth()->user()->createToken('NewToken')->accessToken;

        return new JsonResource([
            'token' => $token,
            'user' => [
                'mobile' => $user->mobile,
                'full_name' => null,
            ]
        ]);

    }

    /**
     * @param Request $request
     * @return JsonResource
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request): JsonResource
    {
        $this->validate($request, [
            'mobile' => 'required|min:11|max:11',
            'password' => 'required'
        ]);

        $user = User::whereMobile($request->input('mobile'))->first();

        if (auth()->attempt(['mobile' => $request->input('mobile'), 'password' => $request->input('password')])) {

            $token = auth()->user()->createToken('NewToken')->accessToken;

            return new JsonResource([
                'token' => $token,
                'user' => [
                    'mobile' => $user->mobile,
                    'full_name' => $user->full_name,
                ]
            ]);
        }

        return new JsonResource([
            'token' => null,
            'user' => []
        ]);
    }


    public function isAuthenticated()
    {
    }

    public function logout(): void
    {
        auth()->user()->token()->revoke();
    }


}
