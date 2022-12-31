<?php

namespace App\Repository\Eloquent;

use App\Models\User;
use App\Repository\BaseRepository;
use App\Repository\UserRepositoryInterface;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserRepository extends BaseRepository  implements UserRepositoryInterface
{
     public function register($data)
     {
        try {
            //Validated
            $validateUser = Validator::make(
                $data,
                [
                    'name' => 'required',
                    'email' => 'required|email|unique:users,email',
                    'password' => 'required'
                ]
            );

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password'])
            ]);

            return response()->json([
                'status' => true,
                'message' => 'User Created Successfully',
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }

     }
    public function login($data)
    {
        // get credentials
        $credentials = $this->getCredentials($data);

        // check user exists with give credential
        $user = $this->isUserExist($data);

        // attempt to login
        $token = Auth::guard('web')->attempt($credentials);
        if (!$token) {
            $this->increaseLoginTries($user);
            throw new \Exception('Invalid login credentials');
        }

        // create  access token
        $accessToken = $user->createToken("API TOKEN")->plainTextToken;

        $data = [
            'token' => $accessToken,
            'status' => true,
            'message' => 'User Logged In Successfully',
        ];
        

        return $data;

    }

    private function getCredentials($data)
    {
        // check email or username with password
        if (filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return ['email' => $data['email'], 'password' => $data['password']];
        }
    }

    private function increaseLoginTries($user)
    {
        // $user->login_tries = $user->login_tries + 1;
        // $user->update();

    }

    private function isUserExist($data)
    {
        $credentials = $this->getCredentials($data);
        // remove password from credentials
        unset($credentials['password']);

        // check user exists with give email
        $user = User::where($credentials)->first();

        if (!$user) {
            throw new \Exception('Email not found, login failed!');
        }

        return $user;
    }


    public function logout()
    {
        auth()->user()->tokens()->delete();
        return [
            'message' => 'user logged out'
        ];
    }

}
