<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Historical Task Version') }}
            </h2>
            <a href="{{ route('tasks.show', $task->id) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                {{ __('Back to Current Version') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Historical Version Info -->
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-yellow-700">
                                    {{ __('You are viewing a historical version of this task from') }} <strong>{{ $history->created_at->format('Y-m-d H:i') }}</strong>.
                                    {{ __('This version was') }}
                                    @if($history->change_type == 'create')
                                        {{ __('the initial version when the task was created') }}.
                                    @elseif($history->change_type == 'update')
                                        {{ __('after an update was made') }}.
                                    @elseif($history->change_type == 'delete')
                                        {{ __('the last version before the task was deleted') }}.
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Task Details -->
                    <div class="mb-6">
                        <div class="flex justify-between items-start mb-4">
                            <h3 class="text-lg font-medium">{{ $historicalTask->name }}</h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <h4 class="text-sm font-medium text-gray-500">{{ __('Description') }}</h4>
                                <p class="mt-1">{{ $historicalTask->description ?: __('No description provided.') }}</p>
                            </div>
                            <div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-500">{{ __('Priority') }}</h4>
                                        <span class="mt-1 px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                            {{ $historicalTask->priority == 'high' ? 'bg-red-100 text-red-800' :
                                               ($historicalTask->priority == 'medium' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') }}">
                                            {{ ucfirst($historicalTask->priority) }}
                                        </span>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-500">{{ __('Status') }}</h4>
                                        <span class="mt-1 px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                            {{ $historicalTask->status == 'done' ? 'bg-green-100 text-green-800' :
                                               ($historicalTask->status == 'in progress' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                                            {{ ucfirst($historicalTask->status) }}
                                        </span>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-500">{{ __('Due Date') }}</h4>
                                        <p class="mt-1">{{ $historicalTask->due_date->format('Y-m-d') }}</p>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-500">{{ __('Created At') }}</h4>
                                        <p class="mt-1">{{ $historicalTask->created_at->format('Y-m-d H:i') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="mt-6 border-t pt-4">
                        <div class="flex justify-between">
                            <a href="{{ route('tasks.show', $task->id) }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 focus:bg-gray-300 active:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('Back to Current Version') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
