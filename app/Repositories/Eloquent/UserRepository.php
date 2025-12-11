<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function findByEmail($email)
    {
        return $this->model->where('email', $email)->first();
    }

    public function getUsersWithVideoCount()
    {
        return $this->model->withCount('videos')->latest()->paginate(20);
    }

    public function updateUserRole($userId, $role)
    {
        return $this->update($userId, ['role' => $role]);
    }
}
