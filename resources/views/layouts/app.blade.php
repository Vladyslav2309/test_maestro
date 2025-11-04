<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Новини')</title>

    <script src="https://cdn.tailwindcss.com"></script>


    <style>
        .prose a {
            color: #1d4ed8;
            text-decoration: underline;
            text-decoration-style: dotted;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .prose a:hover {
            background: #e0f2fe;
            color: #1e3a8a;
        }
    </style>

</head>
<body class="bg-gray-50 font-sans text-gray-900">

<header class="bg-blue-900 text-white shadow p-4 mb-8">
    <div class="container mx-auto flex justify-between items-center">
        <a href="{{ route('news.index') }}" class="text-2xl font-bold">Cайт аля новин</a>
        <nav>
            <a href="{{ route('news.index') }}" class="mr-4 hover:underline">Головна</a>
        </nav>
    </div>
</header>

<main class="container mx-auto px-4">
    @yield('content')
</main>

<footer class="bg-blue-900 text-white shadow mt-12 p-4">
    <div class="container mx-auto text-center text-sm">
        &copy; {{ date('Y') }} Сайт новин для Маестро. Всі права захищені.
    </div>
</footer>

</body>
</html>

