<nav class="bg-gray-800">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center h-16">
            <div class="flex items-center">
                <a href="{{ route('home') }}" class="text-white font-bold">VideoHost</a>

                @auth
                    <div class="ml-10 flex items-center space-x-4">
                        <a href="{{ route('videos.index') }}" class="text-gray-300 hover:text-white">Видео</a>
                        <a href="{{ route('videos.create') }}" class="text-gray-300 hover:text-white">Добавить видео</a>

                        @if(Auth::user()->role === 'admin')
                            <a href="{{ route('admin.videos') }}" class="text-red-300 hover:text-red-100">Админка</a>
                        @endif
                    </div>
                @endauth
            </div>

            <div class="flex items-center">
                @auth
                    <span class="text-gray-300 mr-4">{{ Auth::user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-gray-300 hover:text-white">Выйти</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-gray-300 hover:text-white">Войти</a>
                    <a href="{{ route('register') }}" class="ml-4 text-gray-300 hover:text-white">Регистрация</a>
                @endauth
            </div>
        </div>
    </div>
</nav>
