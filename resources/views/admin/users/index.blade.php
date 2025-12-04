<!DOCTYPE html>

<html lang="ru">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Управление пользователями - Админ панель</title>

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

        <h1 class="text-3xl font-bold mb-6">Управление пользователями</h1>

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

        <!-- Таблица пользователей -->

        <div class="bg-white rounded-lg shadow overflow-x-auto">

            <table class="min-w-full divide-y divide-gray-200">

                <thead class="bg-gray-50">

                <tr>

                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>

                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Имя</th>

                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>

                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Роль</th>

                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Дата регистрации</th>

                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Видео</th>

                </tr>

                </thead>

                <tbody class="bg-white divide-y divide-gray-200">

                @forelse($users as $user)

                    <tr>

                        <td class="px-6 py-4 whitespace-nowrap">{{ $user->id }}</td>

                        <td class="px-6 py-4 font-medium">{{ $user->name }}</td>

                        <td class="px-6 py-4">{{ $user->email }}</td>

                        <td class="px-6 py-4">

                            <form action="{{ route('admin.users.update-role', $user) }}" method="POST" class="flex items-center space-x-2">

                                @csrf

                                @method('PUT')

                                <select name="role"

                                        class="text-sm border rounded px-2 py-1

{{ $user->role === 'admin' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800' }}">

                                    <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>Пользователь</option>

                                    <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Админ</option>

                                </select>

                                <button type="submit"

                                        class="text-xs bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-600">

                                    Сохранить

                                </button>

                            </form>

                        </td>

                        <td class="px-6 py-4">{{ $user->created_at->format('d.m.Y H:i') }}</td>

                        <td class="px-6 py-4">

<span class="text-sm text-gray-500">

Видео: {{ $user->videos_count ?? 0 }}

</span>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">

                            Пользователи не найдены

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

        @if($users->hasPages())

            <div class="mt-6">

                {{ $users->links() }}

            </div>

        @endif

    </div>

</main>

</body>

</html>
