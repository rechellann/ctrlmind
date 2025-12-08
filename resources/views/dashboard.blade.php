<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('CTRL+Mind: Mental Wellness Support System') }}
        </h2>
    </x-slot>

    <div class="py-7 space-y-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Quote of the Day -->
            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-300 border-b border-gray-700 pb-2 mb-4">
                    Quote of the Day
                </h3>

                @if($quote)
                    <div class="p-6 bg-gray-900 text-white rounded-lg">
                        <p class="text-lg italic">“{{ $quote->text }}”</p>
                        @if($quote->author)
                            <p class="text-sm text-gray-400">— {{ $quote->author }}</p>
                        @endif

                        <form method="POST" action="{{ route('quotes.toggle') }}" class="mt-3">
                            @csrf
                            <input type="hidden" name="quote_id" value="{{ $quote->id }}">
                            <button type="submit" class="focus:outline-none">
                                @if($savedQuoteIds->contains($quote->id))
                                    <span class="text-red-500 text-2xl">♥</span> {{-- filled heart --}}
                                @else
                                    <span class="text-gray-400 text-2xl">♡</span> {{-- empty heart --}}
                                @endif
                            </button>
                        </form>
                    </div>
                @endif
                
            </div>

            <!-- Mood Tracker -->
            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-300 border-b border-gray-700 pb-2 mb-4">
                    Mood Tracker
                </h3>
                <div class="max-w-xl mx-auto">
                    <p class="mb-6 text-center text-gray-400 text-base">How are you feeling today?</p>
                    <form method="POST" action="{{ route('mood.store') }}">
                        @csrf
                        <div class="flex justify-center space-x-6 text-6xl">
                            @foreach(['🙂','😐','😢','😠','😴','😍'] as $emoji)
                                <button type="submit" name="mood" value="{{ $emoji }}"
                                        class="hover:scale-125 transition transform duration-150 ease-in-out focus:outline-none">
                                    {{ $emoji }}
                                </button>
                            @endforeach
                        </div>
                    </form>
                </div>
            </div>

            <!-- Mental Wellness Resources -->
            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-300 border-b border-gray-700 pb-2 mb-4">
                    Mental Wellness Resources
                </h3>
                <div class="bg-gray-900 text-white rounded-lg shadow p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @forelse($featuredResources as $res)
                            <div class="bg-gray-800 rounded-lg p-4 shadow">
                                <h4 class="text-md font-semibold text-white">{{ $res->title }}</h4>
                                <p class="text-sm text-gray-400">{{ $res->description }}</p>
                                <a href="{{ $res->url }}" target="_blank"
                                   class="text-blue-400 hover:text-blue-300 text-sm mt-2 inline-block">View →</a>
                            </div>
                        @empty
                            <p class="text-gray-400">No featured resources yet.</p>
                        @endforelse
                    </div>
                    <div class="mt-4 flex justify-end">
                        <a href="{{ route('resources.index') }}" class="text-blue-400 hover:text-blue-300 text-sm">
                            Browse all resources →
                        </a>
                    </div>
                </div>
            </div>

            <!-- Daily Check-in -->
            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-300 border-b border-gray-700 pb-2 mb-4">
                    Daily Check-in
                </h3>
                <a href="{{ route('checkin.start') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                    Start Today's Check-in
                </a>
            </div>

            <!-- Footer -->
            <footer class="text-center text-sm text-gray-500 dark:text-gray-400 mt-8">
                © 2025 CTRL+Mind EVSU
            </footer>

        </div>
    </div>
</x-app-layout>
