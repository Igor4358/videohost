<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;
use Alaouy\Youtube\Facades\Youtube;
use Illuminate\Support\Facades\Auth;

class VideoController extends Controller
{
    public function index()
    {
        $videos = Video::where('is_approved', true)
            ->latest()
            ->paginate(12);

        return view('videos.index', compact('videos'));
    }

    public function create()
    {
        return view('videos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'url' => 'required|url',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        $videoId = Video::extractVideoId($request->url);
        $platform = Video::detectPlatform($request->url);

        if (!$videoId) {
            return back()->withErrors(['url' => 'Некорректная ссылка на видео. Поддерживаются: RuTube, VK Video, Яндекс.Эфир']);
        }

        $video = Video::create([
            'title' => $request->title,
            'description' => $request->description,
            'url' => $request->url,
            'video_id' => $videoId,
            'platform' => $platform,
            'user_id' => Auth::id(),
            'is_approved' => Auth::user()->role === 'admin'
        ]);

        return redirect()->route('videos.show', $video);
    }

    public function show(Video $video)
    {
        return view('videos.show', compact('video'));
    }
}
