<?php

namespace App\Http\Controllers;

use App\Services\VideoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VideoController extends Controller
{
    protected $videoService;

    public function __construct(VideoService $videoService)
    {
        $this->videoService = $videoService;

    }

    public function index()
    {
        $videos = $this->videoService->getAllApprovedVideos();
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

        try {
            $video = $this->videoService->createVideo($request->all(), Auth::id());
            return redirect()->route('videos.show', $video);
        } catch (\InvalidArgumentException $e) {
            return back()->withErrors(['url' => $e->getMessage()]);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Ошибка при добавлении видео']);
        }
    }

    public function show($id)
    {
        $video = $this->videoService->getVideoById($id);

        if (!$video) {
            abort(404);
        }

        return view('videos.show', compact('video'));
    }
}
