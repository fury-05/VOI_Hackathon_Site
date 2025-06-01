@extends('layouts.app') {{-- Assuming you want to use your main app layout --}}

@section('content')
<div class="py-8 md:py-12">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="app-card rounded-lg shadow-xl overflow-hidden">
            <div class="p-6 md:p-8">
                <h1 class="text-2xl font-bold text-primary-accent mb-6">Create New Hackathon</h1>

                {{-- Display Session Success Message --}}
                    @if (session('success'))
                        <div class="mb-4 p-3 bg-green-100 dark:bg-green-700/30 border border-green-400 dark:border-green-600 text-green-700 dark:text-green-200 rounded-md app-card" role="alert">
                            <strong class="font-bold">Success!</strong>
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

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

                <form action="{{ route('admin.hackathons.store') }}" method="POST">
                    @csrf

                    {{-- Hackathon Name --}}
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-[var(--app-text-primary)] mb-1">Hackathon Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                               class="w-full p-2 border rounded-md shadow-sm focus:ring-primary-accent focus:border-primary-accent bg-[var(--app-bg-primary)] text-[var(--app-text-primary)] border-[var(--app-border-color)]">
                    </div>

                    {{-- Slug (Optional - can be auto-generated from name) --}}
                    {{-- For simplicity, we'll let the controller handle slug generation for now --}}
                    {{-- <div class="mb-4">
                        <label for="slug" class="block text-sm font-medium text-[var(--app-text-primary)] mb-1">URL Slug (e.g., my-awesome-hackathon)</label>
                        <input type="text" name="slug" id="slug" value="{{ old('slug') }}"
                               class="w-full p-2 border rounded-md shadow-sm focus:ring-primary-accent focus:border-primary-accent bg-[var(--app-bg-primary)] text-[var(--app-text-primary)] border-[var(--app-border-color)]">
                    </div> --}}

                    {{-- Description --}}
                    <div class="mb-4">
                        <label for="description" class="block text-sm font-medium text-[var(--app-text-primary)] mb-1">Description</label>
                        <textarea name="description" id="description" rows="5" required
                                  class="w-full p-2 border rounded-md shadow-sm focus:ring-primary-accent focus:border-primary-accent bg-[var(--app-bg-primary)] text-[var(--app-text-primary)] border-[var(--app-border-color)]">{{ old('description') }}</textarea>
                    </div>

                    {{-- Rules --}}
                    <div class="mb-4">
                        <label for="rules" class="block text-sm font-medium text-[var(--app-text-primary)] mb-1">Rules (Optional)</label>
                        <textarea name="rules" id="rules" rows="5"
                                  class="w-full p-2 border rounded-md shadow-sm focus:ring-primary-accent focus:border-primary-accent bg-[var(--app-bg-primary)] text-[var(--app-text-primary)] border-[var(--app-border-color)]">{{ old('rules') }}</textarea>
                    </div>

                    {{-- Prizes --}}
                    <div class="mb-4">
                        <label for="prizes" class="block text-sm font-medium text-[var(--app-text-primary)] mb-1">Prizes (Optional)</label>
                        <textarea name="prizes" id="prizes" rows="3"
                                  class="w-full p-2 border rounded-md shadow-sm focus:ring-primary-accent focus:border-primary-accent bg-[var(--app-bg-primary)] text-[var(--app-text-primary)] border-[var(--app-border-color)]">{{ old('prizes') }}</textarea>
                    </div>

                    {{-- Dates Grid --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="start_datetime" class="block text-sm font-medium text-[var(--app-text-primary)] mb-1">Start Date & Time</label>
                            <input type="datetime-local" name="start_datetime" id="start_datetime" value="{{ old('start_datetime') }}" required
                                   class="w-full p-2 border rounded-md shadow-sm focus:ring-primary-accent focus:border-primary-accent bg-[var(--app-bg-primary)] text-[var(--app-text-primary)] border-[var(--app-border-color)]">
                        </div>
                        <div>
                            <label for="end_datetime" class="block text-sm font-medium text-[var(--app-text-primary)] mb-1">End Date & Time</label>
                            <input type="datetime-local" name="end_datetime" id="end_datetime" value="{{ old('end_datetime') }}" required
                                   class="w-full p-2 border rounded-md shadow-sm focus:ring-primary-accent focus:border-primary-accent bg-[var(--app-bg-primary)] text-[var(--app-text-primary)] border-[var(--app-border-color)]">
                        </div>
                        <div>
                            <label for="registration_opens_at" class="block text-sm font-medium text-[var(--app-text-primary)] mb-1">Registration Opens</label>
                            <input type="datetime-local" name="registration_opens_at" id="registration_opens_at" value="{{ old('registration_opens_at') }}" required
                                   class="w-full p-2 border rounded-md shadow-sm focus:ring-primary-accent focus:border-primary-accent bg-[var(--app-bg-primary)] text-[var(--app-text-primary)] border-[var(--app-border-color)]">
                        </div>
                        <div>
                            <label for="registration_closes_at" class="block text-sm font-medium text-[var(--app-text-primary)] mb-1">Registration Closes</label>
                            <input type="datetime-local" name="registration_closes_at" id="registration_closes_at" value="{{ old('registration_closes_at') }}" required
                                   class="w-full p-2 border rounded-md shadow-sm focus:ring-primary-accent focus:border-primary-accent bg-[var(--app-bg-primary)] text-[var(--app-text-primary)] border-[var(--app-border-color)]">
                        </div>
                    </div>

                    {{-- Banner Image URL (Optional) --}}
                    <div class="mb-6">
                        <label for="banner_image_url" class="block text-sm font-medium text-[var(--app-text-primary)] mb-1">Banner Image URL (Optional)</label>
                        <input type="url" name="banner_image_url" id="banner_image_url" value="{{ old('banner_image_url') }}"
                               placeholder="https://example.com/image.png"
                               class="w-full p-2 border rounded-md shadow-sm focus:ring-primary-accent focus:border-primary-accent bg-[var(--app-bg-primary)] text-[var(--app-text-primary)] border-[var(--app-border-color)]">
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                                class="inline-flex items-center px-6 py-2.5 bg-primary-accent border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-accent disabled:opacity-25 transition ease-in-out duration-150">
                            Create Hackathon
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
