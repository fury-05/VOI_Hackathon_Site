@extends('layouts.app')

@section('content')
<div class="py-8 md:py-12 bg-[var(--app-bg-secondary)]">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="app-card rounded-lg shadow-xl overflow-hidden">

            {{-- Display Session Feedback Messages --}}
                    @if (session('success'))
                        <div class="mb-4 p-3 bg-green-100 dark:bg-green-700/30 border border-green-400 dark:border-green-600 text-green-700 dark:text-green-100 rounded-md" role="alert">
                            <strong class="font-bold">Success!</strong>
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="mb-4 p-3 bg-red-100 dark:bg-red-700/30 border border-red-400 dark:border-red-600 text-red-700 dark:text-red-100 rounded-md" role="alert">
                            <strong class="font-bold">Error!</strong>
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif
                    @if (session('info'))
                        <div class="mb-4 p-3 bg-blue-100 dark:bg-blue-700/30 border border-blue-400 dark:border-blue-600 text-blue-700 dark:text-blue-100 rounded-md" role="alert">
                            <strong class="font-bold">Info:</strong>
                            <span class="block sm:inline">{{ session('info') }}</span>
                        </div>
                    @endif
                    {{-- End Display Session Feedback Messages --}}

            {{-- Banner Image --}}
            @if($hackathon->banner_image_url)
                <img src="{{ $hackathon->banner_image_url }}" alt="{{ $hackathon->name }} Banner" class="w-full h-48 md:h-64 object-cover">
            @else
                <div class="w-full h-48 md:h-64 bg-gradient-to-r from-purple-600 to-indigo-700 flex items-center justify-center">
                    <h1 class="text-3xl md:text-5xl font-bold text-white text-center px-4">{{ $hackathon->name }}</h1>
                </div>
            @endif

            <div class="p-6 md:p-8">
                @if(!$hackathon->banner_image_url)
                    <h1 class="text-2xl md:text-3xl font-bold text-primary-accent mb-3">{{ $hackathon->name }}</h1>
                @endif

                {{-- Status and Key Dates --}}
                <div class="mb-6 space-y-2 text-sm">
                    <div class="flex items-center">
                        <span class="font-semibold text-[var(--app-text-secondary)] w-32">Status:</span>
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                            @if($hackathon->status === 'upcoming') bg-yellow-100 text-yellow-800 dark:bg-yellow-700 dark:text-yellow-100
                            @elseif($hackathon->status === 'registration_open') bg-blue-100 text-blue-800 dark:bg-blue-700 dark:text-blue-100
                            @elseif($hackathon->status === 'ongoing') bg-green-100 text-green-800 dark:bg-green-700 dark:text-green-100
                            @elseif($hackathon->status === 'judging') bg-purple-100 text-purple-800 dark:bg-purple-700 dark:text-purple-100
                            @elseif($hackathon->status === 'completed') bg-gray-100 text-gray-800 dark:bg-gray-600 dark:text-gray-200
                            @else bg-gray-200 text-gray-700 @endif">
                            {{ Str::title(str_replace('_', ' ', $hackathon->status)) }}
                        </span>
                    </div>
                    <div class="flex items-center">
                        <span class="font-semibold text-[var(--app-text-secondary)] w-32">Event Dates:</span>
                        <span class="text-[var(--app-text-primary)]">{{ \Carbon\Carbon::parse($hackathon->start_datetime)->format('M d, Y') }} - {{ \Carbon\Carbon::parse($hackathon->end_datetime)->format('M d, Y') }}</span>
                    </div>
                    <div class="flex items-center">
                        <span class="font-semibold text-[var(--app-text-secondary)] w-32">Registration:</span>
                        <span class="text-[var(--app-text-primary)]">{{ \Carbon\Carbon::parse($hackathon->registration_opens_at)->format('M d') }} - {{ \Carbon\Carbon::parse($hackathon->registration_closes_at)->format('M d, Y') }}</span>
                    </div>
                </div>

                {{-- Registration Button (More Dynamic Logic) --}}
               {{-- Registration Button - Uses $registrationButton from controller --}}
                    @auth {{-- Only show registration related buttons/info if user is logged in --}}
                        @if(isset($registrationButton) && $registrationButton['text'])
                        <div class="my-6">
                            @if($registrationButton['can_submit_form'] && !$isRegistered) {{-- Show form only if can register and not already registered --}}
                                <form action="{{ route('hackathons.public.register', $hackathon->slug) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit"
                                       class="inline-block w-full sm:w-auto text-center px-8 py-3 {{ $registrationButton['class'] }} font-bold rounded-lg transition-colors duration-300 text-lg">
                                        {{ $registrationButton['text'] }}
                                    </button>
                                </form>
                            @else
                                <button disabled {{-- For "Already Registered", "Opens Soon", "Closed", etc. --}}
                                    class="w-full sm:w-auto text-center px-8 py-3 {{ $registrationButton['class'] }} font-bold rounded-lg text-lg {{ $registrationButton['disabled'] ? 'opacity-75 cursor-not-allowed' : '' }}">
                                    {{ $registrationButton['text'] }}
                                </button>
                            @endif
                        </div>
                        @endif
                    @else {{-- If user is not logged in, prompt to login/register to see registration status --}}
                        <div class="my-6">
                             <p class="text-sm text-[var(--app-text-secondary)] py-3 px-6 text-center bg-[var(--app-bg-primary)] border border-[var(--app-border-color)] rounded-lg">
                                Please <a href="{{ route('login') }}" class="text-primary-accent hover:underline font-semibold">log in</a> or
                                <a href="{{ route('register') }}" class="text-primary-accent hover:underline font-semibold">register</a> to see registration options.
                            </p>
                        </div>
                    @endauth
                    {{-- End of Registration Button Logic --}}
                    {{-- End of Registration Button Logic --}}


                {{-- Description --}}
                @if($hackathon->description)
                <div class="mb-6">
                    <h3 class="text-xl font-semibold text-[var(--app-text-primary)] mb-2 border-b border-[var(--app-border-color)] pb-1">About this Hackathon</h3>
                    <div class="prose prose-sm sm:prose-base dark:prose-invert max-w-none text-[var(--app-text-secondary)]">
                        {!! nl2br(e($hackathon->description)) !!}
                    </div>
                </div>
                @endif

                {{-- Rules --}}
                @if($hackathon->rules)
                <div class="mb-6">
                    <h3 class="text-xl font-semibold text-[var(--app-text-primary)] mb-2 border-b border-[var(--app-border-color)] pb-1">Rules & Guidelines</h3>
                    <div class="prose prose-sm sm:prose-base dark:prose-invert max-w-none text-[var(--app-text-secondary)]">
                        {!! nl2br(e($hackathon->rules)) !!}
                    </div>
                </div>
                @endif

                {{-- Prizes --}}
                @if($hackathon->prizes)
                <div class="mb-6">
                    <h3 class="text-xl font-semibold text-[var(--app-text-primary)] mb-2 border-b border-[var(--app-border-color)] pb-1">Prizes</h3>
                    <div class="prose prose-sm sm:prose-base dark:prose-invert max-w-none text-[var(--app-text-secondary)]">
                        {!! nl2br(e($hackathon->prizes)) !!}
                    </div>
                </div>
                @endif

                {{-- Placeholder for projects submitted, participants, judges, etc. --}}

                {{-- NEW: Project Submission Section --}}
                    @auth
                        @if($isRegistered && $canSubmitProject)
                            @php
                                // Check if this user already submitted a project for THIS hackathon through their registration
                                $currentUserRegistration = Auth::user()->hackathonRegistrations()->where('hackathon_id', $hackathon->id)->first();
                                $hasSubmittedProjectForThisHackathon = $currentUserRegistration && $currentUserRegistration->project_id;
                            @endphp

                            @if($hasSubmittedProjectForThisHackathon)
                                <div class="mt-8 pt-6 border-t border-[var(--app-border-color)] app-card p-4 rounded-lg bg-green-500/10">
                                    <h3 class="text-lg font-semibold text-green-700 dark:text-green-300 mb-2">Project Submitted!</h3>
                                    <p class="text-sm text-[var(--app-text-secondary)]">
                                        You have submitted project:
                                        <a href="{{ route('projects.show-public', $currentUserRegistration->project_id) }}" class="font-semibold text-primary-accent hover:underline">
                                            {{ \App\Models\Project::find($currentUserRegistration->project_id)->name ?? 'View Project' }}
                                        </a>
                                    </p>
                                    {{-- Option to change submission could go here --}}
                                </div>
                            @elseif($userProjects->count() > 0)
                                <div class="mt-8 pt-6 border-t border-[var(--app-border-color)]">
                                    <h3 class="text-xl font-semibold text-[var(--app-text-primary)] mb-4">Submit Your Project</h3>
                                    <form action="{{ route('hackathons.public.submit_project', $hackathon->slug) }}" method="POST"> {{-- Assuming slug routing for hackathon --}}
                                        @csrf
                                        <div class="mb-4">
                                            <label for="project_id" class="block text-sm font-medium text-[var(--app-text-primary)] mb-1">Choose an existing project:</label>
                                            <select name="project_id" id="project_id" required
                                                    class="w-full p-2 border rounded-md shadow-sm focus:ring-primary-accent focus:border-primary-accent bg-[var(--app-bg-primary)] text-[var(--app-text-primary)] border-[var(--app-border-color)]">
                                                <option value="">-- Select your project --</option>
                                                @foreach ($userProjects as $project)
                                                    <option value="{{ $project->id }}">{{ $project->name }} ({{ $project->github_repo_url ? 'Has GitHub Repo' : 'No GitHub Repo yet' }})</option>
                                                @endforeach
                                            </select>
                                            @error('project_id')
                                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <p class="text-xs text-[var(--app-text-secondary)] mb-3">Ensure your selected project has its GitHub repository URL correctly set on its project page.</p>
                                        <div class="flex items-center gap-4">
                                            <button type="submit"
                                                    class="inline-flex items-center px-6 py-2.5 bg-primary-accent border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-accent transition ease-in-out duration-150">
                                                Submit Selected Project
                                            </button>
                                            {{-- Link to create a new project could go here --}}
                                            {{-- <a href="#" class="text-sm text-primary-accent hover:underline">Or create a new project for this hackathon</a> --}}
                                        </div>
                                    </form>
                                </div>
                            @else
                                <div class="mt-8 pt-6 border-t border-[var(--app-border-color)]">
                                    <h3 class="text-xl font-semibold text-[var(--app-text-primary)] mb-2">Submit Your Project</h3>
                                    <p class="text-sm text-[var(--app-text-secondary)]">You don't seem to have any eligible projects to submit. Please create a project first (ensure it has a GitHub repo URL).</p>
                                    {{-- Link to project creation page --}}
                                    {{-- <a href="#" class="mt-2 inline-block text-sm text-primary-accent hover:underline">Create a New Project</a> --}}
                                </div>
                            @endif
                        @elseif($isRegistered && !$canSubmitProject)
                             <div class="mt-8 pt-6 border-t border-[var(--app-border-color)]">
                                <p class="text-sm text-[var(--app-text-secondary)] app-card p-4 rounded-lg bg-yellow-500/10">Project submission window is not currently open for this hackathon.</p>
                            </div>
                        @endif
                    @endauth
                    {{-- End of Project Submission Section --}}


                <div class="mt-8 pt-6 border-t border-[var(--app-border-color)]">
                    <a href="{{ route('hackathons.public.index') }}" class="text-sm text-primary-accent hover:underline">
                        &larr; Back to All Hackathons
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
