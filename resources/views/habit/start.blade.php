<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>ようこそ</title>
        
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body>
        <div class="bg-yellow-50/25">
        <div class="h-screen w-screen flex justify-center items-center flex-col font-sans">
            <div class="text-orange-300 text-6xl underline underline-offset-8">HabiHabit</div>
            
        

            <p class="text-xl mb-8 mt-10">HabiHabitは目標の達成や行動の習慣化をサポートするサービスです</p>
            <div class="flex">
                <a href="{{ route('register') }}" class="bg-amber-100 py-4 px-24 text-lg rounded-lg mr-6">新規登録</a>
                <a href="{{ route('login') }}" class="bg-amber-100 py-4 px-24 text-lg rounded-lg">ログイン</a>
            </div>
            </div>
       
        </div>
    </body>
</html>







