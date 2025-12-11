<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Управление видео - Админ панель</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
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
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold">Управление видео</h1>
            <a href="{{ route('admin.videos.create') }}"
               class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                + Добавить видео
            </a>
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

        <div class="bg-white rounded-lg shadow overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Название</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Автор</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Платформа</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Статус</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Дата</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Действия</th>
                </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                @forelse($videos as $video)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $video->id }}</td>
                        <td class="px-6 py-4">
                            <a href="{{ route('videos.show', $video) }}" class="text-blue-600 hover:text-blue-800 font-medium">
                                {{ Str::limit($video->title, 40) }}
                            </a>
                        </td>
                        <td class="px-6 py-4">{{ $video->user->name }}</td>
                        <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs rounded-full
                                    @if($video->platform === 'rutube') bg-red-100 text-red-800
                                    @elseif($video->platform === 'vk') bg-blue-100 text-blue-800
                                    @elseif($video->platform === 'youtube') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ $video->platform }}
                                </span>
                        </td>
                        <td class="px-6 py-4">
                            @if($video->is_approved)
                                <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">✓ Одобрено</span>
                            @else
                                <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">На модерации</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">{{ $video->created_at->format('d.m.Y H:i') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex space-x-2">
                                <a href="{{ route('videos.show', $video) }}"
                                   class="text-blue-600 hover:text-blue-900 text-sm">Просмотр</a>

                                @if(!$video->is_approved)
                                    <form action="{{ route('admin.videos.approve', $video) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="text-yellow-600 hover:text-yellow-900 text-sm">
                                            Одобрить
                                        </button>
                                    </form>
                                @endif

                                <a href="{{ route('admin.videos.edit', $video) }}"
                                   class="text-green-600 hover:text-green-900 text-sm">Редактировать</a>

                                <form action="{{ route('admin.videos.destroy', $video) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="text-red-600 hover:text-red-900 text-sm"
                                            onclick="return confirm('Удалить видео?')">
                                        Удалить
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                            Видео не найдены
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        @if($videos->hasPages())
            <div class="mt-6">
                {{ $videos->links() }}
            </div>
        @endif
    </div>
</main>
</body>
</html>
