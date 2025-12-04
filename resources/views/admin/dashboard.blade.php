<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Админ панель</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
<!-- Навигация -->
<nav class="bg-gray-800 text-white shadow-lg">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center h-16">
            <div class="flex items-center">
                <a href="{{ route('admin.dashboard') }}" class="text-xl font-bold">Админ панель</a>
                <div class="ml-10 flex items-center space-x-4">
                    <a href="{{ route('admin.dashboard') }}" class="text-gray-300 hover:text-white">Дашборд</a>
                    <a href="{{ route('admin.videos') }}" class="text-gray-300 hover:text-white">Видео</a>
                    <a href="{{ route('admin.users') }}" class="text-gray-300 hover:text-white">Пользователи</a>
                    <a href="{{ route('home') }}" class="text-blue-300 hover:text-blue-100">На сайт</a>
                </div>
            </div>
            <div class="flex items-center">
                <span class="text-gray-300 mr-4">{{ Auth::user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-gray-300 hover:text-white">Выйти</button>
                </form>
            </div>
        </div>
    </div>
</nav>

<main class="py-8">
    <div class="container mx-auto px-4">
        <h1 class="text-3xl font-bold mb-6">Админ панель</h1>

        @if(session('success'))
            <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif

        <!-- Статистика -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white p-6 rounded-lg shadow">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600 mr-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Всего видео</p>
                        <p class="text-2xl font-bold">{{ $stats['total_videos'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-100 text-yellow-600 mr-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">На модерации</p>
                        <p class="text-2xl font-bold">{{ $stats['pending_videos'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 text-green-600 mr-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.707-7.707a4 4 0 00-5.656-5.656M14.828 14.828a4 4 0 01-5.656 0"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Пользователи</p>
                        <p class="text-2xl font-bold">{{ $stats['total_users'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-100 text-purple-600 mr-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Новых сегодня</p>
                        <p class="text-2xl font-bold">{{ $stats['new_users_today'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Быстрые действия -->
        <div class="mb-8">
            <div class="flex space-x-4">
                <a href="{{ route('admin.videos.create') }}"
                   class="bg-blue-500 text-white px-6 py-3 rounded-lg hover:bg-blue-600">
                    Добавить видео
                </a>
                <a href="{{ route('admin.videos') }}"
                   class="bg-green-500 text-white px-6 py-3 rounded-lg hover:bg-green-600">
                    Управление видео
                </a>
                <a href="{{ route('admin.users') }}"
                   class="bg-purple-500 text-white px-6 py-3 rounded-lg hover:bg-purple-600">
                    Управление пользователями
                </a>
            </div>
        </div>

        <!-- Последние видео и пользователи -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Последние видео -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-4 border-b">
                    <h2 class="text-xl font-bold flex justify-between items-center">
                        Последние видео
                        <a href="{{ route('admin.videos') }}" class="text-sm text-blue-600 hover:text-blue-800">Все видео →</a>
                    </h2>
                </div>
                <div class="p-4">
                    @foreach($recent_videos as $video)
                        <div class="border-b py-3 last:border-b-0 flex justify-between items-center">
                            <div class="flex-1">
                                <a href="{{ route('videos.show', $video) }}" class="font-medium hover:text-blue-600">
                                    {{ Str::limit($video->title, 50) }}
                                </a>
                                <div class="flex items-center mt-1 text-sm text-gray-500">
                                    <span>{{ $video->user->name }}</span>
                                    <span class="mx-2">•</span>
                                    <span>{{ $video->created_at->format('d.m.Y H:i') }}</span>
                                </div>
                            </div>
                            <div class="flex space-x-2 ml-4">
                                @if(!$video->is_approved)
                                    <form action="{{ route('admin.videos.approve', $video) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="text-xs bg-yellow-500 text-white px-2 py-1 rounded hover:bg-yellow-600">
                                            Одобрить
                                        </button>
                                    </form>
                                @endif
                                <a href="{{ route('admin.videos.edit', $video) }}" class="text-xs bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-600">
                                    Ред.
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Последние пользователи -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-4 border-b">
                    <h2 class="text-xl font-bold flex justify-between items-center">
                        Последние пользователи
                        <a href="{{ route('admin.users') }}" class="text-sm text-blue-600 hover:text-blue-800">Все пользователи →</a>
                    </h2>
                </div>
                <div class="p-4">
                    @foreach($recent_users as $user)
                        <div class="border-b py-3 last:border-b-0 flex justify-between items-center">
                            <div>
                                <p class="font-medium">{{ $user->name }}</p>
                                <p class="text-sm text-gray-500">{{ $user->email }}</p>
                            </div>
                            <div>
                                <span class="text-xs px-2 py-1 rounded-full {{ $user->role === 'admin' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $user->role === 'admin' ? 'Админ' : 'Пользователь' }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</main>
</body>
</html>
