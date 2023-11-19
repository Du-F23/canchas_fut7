<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthUsersController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string']
        ]);

        $credentials = request(['email', 'password']);

        if(!Auth::attempt($credentials))
        {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        $user = $request->user();
        $user = User::find($user->id);
        $tokenResult = $user->createToken($user->name)->accessToken;

        return response()->json([
            'access_token' => $tokenResult,
            'user' => $user,
        ]);
    }

    public function signup(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'min:3'],
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
            'number_player' => ['required', 'integer'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'number_player' => $request->number_player,
            'rol_id' => 4,
        ]);

        if ($request->file('image_profile')) {
            $image_profile = 'image_profile/' . $request->name . '_' . date('Y-m-d') . '_' . $request->file('image_profile')->getClientOriginalName();
            $image_profile = $request->file('image_profile')->storeAs('public', $image_profile);
            $user->image_profile = $image_profile;
        }

        $user->save();

        $user = User::find($user->id);

        $tokenResult = $user->createToken($user->name)->accessToken;
        return response()->json([
            'message' => 'Successfully created user!', 'user' => $user, 'token' => $tokenResult
        ], 201);
    }



    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

}
