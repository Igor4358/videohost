<?php

namespace App\Services;

use App\Services\Contracts\VideoValidationInterface;

class RutubeValidationService implements VideoValidationInterface
{
    public function extractVideoId(string $url): ?string
    {
        if (!str_contains($url, 'rutube.ru')) {
            return null;
        }

        $patterns = [
            '/rutube\.ru\/video\/([a-zA-Z0-9]+)/',
            '/rutube\.ru\/play\/embed\/([a-zA-Z0-9]+)/',
            '/rutube\.ru\/video\/embed\/([a-zA-Z0-9]+)/',
            '/rutube\.ru\/embed\/([a-zA-Z0-9]+)/',
            '/rutube\.ru\/\?v=([a-zA-Z0-9]+)/',
            '/([a-zA-Z0-9]{6,})/' // Общий паттерн
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $url, $matches)) {
                // Пропускаем 'embed', 'video' и другие ключевые слова
                if (!in_array($matches[1], ['embed', 'video', 'play', 'rutube'])) {
                    return $matches[1];
                }
            }
        }

        return null;
    }

    public function validateUrl(string $url): bool
    {
        return $this->extractVideoId($url) !== null;
    }
}
