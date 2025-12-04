<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'VideoHost')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .video-container {
            position: relative;
            padding-bottom: 56.25%;
            height: 0;
            overflow: hidden;
        }
        .video-container iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }
    </style>
</head>
<body>
@include('layouts.navigation')

<main class="py-4">
    <div class="container mx-auto px-4">
        @yield('content')
    </div>
</main>
</body>
</html>
