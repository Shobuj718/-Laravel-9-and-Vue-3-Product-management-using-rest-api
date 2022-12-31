<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Repository\UserRepositoryInterface;

class AuthController extends BaseController
{
    private $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function createUser(Request $request)
    {
        try {
            $user =  $this->userRepository->register($request->all());
            return $this->sendResponse($user, 'User Registered success.');
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
        
    }

    public function loginUser(Request $request)
    {
        try {
            $user =  $this->userRepository->login($request->all());
            return $this->sendResponse($user, 'User login success.');
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), 500);
        }
        
    }

    public function logout(Request $request)
    {
        try {
            $user =  $this->userRepository->logout();
            return $this->sendResponse($user, 'User logout success.');
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), 500);
        }

    }
    
}
