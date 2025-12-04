@extends('layouts.app')

@section('title', 'Добавить видео')

@section('content')
    <div class="max-w-2xl mx-auto py-8">
        <h1 class="text-3xl font-bold mb-6">Добавить видео</h1>

        <form action="{{ route('videos.store') }}" method="POST" class="bg-white p-6 rounded-lg shadow-md">
            @csrf

            <div class="mb-4">
                <label for="url" class="block text-gray-700 mb-2 font-medium">
                    Ссылка на видео
                    <span class="text-gray-500 text-sm">(RuTube)</span>
                </label>
                <input type="url"
                       name="url"
                       id="url"
                       required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                       placeholder="Пример: https://rutube.ru/video/... "
                       value="{{ old('url') }}">
                @error('url')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                <div class="text-gray-500 text-sm mt-2 space-y-1">
                    <p>✅ Поддерживаемые платформы:</p>
                    <ul class="list-disc pl-5">
                        <li>RuTube - https://rutube.ru/video/...</li>
                    </ul>
                </div>
            </div>

            <div class="mb-4">
                <label for="title" class="block text-gray-700 mb-2">Название</label>
                <input type="text"
                       name="title"
                       id="title"
                       required
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('title')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="description" class="block text-gray-700 mb-2">Описание</label>
                <textarea name="description"
                          id="description"
                          rows="4"
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
            </div>

            <button type="submit"
                    class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition duration-300">
                Добавить видео
            </button>
        </form>
    </div>
@endsection
