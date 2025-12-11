<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редактирование видео - Админ панель</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">

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
                <form action="{{ route('admin.videos.update', $video) }}" method="POST" id="editForm">
                    @csrf
                    @method('PUT')

                    <div class="mb-6">
                        <label class="block text-gray-700 mb-2 font-medium">Текущее видео</label>
                        <div class="video-container mb-4">
                            <iframe src="https://rutube.ru/play/embed/{{ $video->video_id }}"
                                    frameborder="0"
                                    allowfullscreen></iframe>
                        </div>
                        <div class="text-sm text-gray-500 space-y-1">
                            <p><strong>Платформа:</strong> RuTube</p>
                            <p><strong>Ссылка:</strong> <a href="{{ $video->url }}" target="_blank" class="text-blue-600 hover:underline">{{ $video->url }}</a></p>
                            <p><strong>Автор:</strong> {{ $video->user->name }}</p>
                            <p><strong>Добавлено:</strong> {{ $video->created_at->format('d.m.Y H:i') }}</p>
                        </div>
                    </div>

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
                </form>

                <form action="{{ route('admin.videos.destroy', $video) }}" method="POST" id="deleteForm">
                    @csrf
                    @method('DELETE')
                </form>

                <div class="flex justify-between items-center">
                    <div class="flex space-x-4">
                        <a href="{{ route('admin.videos') }}"
                           class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                            Отмена
                        </a>

                        <button type="submit"
                                form="editForm"
                                class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            Сохранить изменения
                        </button>
                    </div>

                    <button type="submit"
                            form="deleteForm"
                            onclick="return confirm('Вы уверены, что хотите удалить это видео?')"
                            class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                        Удалить видео
                    </button>
                </div>
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deleteForm = document.getElementById('deleteForm');
        const deleteButton = document.querySelector('button[form="deleteForm"]');

        if (deleteButton) {
            deleteButton.addEventListener('click', function(e) {
                if (!confirm('Вы уверены, что хотите удалить это видео? Действие нельзя отменить.')) {
                    e.preventDefault();
                }
            });
        }

        const editForm = document.getElementById('editForm');
        if (editForm) {
            editForm.addEventListener('submit', function() {
                console.log('Отправка формы редактирования (PUT)');
            });
        }

        if (deleteForm) {
            deleteForm.addEventListener('submit', function() {
                console.log('Отправка формы удаления (DELETE)');
            });
        }
    });
</script>
</body>
</html>
