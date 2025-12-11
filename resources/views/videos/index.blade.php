<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Видеохостинг') - VideoHost</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        .video-container {
            position: relative;
            padding-bottom: 56.25%; /* 16:9 Aspect Ratio */
            height: 0;
            overflow: hidden;
            background: #000;
            border-radius: 8px 8px 0 0;
        }

        .video-container iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: 0;
        }

        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .truncate {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 2rem;
        }

        .pagination a, .pagination span {
            padding: 0.5rem 1rem;
            margin: 0 0.25rem;
            border: 1px solid #e5e7eb;
            border-radius: 0.375rem;
            color: #4b5563;
            text-decoration: none;
        }

        .pagination a:hover {
            background-color: #f3f4f6;
        }

        .pagination .active {
            background-color: #3b82f6;
            color: white;
            border-color: #3b82f6;
        }
    </style>
</head>
<body class="bg-gray-50">
<!-- Навигация -->
<nav class="bg-gray-800 text-white shadow-lg">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center h-16">
            <div class="flex items-center">
                <a href="{{ route('home') }}" class="text-xl font-bold text-white">VideoHost</a>

                @auth
                    <div class="ml-10 flex items-center space-x-4">
                        <a href="{{ route('videos.index') }}" class="text-gray-300 hover:text-white">Видео</a>
                        <a href="{{ route('videos.create') }}" class="text-gray-300 hover:text-white">Добавить видео</a>

                        @if(Auth::user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" class="text-red-300 hover:text-red-100">Админка</a>
                        @endif
                    </div>
                @endauth
            </div>

            <div class="flex items-center">
                @auth
                    <span class="text-gray-300 mr-4">{{ Auth::user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-gray-300 hover:text-white bg-transparent border-none cursor-pointer">
                            Выйти
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-gray-300 hover:text-white">Войти</a>
                    <a href="{{ route('register') }}" class="ml-4 text-gray-300 hover:text-white">Регистрация</a>
                @endauth
            </div>
        </div>
    </div>
</nav>

<main class="py-8">
    <div class="container mx-auto px-4">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Все видео</h1>
            <p class="text-gray-600 mt-2">Смотрите видео с RuTube </p>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                {{ session('error') }}
            </div>
        @endif

        @if($videos->isEmpty())
            <div class="text-center py-16 bg-white rounded-lg shadow">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                </svg>
                <h3 class="text-xl font-medium text-gray-900 mb-2">Пока нет видео</h3>
                <p class="text-gray-500 mb-6">Будьте первым, кто добавит видео!</p>

                @auth
                    <a href="{{ route('videos.create') }}"
                       class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Добавить видео
                    </a>
                @else
                    <div class="space-y-3">
                        <p class="text-gray-500">Чтобы добавить видео, нужно войти в систему</p>
                        <a href="{{ route('login') }}"
                           class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition">
                            Войти
                        </a>
                    </div>
                @endauth
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($videos as $video)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-300">
                        <!-- Превью видео -->
                        <a href="{{ route('videos.show', $video) }}" class="block">
                            <div class="video-container">
                                @if($video->platform === 'rutube')
                                    <iframe
                                        src="https://rutube.ru/play/embed/{{ $video->video_id }}"
                                        frameborder="0"
                                        allow="clipboard-write; autoplay"
                                        webkitAllowFullScreen
                                        mozallowfullscreen
                                        allowFullScreen>
                                    </iframe>
                                @elseif($video->platform === 'vk')
                                    @php
                                        $vk_parts = explode('_', $video->video_id);
                                        $vk_oid = $vk_parts[0] ?? '';
                                        $vk_id = $vk_parts[1] ?? '';
                                    @endphp
                                    @if($vk_oid && $vk_id)
                                        <iframe
                                            src="https://vk.com/video_ext.php?oid={{ $vk_oid }}&id={{ $vk_id }}&hash="
                                            frameborder="0"
                                            allowfullscreen>
                                        </iframe>
                                    @else
                                        <div class="flex items-center justify-center h-full bg-gray-800 text-white">
                                            <div class="text-center">
                                                <svg class="w-12 h-12 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                <p class="text-sm">Неверный ID видео</p>
                                            </div>
                                        </div>
                                    @endif
                                @else
                                    <div class="flex items-center justify-center h-full bg-gray-800">
                                        <div class="text-center text-white p-4">
                                            <svg class="w-12 h-12 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <a href="{{ $video->url }}" target="_blank"
                                               class="inline-block bg-blue-500 px-4 py-2 rounded hover:bg-blue-600">
                                                Смотреть видео
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </a>

                        <div class="p-4">
                            <a href="{{ route('videos.show', $video) }}" class="block">
                                <h3 class="font-bold text-lg mb-2 truncate" title="{{ $video->title }}">
                                    {{ $video->title }}
                                </h3>
                            </a>

                            @if($video->description)
                                <p class="text-gray-600 text-sm mb-3 line-clamp-2" title="{{ $video->description }}">
                                    {{ $video->description }}
                                </p>
                            @endif

                            <div class="flex justify-between items-center text-sm">
                                <div class="flex items-center text-gray-500">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    <span>{{ $video->user->name }}</span>
                                </div>

                                <span class="text-gray-500" title="{{ $video->created_at->format('d.m.Y H:i') }}">
                                {{ $video->created_at->format('d.m.Y') }}
                            </span>
                            </div>

                            <div class="mt-2">
                                @php
                                    $platformColors = [
                                        'rutube' => 'bg-red-100 text-red-800'
                                    ];
                                    $platformNames = [
                                        'rutube' => 'RuTube'
                                    ];
                                    $color = $platformColors[$video->platform] ?? 'bg-gray-100 text-gray-800';
                                    $name = $platformNames[$video->platform] ?? $video->platform;
                                @endphp
                                <span class="inline-block px-2 py-1 text-xs rounded-full {{ $color }}">
                                {{ $name }}
                            </span>

                                @if(!$video->is_approved && auth()->check() && auth()->user()->role === 'admin')
                                    <span class="inline-block ml-2 px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">
                                На модерации
                            </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if($videos->hasPages())
                <div class="mt-12">
                    {{ $videos->links() }}
                </div>
            @endif
        @endif

        @auth
            <div class="mt-8 text-center">
                <a href="{{ route('videos.create') }}"
                   class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition shadow">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Добавить новое видео
                </a>
            </div>
        @endauth
    </div>
</main>

<footer class="bg-gray-800 text-white py-8 mt-12">
    <div class="container mx-auto px-4">
        <div class="text-center">
            <p class="text-gray-400">© 2025 VideoHost. Все права защищены.</p>
            <p class="text-gray-500 text-sm mt-2">Поддерживаемые платформы: RuTube </p>
        </div>
    </div>
</footer>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            const alerts = document.querySelectorAll('.bg-green-100, .bg-red-100');
            alerts.forEach(alert => {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            });
        }, 5000);

        const deleteForms = document.querySelectorAll('form[data-confirm]');
        deleteForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                if (!confirm(this.getAttribute('data-confirm'))) {
                    e.preventDefault();
                }
            });
        });
    });
</script>
</body>
</html>
