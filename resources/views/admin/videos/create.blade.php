<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Добавить видео - Админ панель</title>
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
    <div class="container mx-auto px-4 max-w-2xl">
        <h1 class="text-3xl font-bold mb-6">Добавить видео (админ)</h1>

        @if($errors->any())
            <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="p-6">
                <form action="{{ route('admin.videos.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label for="url" class="block text-gray-700 mb-2 font-medium">
                            Ссылка на видео *
                            <span class="text-gray-500 text-sm">(RuTube)</span>
                        </label>
                        <input type="url"
                               name="url"
                               id="url"
                               required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="https://rutube.ru/video/..."
                               value="{{ old('url') }}">
                        <p class="text-gray-500 text-sm mt-1">Поддерживаются только RuTube</p>
                    </div>

                    <div class="mb-4">
                        <label for="title" class="block text-gray-700 mb-2 font-medium">Название видео (обязательно) *</label>
                        <input type="text"
                               name="title"
                               id="title"
                               required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                               value="{{ old('title') }}">
                    </div>

                    <div class="mb-6">
                        <label for="description" class="block text-gray-700 mb-2 font-medium">Описание</label>
                        <textarea name="description"
                                  id="description"
                                  rows="4"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('description') }}</textarea>
                    </div>

                    <div class="flex justify-between items-center">
                        <a href="{{ route('admin.videos') }}"
                           class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                            Отмена
                        </a>
                        <button type="submit"
                                class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            Добавить видео
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="mt-8 bg-blue-50 p-4 rounded-lg">
            <h3 class="font-bold text-blue-800 mb-2">Информация:</h3>
            <ul class="text-blue-700 text-sm space-y-1">
                <li>• Видео добавленное через админку автоматически одобряется</li>
                <li>• Автором видео будет указан текущий администратор</li>
                <li>• После добавления видео сразу появится на сайте</li>
            </ul>
        </div>
    </div>
</main>
</body>
</html>
