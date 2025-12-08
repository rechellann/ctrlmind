<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('My Clients') }}
        </h2>
    </x-slot>

    <div class="py-6 px-4">
        @foreach($clients as $client)
            <div class="flex items-center justify-between bg-gray-100 dark:bg-gray-800 p-4 rounded mb-2">
                <div>
                    <p class="font-bold text-gray-900 dark:text-gray-100">{{ $client->name }}</p>
                    <p class="italic text-gray-600 dark:text-gray-400">{{ $client->email }}</p>
                </div>
                <div>
                    <a href="{{ route('consultant.chat', $client->id) }}"
                       class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">
                        Chat
                    </a>
                </div>
            </div>
        @endforeach
    </div>
</x-app-layout>
