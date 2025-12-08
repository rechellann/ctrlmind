<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            My Clients
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto p-6 bg-white dark:bg-gray-900 shadow rounded-lg">
        <ul class="space-y-2">
            @foreach($clients as $client)
                <li class="p-3 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                    <span>{{ $client->name }} ({{ $client->email }})</span>
                    <a href="{{ route('consultant.chat', $client->id) }}"
                       class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600">
                        Open Chat
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</x-app-layout>
