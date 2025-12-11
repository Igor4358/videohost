<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Video;
use App\Models\User;
use App\Services\UserService;
use App\Services\VideoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class AdminController extends Controller
{
    protected $videoService;
    protected $userService;

    public function __construct(VideoService $videoService, UserService $userService)
    {
        $this->videoService = $videoService;
        $this->userService = $userService;
    }

    public function dashboard()
    {
        $stats = $this->userService->getDashboardStats();
        $recent_videos = $this->videoService->getAllVideosForAdmin()->take(5);
        $recent_users = $this->userService->getAllUsersWithStats()->take(5);

        return view('admin.dashboard', compact('stats', 'recent_videos', 'recent_users'));
    }

    public function videos()
    {
        $videos = $this->videoService->getAllVideosForAdmin();
        return view('admin.videos.index', compact('videos'));
    }

    public function editVideo($id)
    {
        $video = $this->videoService->getVideoById($id);
        return view('admin.videos.edit', compact('video'));
    }

    public function updateVideo(Request $request, Video $video)
    {
        \Log::info('AdminController updateVideo called', [
            'video_id' => $video->id,
            'request_data' => $request->all()
        ]);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_approved' => 'boolean'
        ]);

        try {
            $this->videoService->updateVideo($video->id, $request->all());
            return redirect()->route('admin.videos')->with('success', 'Видео обновлено');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            \Log::error('Video not found: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Видео не найдено']);
        } catch (\Exception $e) {
            \Log::error('Error updating video: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Ошибка при обновлении видео: ' . $e->getMessage()]);
        }
    }
    public function createVideo()
    {
        return view('admin.videos.create');
    }
    public function storeVideo(Request $request)
    {
        $request->validate([
            'url' => 'required|url',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        $videoId = \App\Models\Video::extractVideoId($request->url);

        if (!$videoId) {
            return back()->withErrors(['url' => 'Некорректная ссылка на RuTube видео']);
        }

        \App\Models\Video::create([
            'title' => $request->title,
            'description' => $request->description,
            'url' => $request->url,
            'video_id' => $videoId,
            'platform' => 'rutube',
            'user_id' => Auth::id(),
            'is_approved' => true // Автоодобрение для админов
        ]);

        return redirect()->route('admin.videos')->with('success', 'Видео добавлено');
    }
    public function destroyVideo($id)
    {
        $this->videoService->deleteVideo($id);
        return redirect()->route('admin.videos')->with('success', 'Видео удалено');
    }

    public function approveVideo($id)
    {
        $this->videoService->approveVideo($id);
        return back()->with('success', 'Видео одобрено');
    }

    public function users()
    {
        $users = $this->userService->getAllUsersWithStats();
        return view('admin.users.index', compact('users'));
    }

    public function updateUserRole(Request $request, User $user) // Model Binding
    {
        \Log::info('Admin: updateUserRole called', [
            'user_id' => $user->id,
            'current_role' => $user->role,
            'request_data' => $request->all(),
            'auth_user' => auth()->id(),
        ]);

        $request->validate([
            'role' => 'required|in:user,admin'
        ]);

        \Log::debug('Validation passed', ['role' => $request->role]);

        try {
            $oldRole = $user->role;

            $user->role = $request->role;
            $user->save();

            \Log::info('Role updated successfully', [
                'user_id' => $user->id,
                'old_role' => $oldRole,
                'new_role' => $user->role,
                'updated_by' => auth()->id()
            ]);

            return back()->with('success', 'Роль обновлена');

        } catch (\Exception $e) {
            \Log::error('Error updating user role', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->with('error', 'Ошибка при обновлении роли: ' . $e->getMessage());
        }
    }
}
