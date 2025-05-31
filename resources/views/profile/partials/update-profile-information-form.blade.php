<section>
    <header>
        <h2 class="text-lg font-medium text-[var(--app-text-primary)]">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-[var(--app-text-secondary)]">
            {{ __("Update your account's profile information, GitHub details, and skills.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        {{-- Name --}}
        <div>
            <x-input-label for="name" :value="__('Name')" class="text-[var(--app-text-primary)] font-medium" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full bg-transparent text-[var(--app-text-primary)] border-0 border-b-2 border-gray-300 dark:border-gray-600 focus:border-primary-accent focus:ring-0 py-2 px-1 placeholder:text-[var(--app-text-secondary)] placeholder:opacity-75" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        {{-- Email --}}
        <div>
            <x-input-label for="email" :value="__('Email')" class="text-[var(--app-text-primary)] font-medium" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full bg-transparent text-[var(--app-text-primary)] border-0 border-b-2 border-gray-300 dark:border-gray-600 focus:border-primary-accent focus:ring-0 py-2 px-1 placeholder:text-[var(--app-text-secondary)] placeholder:opacity-75" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-[var(--app-text-primary)]">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-[var(--app-text-secondary)] hover:text-[var(--app-text-primary)] rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-accent">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        {{-- GitHub Username --}}
        <div class="mt-4">
            <x-input-label for="github_username" :value="__('GitHub Username (optional)')" class="text-[var(--app-text-primary)] font-medium" />
            <x-text-input id="github_username" name="github_username" type="text" class="mt-1 block w-full bg-transparent text-[var(--app-text-primary)] border-0 border-b-2 border-gray-300 dark:border-gray-600 focus:border-primary-accent focus:ring-0 py-2 px-1 placeholder:text-[var(--app-text-secondary)] placeholder:opacity-75" :value="old('github_username', $user->github_username)" autocomplete="github_username" />
            <x-input-error class="mt-2" :messages="$errors->get('github_username')" />
            <p class="mt-1 text-xs text-[var(--app-text-secondary)]">Your public GitHub username (e.g., "octocat").</p>
        </div>

        {{-- Bio --}}
        <div class="mt-4">
            <x-input-label for="bio" :value="__('Bio (optional)')" class="text-[var(--app-text-primary)] font-medium" />
            <textarea id="bio" name="bio" rows="4" class="mt-1 block w-full rounded-md shadow-sm bg-transparent text-[var(--app-text-primary)] border-0 border-b-2 border-gray-300 dark:border-gray-600 focus:border-primary-accent focus:ring-0 py-2 px-1 placeholder:text-[var(--app-text-secondary)] placeholder:opacity-75">{{ old('bio', $user->bio) }}</textarea>
            <x-input-error class="mt-2" :messages="$errors->get('bio')" />
            <p class="mt-1 text-xs text-[var(--app-text-secondary)]">A short description about yourself.</p>
        </div>

        {{-- Skills --}}
        <div class="mt-4">
            <x-input-label for="skills" :value="__('Skills (optional)')" class="text-[var(--app-text-primary)] font-medium" />
            <x-text-input id="skills" name="skills" type="text" class="mt-1 block w-full bg-transparent text-[var(--app-text-primary)] border-0 border-b-2 border-gray-300 dark:border-gray-600 focus:border-primary-accent focus:ring-0 py-2 px-1 placeholder:text-[var(--app-text-secondary)] placeholder:opacity-75" :value="old('skills', is_array($user->skills) ? implode(', ', $user->skills) : $user->skills)" placeholder="e.g., PHP, Laravel, JavaScript" />
            <x-input-error class="mt-2" :messages="$errors->get('skills')" />
            <p class="mt-1 text-xs text-[var(--app-text-secondary)]">Enter your skills, separated by commas.</p>
        </div>


        <div class="flex items-center gap-4">
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-primary-accent border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-accent disabled:opacity-25 transition ease-in-out duration-150">
                {{ __('Save') }}
            </button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 3000)"
                    class="text-sm font-medium text-green-700 dark:text-green-300 bg-green-100 dark:bg-green-500/20 px-3 py-1.5 rounded-md"
                >{{ __('Profile updated successfully!') }}</p>
            @endif
        </div>
    </form>
</section>
