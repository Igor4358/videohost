<?php

namespace App\Services;

use App\Repositories\Interfaces\VideoRepositoryInterface;
use App\Services\Contracts\VideoValidationInterface;

class VideoService
{
    protected $videoRepository;
    protected $videoValidator;

    public function __construct(
        VideoRepositoryInterface $videoRepository,
        VideoValidationInterface $videoValidator
    ) {
        $this->videoRepository = $videoRepository;
        $this->videoValidator = $videoValidator;
    }

    public function getAllApprovedVideos()
    {
        return $this->videoRepository->getApprovedVideos();
    }

    public function getVideoById($id)
    {
        return $this->videoRepository->find($id);
    }

    public function createVideo(array $data, $userId)
    {
        // Валидация ссылки
        $videoId = $this->videoValidator->extractVideoId($data['url']);

        if (!$videoId) {
            throw new \InvalidArgumentException('Некорректная ссылка на RuTube видео');
        }

        $videoData = [
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'url' => $data['url'],
            'video_id' => $videoId,
            'user_id' => $userId,
            'platform' => 'rutube',
            'is_approved' => false // По умолчанию на модерации
        ];

        return $this->videoRepository->create($videoData);
    }

    public function updateVideo($id, array $data)
    {
        // Добавляем логирование для отладки
        \Log::info('Updating video', ['id' => $id, 'data' => $data]);

        return $this->videoRepository->update($id, $data);
    }

    public function deleteVideo($id)
    {
        \Log::info('Deleting video', ['id' => $id]);
        return $this->videoRepository->delete($id);
    }

    public function approveVideo($id)
    {
        \Log::info('Approving video', ['id' => $id]);
        return $this->videoRepository->update($id, ['is_approved' => true]);
    }

    public function getAllVideosForAdmin()
    {
        return $this->videoRepository->paginate(20);
    }

    public function getPendingVideos()
    {
        return $this->videoRepository->getPendingVideos();
    }
}
