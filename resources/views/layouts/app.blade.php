<!DOCTYPE html>
<html lang="en" x-data="{ dark: localStorage.getItem('dark') === 'true' }" :class="{ 'dark': dark }">

<head>
    <meta charset="UTF-8">
    <title>PollMaster</title>

    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        function toggleDark() {
            let isDark = document.documentElement.classList.toggle('dark');
            localStorage.setItem('dark', isDark);
        }
    </script>
</head>

<body class="bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-100">

<div class="flex min-h-screen">

    @include('layouts.sidebar')

    <div class="flex-1 flex flex-col">

        @include('layouts.topbar')

        <main class="p-6">
            {{ $slot }}
        </main>

    </div>

</div>

</body>
</html>