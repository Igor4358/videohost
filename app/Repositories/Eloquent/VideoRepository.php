<?php

namespace App\Repositories\Eloquent;

use App\Models\Video;
use App\Repositories\Interfaces\VideoRepositoryInterface;

class VideoRepository extends BaseRepository implements VideoRepositoryInterface
{
    public function __construct(Video $model)
    {
        parent::__construct($model);
    }

    public function getApprovedVideos()
    {
        return $this->model->where('is_approved', true)->latest()->paginate(12);
    }

    public function getPendingVideos()
    {
        return $this->model->where('is_approved', false)->latest()->get();
    }

    public function getVideosByUser($userId)
    {
        return $this->model->where('user_id', $userId)->latest()->get();
    }
    public function updateVideo($id, array $data)
    {
        \Log::info('=== VideoService updateVideo START ===');
        \Log::info('ID:', ['id' => $id, 'type' => gettype($id)]);
        \Log::info('Data:', $data);

        try {
            $video = $this->videoRepository->find($id);
            \Log::info('Found video:', ['exists' => !is_null($video), 'type' => get_class($video)]);

            if (!$video) {
                throw new \Exception("Video with ID {$id} not found");
            }

            $result = $this->videoRepository->update($id, $data);
            \Log::info('Update result:', ['success' => !is_null($result)]);

            return $result;
        } catch (\Exception $e) {
            \Log::error('VideoService error: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            throw $e;
        }
    }
    public function approveVideo($id)
    {
        return $this->update($id, ['is_approved' => true]);
    }

    public function create(array $data)
    {
        $data['platform'] = 'rutube'; // У нас только RuTube
        return parent::create($data);
    }
}
