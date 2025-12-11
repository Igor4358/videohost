<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'url',
        'thumbnail',
        'platform',
        'video_id',
        'user_id',
        'is_approved'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function extractVideoId($url)
    {
        // Для RuTube
        if (str_contains($url, 'rutube.ru')) {
            $patterns = [
                '/rutube\.ru\/video\/([a-zA-Z0-9]+)/',
                '/rutube\.ru\/play\/embed\/([a-zA-Z0-9]+)/',
                '/rutube\.ru\/video\/embed\/([a-zA-Z0-9]+)/',
                '/rutube\.ru\/embed\/([a-zA-Z0-9]+)/'
            ];

            foreach ($patterns as $pattern) {
                preg_match($pattern, $url, $matches);
                if (!empty($matches[1])) {
                    return $matches[1];
                }
            }
            return null;
        }

        return null;
    }

    // Определяем платформу по URL
    public static function detectPlatform($url)
    {
        if (str_contains($url, 'rutube.ru')) {
            return 'rutube';
        }
        return 'other';
    }

}
