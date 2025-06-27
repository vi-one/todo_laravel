<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Task Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    <!-- Task Details -->
                    <div class="mb-6">
                        <div class="flex justify-between items-start mb-4">
                            <h3 class="text-lg font-medium">{{ $task->name }}</h3>
                            <div class="flex gap-2">
                                <a href="{{ route('tasks.edit', $task->id) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    {{ __('Edit') }}
                                </a>
                                <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 focus:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150" onclick="return confirm('{{ __('Are you sure you want to delete this task?') }}')">
                                        {{ __('Delete') }}
                                    </button>
                                </form>
                            </div>
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
                                        <h4 class="text-sm font-medium text-gray-500">{{ __('Created At') }}</h4>
                                        <p class="mt-1">{{ $task->created_at->format('Y-m-d H:i') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Shareable Link Section -->
                    <div class="mb-6 border-t pt-6">
                        <h3 class="text-lg font-medium mb-4">{{ __('Share Task') }}</h3>

                        @if(session('shareableLink'))
                            <div class="mb-4">
                                <label for="shareableLink" class="block text-sm font-medium text-gray-700">{{ __('Shareable Link') }}</label>
                                <div class="mt-1 flex rounded-md shadow-sm">
                                    <input type="text" id="shareableLink" value="{{ session('shareableLink') }}" readonly class="flex-1 block w-full rounded-none rounded-l-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <button type="button" onclick="copyToClipboard()" class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 rounded-r-md bg-gray-50 text-gray-500 text-sm">
                                        {{ __('Copy') }}
                                    </button>
                                </div>
                            </div>
                        @endif

                        @if($task->shareableLinks->count() > 0)
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-700 mb-2">{{ __('Active Shareable Links') }}</h4>
                                @foreach($task->shareableLinks as $link)
                                    <div class="flex items-center justify-between bg-gray-50 p-3 rounded mb-2">
                                        <div>
                                            <p class="text-sm">{{ __('Expires at') }}: {{ $link->expires_at->format('Y-m-d H:i') }}</p>
                                            <p class="text-xs text-gray-500">{{ route('shared-task.show', $link->token) }}</p>
                                        </div>
                                        <form action="{{ route('shareable-links.destroy', $link->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('{{ __('Are you sure you want to delete this link?') }}')">
                                                {{ __('Delete') }}
                                            </button>
                                        </form>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <form action="{{ route('tasks.share', $task->id) }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label for="expires_at" class="block text-sm font-medium text-gray-700">{{ __('Expires At') }}</label>
                                <input type="datetime-local" name="expires_at" id="expires_at" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('expires_at')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
                                    </svg>
                                    {{ __('Create Shareable Link') }}
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Task History Section -->
                    <div class="border-t pt-6">
                        <h3 class="text-lg font-medium mb-4">{{ __('Task History') }}</h3>

                        @if($histories->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Date') }}</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('User') }}</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Action') }}</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Changes') }}</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"></th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($histories as $history)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $history->created_at->format('Y-m-d H:i') }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $history->user ? $history->user->name : __('System') }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    @if($history->change_type == 'create')
                                                        <span class="text-green-600">{{ __('Created') }}</span>
                                                    @elseif($history->change_type == 'update')
                                                        <span class="text-blue-600">{{ __('Updated') }}</span>
                                                    @elseif($history->change_type == 'delete')
                                                        <span class="text-red-600">{{ __('Deleted') }}</span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 text-sm text-gray-500">
                                                    @if($history->change_type == 'update')
                                                        <ul class="list-disc list-inside">
                                                            @foreach($history->changes['old'] as $field => $value)
                                                                @if(in_array($field, ['name', 'description', 'priority', 'status', 'due_date']) && $value != $history->changes['new'][$field])
                                                                    <li>
                                                                        <strong>{{ ucfirst($field) }}:</strong>
                                                                        {{ $field == 'due_date' ? \Carbon\Carbon::parse($value)->format('Y-m-d') : $value }}
                                                                        &rarr;
                                                                        {{ $field == 'due_date' ? \Carbon\Carbon::parse($history->changes['new'][$field])->format('Y-m-d') : $history->changes['new'][$field] }}
                                                                    </li>
                                                                @endif
                                                            @endforeach
                                                        </ul>
                                                    @elseif($history->change_type == 'create')
                                                        {{ __('Task created') }}
                                                    @elseif($history->change_type == 'delete')
                                                        {{ __('Task deleted') }}
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    <a href="{{ route('tasks.history.show', [$task->id, $history->id]) }}" class="text-indigo-600 hover:text-indigo-900">
                                                        {{ __('View') }}
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-gray-500">{{ __('No history available for this task.') }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function copyToClipboard() {
            const shareableLinkInput = document.getElementById('shareableLink');
            shareableLinkInput.select();
            document.execCommand('copy');
            alert('{{ __('Link copied to clipboard!') }}');
        }
    </script>
</x-app-layout>
