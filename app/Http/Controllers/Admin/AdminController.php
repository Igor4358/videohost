<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Video;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
   // public function __construct()
   // {
   //     $this->middleware(['auth', 'admin']);
   // }

    // Главная админки
    public function dashboard()
    {
        $stats = [
            'total_videos' => Video::count(),
            'pending_videos' => Video::where('is_approved', false)->count(),
            'total_users' => User::count(),
            'new_users_today' => User::whereDate('created_at', today())->count()
        ];

        $recent_videos = Video::latest()->take(5)->get();
        $recent_users = User::latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recent_videos', 'recent_users'));
    }

    // Список всех видео
    public function videos()
    {
        $videos = Video::with('user')->latest()->paginate(20);
        return view('admin.videos.index', compact('videos'));
    }

    // Форма создания видео
    public function createVideo()
    {
        return view('admin.videos.create');
    }

    // Сохранение видео
    public function storeVideo(Request $request)
    {
        $request->validate([
            'url' => 'required|url',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        $videoId = Video::extractVideoId($request->url);
        $platform = Video::detectPlatform($request->url);

        if (!$videoId) {
            return back()->withErrors(['url' => 'Некорректная ссылка на видео']);
        }

        Video::create([
            'title' => $request->title,
            'description' => $request->description,
            'url' => $request->url,
            'video_id' => $videoId,
            'platform' => $platform,
            'user_id' => Auth::id(),
            'is_approved' => true // Автоодобрение для админов
        ]);

        return redirect()->route('admin.videos')->with('success', 'Видео добавлено');
    }

    // Форма редактирования видео
    public function editVideo(Video $video)
    {
        return view('admin.videos.edit', compact('video'));
    }

    // Обновление видео
    public function updateVideo(Request $request, Video $video)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_approved' => 'boolean'
        ]);

        $video->update([
            'title' => $request->title,
            'description' => $request->description,
            'is_approved' => $request->has('is_approved')
        ]);

        return redirect()->route('admin.videos')->with('success', 'Видео обновлено');
    }

    // Удаление видео
    public function destroyVideo(Video $video)
    {
        $video->delete();
        return redirect()->route('admin.videos')->with('success', 'Видео удалено');
    }

    // Одобрение видео
    public function approveVideo(Video $video)
    {
        $video->update(['is_approved' => true]);
        return back()->with('success', 'Видео одобрено');
    }

    // Список пользователей
    public function users()
    {
        // Загружаем пользователей с подсчетом видео
        $users = User::withCount('videos')->latest()->paginate(20);
        return view('admin.users.index', compact('users'));
    }

    // Изменение роли пользователя
    public function updateUserRole(Request $request, User $user)
    {
        Log::info('=== ОБНОВЛЕНИЕ РОЛИ ===');
        Log::info('Пользователь:', ['id' => $user->id, 'name' => $user->name]);
        Log::info('Текущая роль:', ['role' => $user->role]);
        Log::info('Новая роль из формы:', ['new_role' => $request->input('role')]);

        $request->validate([
            'role' => 'required|in:user,admin'
        ]);

        // Сохраняем старую роль для сообщения
        $oldRole = $user->role;

        // Обновляем роль
        $user->role = $request->role;
        $saved = $user->save();

        Log::info('Результат сохранения:', ['success' => $saved]);

        if ($saved) {
            Log::info('Роль обновлена успешно!');
            return back()->with('success', "Роль пользователя {$user->name} изменена с '{$oldRole}' на '{$user->role}'");
        } else {
            Log::error('Ошибка сохранения роли!');
            return back()->with('error', 'Ошибка обновления роли');
        }
    }
}
