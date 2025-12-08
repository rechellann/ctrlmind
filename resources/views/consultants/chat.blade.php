<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Chat with {{ $user->name }}
        </h2>
    </x-slot>

    <div class="max-w-2xl mx-auto h-[600px] flex flex-col bg-white dark:bg-gray-900 shadow rounded-lg">
        <!-- Messages -->
        <div id="chat-messages" class="flex-1 overflow-y-auto p-4 space-y-3">
            @foreach($messages as $message)
                <div class="{{ $message->user_id == auth()->id() ? 'text-right' : 'text-left' }}">
                    <div class="inline-block px-3 py-2 rounded-2xl 
                        {{ $message->user_id == auth()->id() 
                            ? 'bg-green-500 text-white' 
                            : 'bg-gray-200 dark:bg-gray-700 dark:text-gray-100' }}">
                        {{ $message->content }}
                    </div>
                    <div class="text-xs text-gray-500 mt-1">
                        {{ $message->created_at->diffForHumans() }}
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Input -->
        <div class="border-t border-gray-200 dark:border-gray-700 p-3 bg-gray-50 dark:bg-gray-800">
            <form action="{{ route('consultant.chat.reply', $user->id) }}" method="POST" class="flex items-center space-x-2">
                @csrf
                <input type="text" name="content"
                       class="flex-1 rounded-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:ring-green-500 focus:border-green-500 px-4 py-2"
                       placeholder="Type a reply..." required>
                <button type="submit"
                        class="px-4 py-2 bg-green-500 text-white rounded-full hover:bg-green-600">
                    Send
                </button>
            </form>
        </div>
    </div>

    <script>
        const chatBox = document.getElementById('chat-messages');
        chatBox.scrollTop = chatBox.scrollHeight;
        document.querySelector('input[name="content"]').focus();
    </script>
</x-app-layout>
