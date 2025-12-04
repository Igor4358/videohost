<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редактирование видео - Админ панель</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
<nav class="bg-gray-800 text-white shadow-lg">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center h-16">
            <div class="flex items-center">
                <a href="{{ route('admin.dashboard') }}" class="text-xl font-bold">Админ панель</a>
                <div class="ml-10 flex items-center space-x-4">
                    <a href="{{ route('admin.videos') }}" class="text-gray-300 hover:text-white">← Назад к видео</a>
                </div>
            </div>
            <div class="flex items-center">
                <a href="{{ route('home') }}" class="text-blue-300 hover:text-blue-100 mr-4">На сайт</a>
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
    <div class="container mx-auto px-4 max-w-4xl">
        <div class="mb-6">
            <h1 class="text-3xl font-bold">Редактирование видео</h1>
            <p class="text-gray-600 mt-2">ID: {{ $video->id }}</p>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="p-6">
                <form action="{{ route('admin.videos.update', $video) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Предпросмотр видео -->
                    <div class="mb-6">
                        <label class="block text-gray-700 mb-2 font-medium">Текущее видео</label>
                        <div class="video-container mb-4">
                            @if($video->platform === 'rutube')
                                <iframe src="https://rutube.ru/play/embed/{{ $video->video_id }}"
                                        frameborder="0"
                                        allowfullscreen></iframe>
                            @elseif($video->platform === 'vk')
                                @php
                                    $parts = explode('_', $video->video_id);
                                    $oid = $parts[0] ?? '';
                                    $id = $parts[1] ?? '';
                                @endphp
                                <iframe src="https://vk.com/video_ext.php?oid={{ $oid }}&id={{ $id }}"
                                        frameborder="0"
                                        allowfullscreen></iframe>
                            @elseif($video->platform === 'youtube')
                                <iframe src="https://www.youtube.com/embed/{{ $video->video_id }}"
                                        frameborder="0"
                                        allowfullscreen></iframe>
                            @endif
                        </div>
                        <div class="text-sm text-gray-500 space-y-1">
                            <p><strong>Платформа:</strong> {{ $video->platform }}</p>
                            <p><strong>Ссылка:</strong> <a href="{{ $video->url }}" target="_blank" class="text-blue-600 hover:underline">{{ $video->url }}</a></p>
                            <p><strong>Автор:</strong> {{ $video->user->name }}</p>
                            <p><strong>Добавлено:</strong> {{ $video->created_at->format('d.m.Y H:i') }}</p>
                        </div>
                    </div>

                    <!-- Название -->
                    <div class="mb-4">
                        <label for="title" class="block text-gray-700 mb-2 font-medium">Название *</label>
                        <input type="text"
                               name="title"
                               id="title"
                               value="{{ old('title', $video->title) }}"
                               required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('title')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Описание -->
                    <div class="mb-4">
                        <label for="description" class="block text-gray-700 mb-2 font-medium">Описание</label>
                        <textarea name="description"
                                  id="description"
                                  rows="4"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('description', $video->description) }}</textarea>
                        @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Статус -->
                    <div class="mb-6">
                        <label class="flex items-center">
                            <input type="checkbox"
                                   name="is_approved"
                                   value="1"
                                   {{ $video->is_approved ? 'checked' : '' }}
                                   class="mr-2 h-5 w-5">
                            <span class="text-gray-700">Видео одобрено (отображается на сайте)</span>
                        </label>
                    </div>

                    <!-- Кнопки -->
                    <div class="flex justify-between items-center">
                        <div class="flex space-x-4">
                            <a href="{{ route('admin.videos') }}"
                               class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                                Отмена
                            </a>
                            <button type="submit"
                                    class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                Сохранить изменения
                            </button>
                        </div>

                        <!-- Кнопка удаления -->
                        <form action="{{ route('admin.videos.destroy', $video) }}"
                              method="POST"
                              onsubmit="return confirm('Вы уверены, что хотите удалить это видео?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                                Удалить видео
                            </button>
                        </form>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

<style>
    .video-container {
        position: relative;
        padding-bottom: 56.25%;
        height: 0;
        overflow: hidden;
        background: #000;
        border-radius: 8px;
    }

    .video-container iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border: 0;
    }
</style>
</body>
</html>
