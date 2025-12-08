<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Saved Quotes') }}
        </h2>
    </x-slot>

    <div class="py-7">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 text-gray-900 dark:text-gray-100">
                
                @if($userQuotes->isEmpty())
                    <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                        <p class="text-gray-500 dark:text-gray-400">You haven’t saved any quotes yet.</p>
                    </div>
                @else
                    <div class="space-y-6">
                        @foreach($userQuotes as $userQuote)
                            @php $quote = $userQuote->quote; @endphp
                            <!-- Quote container -->
                            <div class="bg-gray-900 text-white rounded-lg shadow p-6">
                                <p class="text-lg italic">“{{ $quote->text }}”</p>
                                @if($quote->author)
                                    <p class="text-sm text-gray-400">— {{ $quote->author }}</p>
                                @endif

                                <div class="mt-4 flex items-center gap-6">
                                    <!-- Unsave -->
                                    <form method="POST" action="{{ route('quotes.toggle') }}">
                                        @csrf
                                        <input type="hidden" name="quote_id" value="{{ $quote->id }}">
                                        <button type="submit" title="Unsave">
                                            <span class="text-red-500 text-2xl">♥</span>
                                        </button>
                                    </form>

                                    <!-- Pin / Unpin -->
                                    <form method="POST" action="{{ route('quotes.pin', $quote->id) }}">
                                        @csrf
                                        <button type="submit"
                                                class="px-3 py-1 rounded {{ $userQuote->pinned ? 'bg-yellow-600 text-white' : 'bg-yellow-500 text-white' }}">
                                            {{ $userQuote->pinned ? 'Unpin' : 'Pin' }}
                                        </button>
                                    </form>

                                    <!-- Share -->
                                    <form method="POST" action="{{ route('journal.redirect') }}">
                                        @csrf
                                        <input type="hidden" name="quote_id" value="{{ $quote->id }}">
                                        <button type="submit"
                                                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-1 rounded">
                                            Share
                                        </button>
                                    </form>
                                </div>

                                @if($userQuote->pinned)
                                    <span class="mt-2 inline-block text-yellow-400">📌 Pinned</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>
