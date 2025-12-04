@extends('layouts.app')

@section('title', $video->title)

@section('content')
    <div class="max-w-6xl mx-auto py-8">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <!-- Видео -->
            <div class="p-4">
                <h1 class="text-3xl font-bold mb-4">{{ $video->title }}</h1>

                <div class="video-container mb-6">
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
                        <iframe
                            src="https://vk.com/video_ext.php?oid={{ explode('_', $video->video_id)[0] }}&id={{ explode('_', $video->video_id)[1] }}&hash="
                            frameborder="0"
                            allowfullscreen>
                        </iframe>
                    @elseif($video->platform === 'youtube')
                        <iframe
                            src="https://www.youtube.com/embed/{{ $video->video_id }}"
                            frameborder="0"
                            allowfullscreen>
                        </iframe>
                    @elseif($video->platform === 'vimeo')
                        <iframe
                            src="https://player.vimeo.com/video/{{ $video->video_id }}"
                            frameborder="0"
                            allowfullscreen>
                        </iframe>
                    @else
                        <div class="text-center py-12">
                            <p class="text-gray-500">Платформа не поддерживается для встраивания</p>
                            <a href="{{ $video->url }}" target="_blank"
                               class="mt-4 inline-block bg-blue-500 text-white px-4 py-2 rounded">
                                Смотреть на оригинальном сайте
                            </a>
                        </div>
                    @endif
                </div>
                <!-- Информация о видео -->
                <div class="mb-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center">
                            <div class="mr-4">
                                <span class="text-gray-500 text-sm">Автор:</span>
                                <span class="font-medium ml-2">{{ $video->user->name }}</span>
                            </div>
                            <div>
                                <span class="text-gray-500 text-sm">Дата:</span>
                                <span class="ml-2">{{ $video->created_at->format('d.m.Y H:i') }}</span>
                            </div>
                        </div>

                        @if(auth()->check() && auth()->user()->role === 'admin')
                            <div>
                                <a href="{{ route('admin.videos.edit', $video) }}"
                                   class="bg-blue-500 text-white px-3 py-1 rounded text-sm hover:bg-blue-600">
                                    Редактировать
                                </a>
                            </div>
                        @endif
                    </div>

                    <!-- Описание -->
                    @if($video->description)
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="font-bold text-lg mb-2">Описание</h3>
                            <p class="text-gray-700 whitespace-pre-line">{{ $video->description }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Кнопка назад -->
        <div class="mt-6">
            <a href="{{ route('videos.index') }}"
               class="inline-flex items-center text-blue-600 hover:text-blue-800">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Назад к списку видео
            </a>
        </div>
    </div>

    <style>
        .video-container {
            position: relative;
            padding-bottom: 56.25%; /* 16:9 Aspect Ratio */
            height: 0;
            overflow: hidden;
            max-width: 100%;
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
@endsection
