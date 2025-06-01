@extends('layouts.app')

@section('content')
<div class="py-8 md:py-12">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="app-card rounded-lg shadow-xl overflow-hidden">
            <div class="p-6 md:p-8">
                <h1 class="text-2xl font-bold text-primary-accent mb-6">Create New Project</h1>

                {{-- Display any validation errors --}}
                @if ($errors->any())
                    <div class="mb-4 p-3 bg-red-100 dark:bg-red-700/30 border border-red-400 dark:border-red-600 text-red-700 dark:text-red-200 rounded-md">
                        <strong class="font-bold">Oops! Something went wrong.</strong>
                        <ul class="mt-1 list-disc list-inside text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('projects.store') }}" method="POST">
                    @csrf

                    {{-- Project Name --}}
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-[var(--app-text-primary)] mb-1">Project Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                               class="w-full p-2 border rounded-md shadow-sm focus:ring-primary-accent focus:border-primary-accent bg-[var(--app-bg-primary)] text-[var(--app-text-primary)] border-[var(--app-border-color)]">
                    </div>

                    {{-- Description --}}
                    <div class="mb-4">
                        <label for="description" class="block text-sm font-medium text-[var(--app-text-primary)] mb-1">Description</label>
                        <textarea name="description" id="description" rows="5" required
                                  class="w-full p-2 border rounded-md shadow-sm focus:ring-primary-accent focus:border-primary-accent bg-[var(--app-bg-primary)] text-[var(--app-text-primary)] border-[var(--app-border-color)]">{{ old('description') }}</textarea>
                    </div>

                    {{-- GitHub Repository URL --}}
                    <div class="mb-4">
                        <label for="github_repo_url" class="block text-sm font-medium text-[var(--app-text-primary)] mb-1">GitHub Repository URL (Optional but Recommended)</label>
                        <input type="url" name="github_repo_url" id="github_repo_url" value="{{ old('github_repo_url') }}"
                               placeholder="https://github.com/username/repository-name"
                               class="w-full p-2 border rounded-md shadow-sm focus:ring-primary-accent focus:border-primary-accent bg-[var(--app-bg-primary)] text-[var(--app-text-primary)] border-[var(--app-border-color)]">
                        <p class="mt-1 text-xs text-[var(--app-text-secondary)]">The full URL to your project's GitHub repository.</p>
                    </div>

                    {{-- Live Demo URL --}}
                    <div class="mb-4">
                        <label for="live_url" class="block text-sm font-medium text-[var(--app-text-primary)] mb-1">Live Demo URL (Optional)</label>
                        <input type="url" name="live_url" id="live_url" value="{{ old('live_url') }}"
                               placeholder="https://yourproject.example.com"
                               class="w-full p-2 border rounded-md shadow-sm focus:ring-primary-accent focus:border-primary-accent bg-[var(--app-bg-primary)] text-[var(--app-text-primary)] border-[var(--app-border-color)]">
                    </div>

                    {{-- Tags --}}
                    <div class="mb-6">
                        <label for="tags" class="block text-sm font-medium text-[var(--app-text-primary)] mb-1">Technologies / Tags (Comma-separated)</label>
                        <input type="text" name="tags" id="tags" value="{{ old('tags') }}"
                               placeholder="e.g., Laravel, Vue.js, TailwindCSS, AI"
                               class="w-full p-2 border rounded-md shadow-sm focus:ring-primary-accent focus:border-primary-accent bg-[var(--app-bg-primary)] text-[var(--app-text-primary)] border-[var(--app-border-color)]">
                        <p class="mt-1 text-xs text-[var(--app-text-secondary)]">List the key technologies or tags associated with your project.</p>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                                class="inline-flex items-center px-6 py-2.5 bg-primary-accent border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-accent disabled:opacity-25 transition ease-in-out duration-150">
                            Create Project
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
