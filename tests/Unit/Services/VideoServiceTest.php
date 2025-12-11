<?php

namespace Tests\Unit\Services;

use App\Repositories\Interfaces\VideoRepositoryInterface;
use App\Services\Contracts\VideoValidationInterface;
use App\Services\VideoService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VideoServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $videoService;
    protected $videoRepositoryMock;
    protected $videoValidatorMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->videoRepositoryMock = $this->createMock(VideoRepositoryInterface::class);
        $this->videoValidatorMock = $this->createMock(VideoValidationInterface::class);

        $this->videoService = new VideoService(
            $this->videoRepositoryMock,
            $this->videoValidatorMock
        );
    }

    public function test_create_video_success()
    {
        $data = [
            'title' => 'Test Video',
            'description' => 'Test Description',
            'url' => 'https://rutube.ru/video/test123'
        ];

        $userId = 1;
        $videoId = 'test123';

        $this->videoValidatorMock->method('extractVideoId')
            ->willReturn($videoId);

        $expectedData = [
            'title' => 'Test Video',
            'description' => 'Test Description',
            'url' => 'https://rutube.ru/video/test123',
            'video_id' => 'test123',
            'user_id' => 1,
            'is_approved' => false,
            'platform' => 'rutube'
        ];

        $this->videoRepositoryMock->expects($this->once())
            ->method('create')
            ->with($expectedData);

        $this->videoService->createVideo($data, $userId);
    }

    public function test_create_video_invalid_url()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Некорректная ссылка на RuTube видео');

        $data = [
            'title' => 'Test Video',
            'url' => 'invalid-url'
        ];

        $this->videoValidatorMock->method('extractVideoId')
            ->willReturn(null);

        $this->videoService->createVideo($data, 1);
    }
}
