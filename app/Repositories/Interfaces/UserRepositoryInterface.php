<?php

namespace App\Repositories\Interfaces;

interface UserRepositoryInterface extends RepositoryInterface
{
    public function findByEmail($email);
    public function getUsersWithVideoCount();
    public function updateUserRole($userId, $role);
}
