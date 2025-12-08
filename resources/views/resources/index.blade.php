<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-100">Resource Library</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto space-y-6">

            <!-- Resource Grid -->
            <div class="bg-gray-900 text-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-300 border-b border-gray-700 pb-2 mb-4">
                    Mental Health Resources
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($resources as $resource)
                        <div class="bg-gray-800 rounded-lg p-4 shadow hover:shadow-lg transition">
                            <h4 class="text-md font-semibold text-white">{{ $resource->title }}</h4>
                            <p class="text-sm text-gray-400">{{ $resource->description }}</p>
                            <div class="mt-3 flex justify-between items-center">
                                <span class="text-xs px-2 py-1 rounded bg-blue-600">{{ ucfirst($resource->type) }}</span>
                                @if($resource->url)
                                    <a href="{{ $resource->url }}" target="_blank"
                                       class="text-blue-400 hover:text-blue-300 text-sm">View →</a>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-400">No resources found.</p>
                    @endforelse
                </div>
                <div class="mt-6">
                    {{ $resources->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
