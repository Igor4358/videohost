<?php

namespace Tests\Unit\Services;

use App\Services\RutubeValidationService;
use Tests\TestCase;

class RutubeValidationServiceTest extends TestCase
{
    protected $validator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->validator = new RutubeValidationService();
    }

    public function test_valid_rutube_urls()
    {
        $testCases = [
            'https://rutube.ru/video/abc123/' => 'abc123',
            'https://rutube.ru/play/embed/def456' => 'def456',
            'https://rutube.ru/video/embed/ghi789' => 'ghi789',
            'https://rutube.ru/embed/jkl012' => 'jkl012',
            'https://rutube.ru/?v=mno345' => 'mno345'
        ];

        foreach ($testCases as $url => $expectedId) {
            $this->assertTrue($this->validator->validateUrl($url));
            $this->assertEquals($expectedId, $this->validator->extractVideoId($url));
        }
    }

    public function test_invalid_urls()
    {
        $invalidUrls = [
            'https://youtube.com/watch?v=123',
            'https://vk.com/video123',
            'not-a-url',
            'https://rutube.ru/channel/123',
            'https://rutube.ru/'
        ];

        foreach ($invalidUrls as $url) {
            $this->assertFalse($this->validator->validateUrl($url));
            $this->assertNull($this->validator->extractVideoId($url));
        }
    }
}
