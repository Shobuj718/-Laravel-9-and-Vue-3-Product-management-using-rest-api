<?php

namespace App\Repository;

interface UserRepositoryInterface
{
    public function register($data);
    public function login($data);
    public function logout();
}
