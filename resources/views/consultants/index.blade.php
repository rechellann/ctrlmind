<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Consultants
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto p-6 bg-white dark:bg-gray-900 shadow rounded-lg">
        <ul class="space-y-2">
            @foreach($consultants as $consultant)
                <li class="p-3 border-b border-gray-200 dark:border-gray-700">
                    {{ $consultant->name }} ({{ $consultant->email }})
                </li>
            @endforeach
        </ul>
    </div>
</x-app-layout>
