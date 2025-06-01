@extends('layouts.app')

@section('content')
<div class="py-8 md:py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="app-card rounded-lg shadow-xl overflow-hidden">
            <div class="p-6 md:p-8">
                {{-- Header --}}
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 pb-4 border-b border-[var(--app-border-color)]">
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold text-primary-accent">{{ $hackathon->name }}</h1>
                        <p class="text-sm text-[var(--app-text-secondary)]">
                            Status:
                            <span class="font-semibold px-2 py-0.5 rounded-full text-xs
                                @if($hackathon->status === 'upcoming') bg-yellow-100 text-yellow-800 dark:bg-yellow-700 dark:text-yellow-100
                                @elseif($hackathon->status === 'registration_open') bg-blue-100 text-blue-800 dark:bg-blue-700 dark:text-blue-100
                                @elseif($hackathon->status === 'ongoing') bg-green-100 text-green-800 dark:bg-green-700 dark:text-green-100
                                @elseif($hackathon->status === 'judging') bg-purple-100 text-purple-800 dark:bg-purple-700 dark:text-purple-100
                                @elseif($hackathon->status === 'completed') bg-gray-100 text-gray-800 dark:bg-gray-600 dark:text-gray-200
                                @else bg-gray-200 text-gray-700 @endif">
                                {{ Str::title(str_replace('_', ' ', $hackathon->status)) }}
                            </span>
                        </p>
                    </div>
                    <div class="mt-3 sm:mt-0">
                        <a href="{{ route('admin.hackathons.edit', $hackathon->id) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 dark:bg-indigo-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 dark:hover:bg-indigo-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition ease-in-out duration-150">
                            Edit Hackathon
                        </a>
                    </div>
                </div>

                {{-- Dates --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4 mb-6 text-sm">
                    <div>
                        <strong class="block text-[var(--app-text-secondary)]">Starts:</strong>
                        <span class="text-[var(--app-text-primary)]">{{ \Carbon\Carbon::parse($hackathon->start_datetime)->format('D, M j, Y - g:i A') }}</span>
                    </div>
                    <div>
                        <strong class="block text-[var(--app-text-secondary)]">Ends:</strong>
                        <span class="text-[var(--app-text-primary)]">{{ \Carbon\Carbon::parse($hackathon->end_datetime)->format('D, M j, Y - g:i A') }}</span>
                    </div>
                    <div>
                        <strong class="block text-[var(--app-text-secondary)]">Registration Opens:</strong>
                        <span class="text-[var(--app-text-primary)]">{{ \Carbon\Carbon::parse($hackathon->registration_opens_at)->format('D, M j, Y - g:i A') }}</span>
                    </div>
                    <div>
                        <strong class="block text-[var(--app-text-secondary)]">Registration Closes:</strong>
                        <span class="text-[var(--app-text-primary)]">{{ \Carbon\Carbon::parse($hackathon->registration_closes_at)->format('D, M j, Y - g:i A') }}</span>
                    </div>
                </div>

                {{-- Description --}}
                @if($hackathon->description)
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-[var(--app-text-primary)] mb-2">Description</h3>
                    <div class="prose prose-sm sm:prose-base dark:prose-invert max-w-none text-[var(--app-text-secondary)]">
                        {!! nl2br(e($hackathon->description)) !!}
                    </div>
                </div>
                @endif

                {{-- Rules --}}
                @if($hackathon->rules)
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-[var(--app-text-primary)] mb-2">Rules</h3>
                    <div class="prose prose-sm sm:prose-base dark:prose-invert max-w-none text-[var(--app-text-secondary)]">
                        {!! nl2br(e($hackathon->rules)) !!}
                    </div>
                </div>
                @endif

                {{-- Prizes --}}
                @if($hackathon->prizes)
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-[var(--app-text-primary)] mb-2">Prizes</h3>
                    <div class="prose prose-sm sm:prose-base dark:prose-invert max-w-none text-[var(--app-text-secondary)]">
                        {!! nl2br(e($hackathon->prizes)) !!}
                    </div>
                </div>
                @endif

                {{-- Banner Image --}}
                 @if($hackathon->banner_image_url)
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-[var(--app-text-primary)] mb-2">Banner</h3>
                    <img src="{{ $hackathon->banner_image_url }}" alt="{{ $hackathon->name }} Banner" class="rounded-md shadow-md max-w-full h-auto">
                </div>
                @endif

                {{-- Placeholder for more admin-specific details like list of participants, projects, etc. --}}
                {{-- <div class="mt-8 pt-6 border-t border-[var(--app-border-color)]">
                    <h3 class="text-lg font-semibold text-[var(--app-text-primary)] mb-2">Participants / Projects (Admin View)</h3>
                    <p class="text-[var(--app-text-secondary)] text-sm">Details about participants and submitted projects for this hackathon will be shown here.</p>
                </div> --}}

                <div class="mt-8 pt-6 border-t border-[var(--app-border-color)] text-right">
                    <a href="{{ route('admin.hackathons.index') }}" class="text-sm text-primary-accent hover:underline">
                        &larr; Back to All Hackathons
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
