<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - {{ __('Shared Task') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Shared Task') }}
                </h2>
            </div>
        </header>

        <main>
            <div class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            <!-- Task Details -->
                            <div class="mb-6">
                                <div class="mb-4">
                                    <h3 class="text-lg font-medium">{{ $task->name }}</h3>
                                    <p class="text-sm text-gray-500">{{ __('Shared via link') }}</p>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-500">{{ __('Description') }}</h4>
                                        <p class="mt-1">{{ $task->description ?: __('No description provided.') }}</p>
                                    </div>
                                    <div>
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <h4 class="text-sm font-medium text-gray-500">{{ __('Priority') }}</h4>
                                                <span class="mt-1 px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                    {{ $task->priority == 'high' ? 'bg-red-100 text-red-800' :
                                                       ($task->priority == 'medium' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') }}">
                                                    {{ ucfirst($task->priority) }}
                                                </span>
                                            </div>
                                            <div>
                                                <h4 class="text-sm font-medium text-gray-500">{{ __('Status') }}</h4>
                                                <span class="mt-1 px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                    {{ $task->status == 'done' ? 'bg-green-100 text-green-800' :
                                                       ($task->status == 'in progress' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                                                    {{ ucfirst($task->status) }}
                                                </span>
                                            </div>
                                            <div>
                                                <h4 class="text-sm font-medium text-gray-500">{{ __('Due Date') }}</h4>
                                                <p class="mt-1">{{ $task->due_date->format('Y-m-d') }}</p>
                                            </div>
                                            <div>
                                                <h4 class="text-sm font-medium text-gray-500">{{ __('Link Expires') }}</h4>
                                                <p class="mt-1">{{ $shareableLink->expires_at->format('Y-m-d H:i') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-6 border-t pt-4">
                                <p class="text-sm text-gray-500">
                                    {{ __('This task is shared via a temporary link that will expire on') }} {{ $shareableLink->expires_at->format('Y-m-d H:i') }}.
                                </p>
                                <p class="text-sm text-gray-500 mt-2">
                                    {{ __('To manage this task, please log in to your account.') }}
                                </p>
                                <div class="mt-4">
                                    <a href="{{ route('login') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        {{ __('Log In') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <footer class="bg-white shadow mt-auto py-4">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <p class="text-center text-sm text-gray-500">
                    &copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}. {{ __('All rights reserved.') }}
                </p>
            </div>
        </footer>
    </div>
</body>
</html>
