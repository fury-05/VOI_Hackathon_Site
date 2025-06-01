@extends('layouts.app') {{-- Assuming you want to use your main app layout --}}

@section('content')
<div class="py-8 md:py-12">
    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-primary-accent">Manage Hackathons</h1>
            <a href="{{ route('admin.hackathons.create') }}"
               class="inline-flex items-center px-4 py-2 bg-primary-accent border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-accent transition ease-in-out duration-150">
                Create New Hackathon
            </a>
        </div>

        {{-- Display Session Success Message --}}
        @if (session('success'))
            <div class="mb-4 p-3 bg-green-100 dark:bg-green-700/30 border border-green-400 dark:border-green-600 text-green-700 dark:text-green-200 rounded-md app-card" role="alert">
                <strong class="font-bold">Success!</strong>
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <div class="app-card rounded-lg shadow-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-[var(--app-border-color)]">
                    <thead class="bg-[var(--app-bg-secondary)]">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-[var(--app-text-secondary)] uppercase tracking-wider">Name</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-[var(--app-text-secondary)] uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-[var(--app-text-secondary)] uppercase tracking-wider">Start Date</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-[var(--app-text-secondary)] uppercase tracking-wider">End Date</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-[var(--app-text-secondary)] uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-[var(--app-bg-primary)] divide-y divide-[var(--app-border-color)]">
                        @forelse ($hackathons as $hackathon)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-[var(--app-text-primary)]">
                                    {{ $hackathon->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-[var(--app-text-secondary)]">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        @if($hackathon->status === 'upcoming') bg-yellow-100 text-yellow-800 dark:bg-yellow-700 dark:text-yellow-100
                                        @elseif($hackathon->status === 'registration_open') bg-blue-100 text-blue-800 dark:bg-blue-700 dark:text-blue-100
                                        @elseif($hackathon->status === 'ongoing') bg-green-100 text-green-800 dark:bg-green-700 dark:text-green-100
                                        @elseif($hackathon->status === 'judging') bg-purple-100 text-purple-800 dark:bg-purple-700 dark:text-purple-100
                                        @elseif($hackathon->status === 'completed') bg-gray-100 text-gray-800 dark:bg-gray-600 dark:text-gray-200
                                        @else bg-gray-200 text-gray-700 @endif">
                                        {{ Str::title(str_replace('_', ' ', $hackathon->status)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-[var(--app-text-secondary)]">
                                    {{ \Carbon\Carbon::parse($hackathon->start_datetime)->format('M d, Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-[var(--app-text-secondary)]">
                                    {{ \Carbon\Carbon::parse($hackathon->end_datetime)->format('M d, Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    {{-- Add links for Edit, View, Delete later --}}
                                    <a href="{{ route('admin.hackathons.show', $hackathon->id) }}" class="text-primary-accent hover:underline mr-3">View</a>

                                    <a href="{{ route('admin.hackathons.edit', $hackathon->id) }}" class="text-indigo-600 dark:text-indigo-400 hover:underline mr-3">Edit</a> {{-- ADDED THIS LINK --}}
                                    {{-- DELETE Form --}}
                                        <form action="{{ route('admin.hackathons.destroy', $hackathon->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this hackathon? This action cannot be undone.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 dark:text-red-400 hover:underline">
                                                Delete
                                            </button>
                                        </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-center text-[var(--app-text-secondary)]">
                                    No hackathons found. <a href="{{ route('admin.hackathons.create') }}" class="text-primary-accent hover:underline">Create one now!</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{-- Pagination Links --}}
            <div class="p-4">
                {{ $hackathons->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
