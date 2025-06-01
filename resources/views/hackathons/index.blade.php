@extends('layouts.app')

@section('content')
<div class="py-8 md:py-12 bg-[var(--app-bg-secondary)]">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <header class="mb-8 text-center">
            <h1 class="text-3xl md:text-4xl font-bold text-primary-accent">Upcoming & Ongoing Hackathons</h1>
            <p class="mt-2 text-lg text-[var(--app-text-secondary)]">Join our exciting hackathons and showcase your skills!</p>
        </header>

        @if($hackathons->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
                @foreach ($hackathons as $hackathon)
                    <div class="app-card rounded-xl shadow-lg overflow-hidden flex flex-col transition-all duration-300 hover:shadow-2xl hover:scale-105">
                        @if($hackathon->banner_image_url)
                            <img src="{{ $hackathon->banner_image_url }}" alt="{{ $hackathon->name }} banner" class="w-full h-40 object-cover">
                        @else
                            <div class="w-full h-40 bg-gradient-to-r from-purple-500 to-indigo-600 flex items-center justify-center">
                                <span class="text-white text-2xl font-semibold">{{ Str::limit($hackathon->name, 20) }}</span>
                            </div>
                        @endif
                        <div class="p-5 md:p-6 flex flex-col flex-grow">
                            <h2 class="text-xl font-semibold text-[var(--app-text-primary)] mb-2 group-hover:text-primary-accent">
                                {{ $hackathon->name }}
                            </h2>
                            <p class="text-xs text-[var(--app-text-secondary)] mb-1">
                                <span class="font-medium">Starts:</span> {{ \Carbon\Carbon::parse($hackathon->start_datetime)->format('M d, Y') }}
                                | <span class="font-medium">Ends:</span> {{ \Carbon\Carbon::parse($hackathon->end_datetime)->format('M d, Y') }}
                            </p>
                            <p class="text-xs px-2 py-0.5 inline-flex leading-5 font-semibold rounded-full mb-3
                                @if($hackathon->status === 'upcoming') bg-yellow-100 text-yellow-800 dark:bg-yellow-700 dark:text-yellow-100
                                @elseif($hackathon->status === 'registration_open') bg-blue-100 text-blue-800 dark:bg-blue-700 dark:text-blue-100
                                @elseif($hackathon->status === 'ongoing') bg-green-100 text-green-800 dark:bg-green-700 dark:text-green-100
                                @else bg-gray-100 text-gray-800 dark:bg-gray-600 dark:text-gray-200 @endif">
                                {{ Str::title(str_replace('_', ' ', $hackathon->status)) }}
                            </p>
                            <p class="text-sm text-[var(--app-text-secondary)] flex-grow mb-4">
                                {{ Str::limit(strip_tags($hackathon->description), 120) }}
                            </p>
                            <div class="mt-auto">
                                {{-- We'll make a proper public show route later --}}
                               <a href="{{ route('hackathons.public.show', $hackathon->status === 'upcoming' || $hackathon->status === 'registration_open' || $hackathon->status === 'ongoing' || $hackathon->status === 'judging' || $hackathon->status === 'completed' ? ($hackathon->slug ?? $hackathon->id) : $hackathon->id) }}"
                                       class="inline-block w-full text-center px-4 py-2.5 bg-primary-accent text-white font-semibold rounded-lg hover:opacity-90 transition-opacity duration-300">
                                        View Details {{ $hackathon->status === 'registration_open' ? '& Register' : ''}}
                                    </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination Links --}}
            <div class="mt-10">
                {{ $hackathons->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                </svg>
                <h3 class="mt-2 text-lg font-medium text-[var(--app-text-primary)]">No Hackathons Available</h3>
                <p class="mt-1 text-sm text-[var(--app-text-secondary)]">
                    There are currently no hackathons listed. Please check back soon!
                </p>
                @auth
                    @if(Auth::user()->is_admin)
                    <div class="mt-6">
                        <a href="{{ route('admin.hackathons.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-accent hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-accent">
                            Create New Hackathon
                        </a>
                    </div>
                    @endif
                @endauth
            </div>
        @endif
    </div>
</div>
@endsection
