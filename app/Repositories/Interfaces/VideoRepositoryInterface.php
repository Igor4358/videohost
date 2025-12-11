<?php

namespace App\Repositories\Interfaces;

interface VideoRepositoryInterface extends RepositoryInterface
{
    public function getApprovedVideos();
    public function getPendingVideos();
    public function getVideosByUser($userId);
    public function approveVideo($id);
}
