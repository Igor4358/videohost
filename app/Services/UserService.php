<?php

namespace App\Services;

use App\Repositories\Interfaces\UserRepositoryInterface;

class UserService
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getAllUsersWithStats()
    {
        return $this->userRepository->getUsersWithVideoCount();
    }

    public function getUserById($id)
    {
        return $this->userRepository->find($id);
    }

    public function updateUserRole($userId, $role)
    {
        if (!in_array($role, ['user', 'admin'])) {
            throw new \InvalidArgumentException('Некорректная роль');
        }

        return $this->userRepository->updateUserRole($userId, $role);
    }

    public function createUser(array $data)
    {
        $data['password'] = bcrypt($data['password']);
        $data['role'] = $data['role'] ?? 'user';

        return $this->userRepository->create($data);
    }

    public function getDashboardStats()
    {
        $totalVideos = \App\Models\Video::count();
        $pendingVideos = \App\Models\Video::where('is_approved', false)->count();
        $totalUsers = \App\Models\User::count();
        $newUsersToday = \App\Models\User::whereDate('created_at', today())->count();

        return [
            'total_videos' => $totalVideos,
            'pending_videos' => $pendingVideos,
            'total_users' => $totalUsers,
            'new_users_today' => $newUsersToday
        ];
    }
}
