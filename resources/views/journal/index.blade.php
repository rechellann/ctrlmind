<x-app-layout>
    <x-slot name="header">
        <!-- Journal navigation with icons -->
        <div class="flex space-x-6">
            <!-- Journal Entries -->
            <a href="{{ route('journal.index') }}" 
               class="flex items-center gap-2 px-4 py-2 rounded {{ request()->routeIs('journal.index') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:text-white' }}">
                <!-- Document icon -->
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" 
                     viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M7 8h10M7 12h10M7 16h10M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                Entries
            </a>

            <!-- Archived -->
            <a href="{{ route('journal.archived') }}" 
               class="flex items-center gap-2 px-4 py-2 rounded {{ request()->routeIs('journal.archived') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:text-white' }}">
                <!-- Archive box icon -->
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" 
                     viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M3 7h18M5 7v13h14V7M9 3h6v4H9V3z" />
                </svg>
                Archives
            </a>
        </div>
    </x-slot>
    
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 text-gray-900 dark:text-gray-100">

                <!-- Free-form journal entry -->
                <div class="mb-8">
                    <form method="POST" action="{{ route('journal.store') }}">
                        @csrf
                        <textarea name="reflection" rows="4" placeholder="Write your thoughts here..."
                                  class="w-full px-4 py-2 rounded border border-gray-600 bg-gray-800 text-white placeholder-gray-400"></textarea>
                        <button type="submit" class="mt-3 bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">
                            Save Journal Entry
                        </button>
                    </form>
                </div>

                <!-- Existing entries -->
                @if($entries->isEmpty())
                    <p class="text-gray-500 dark:text-gray-400">You haven’t written any reflections yet.</p>
                @else
                    <div class="space-y-6">
                        @foreach($entries as $entry)
                            <div class="relative bg-gray-900 text-white rounded-lg shadow p-6">

                                <!-- Dropdown menu (⋮) -->
                                <div x-data="{ open: false }" class="absolute top-2 right-3">
                                    <button @click="open = !open" class="text-gray-400 hover:text-white text-xl font-bold">
                                        ⋮
                                    </button>

                                    <!-- Dropdown -->
                                    <div x-show="open" @click.away="open = false"
                                         x-transition:enter="transition ease-out duration-200"
                                         x-transition:enter-start="opacity-0 transform scale-95"
                                         x-transition:enter-end="opacity-100 transform scale-100"
                                         x-transition:leave="transition ease-in duration-150"
                                         x-transition:leave-start="opacity-100 transform scale-100"
                                         x-transition:leave-end="opacity-0 transform scale-95"
                                         class="absolute right-0 mt-2 bg-gray-800 border border-gray-700 rounded shadow-lg z-10 w-48">
                                        <ul class="text-sm text-white divide-y divide-gray-700">
                                            <!-- Share -->
                                            <li>
                                                <form method="POST" action="{{ route('journal.share', $entry->id) }}">
                                                    @csrf
                                                    <button type="submit" class="w-full text-left px-4 py-2 hover:bg-gray-700">
                                                        {{ $entry->shared_with_consultant ? 'Unshare with Consultant' : 'Share with Consultant' }}
                                                    </button>
                                                </form>
                                            </li>

                                            <!-- Edit (only if within 24h) -->
                                            @if($entry->created_at->timezone('Asia/Manila')->gt(now('Asia/Manila')->subDay()))
                                                <li>
                                                    <a href="{{ route('journal.edit', $entry->id) }}" class="block px-4 py-2 hover:bg-gray-700">
                                                        Edit
                                                    </a>
                                                </li>
                                            @endif

                                            <!-- Archive -->
                                            <li>
                                                <form method="POST" action="{{ route('journal.archiveEntry', $entry->id) }}"
                                                      onsubmit="return confirm('Are you sure you want to archive this entry?')">
                                                    @csrf
                                                    <button type="submit" class="w-full text-left px-4 py-2 hover:bg-gray-700">
                                                        Archive
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <!-- Timestamp -->
                                <span class="text-sm text-gray-400">
                                    {{ $entry->created_at->timezone('Asia/Manila')->format('F j, Y \a\t g:i A') }}
                                </span>

                                <!-- Quote -->
                                @if($entry->quote)
                                    <p class="italic mt-2">“{{ $entry->quote->text }}” — {{ $entry->quote->author }}</p>
                                @endif

                                <!-- Reflection -->
                                <p class="mt-2">{{ $entry->reflection }}</p>

                                <!-- Edit deadline note -->
                                @if($entry->created_at->timezone('Asia/Manila')->gt(now('Asia/Manila')->subDay()))
                                    <div class="mt-4 flex justify-end">
                                        <span class="text-xs text-gray-400 italic">
                                            Edit until {{ $entry->created_at->timezone('Asia/Manila')->addDay()->format('M j, g:i A') }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
