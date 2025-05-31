<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light"> <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}"> <title>{{ config('app.name', 'DevLink Hub') }} - Hackathons & Developer Showcase</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: ['selector', '[data-theme="dark"]'], // Enable dark mode based on data-theme attribute
            theme: {
                extend: {
                    colors: {
                        'primary-light': '#6D28D9', // Purple for light theme accent (violet-700)
                        'primary-dark': '#8B5CF6',  // Lighter Purple for dark theme accent (violet-500)
                        'accent-light': '#EDE9FE', // Light purple background (violet-100)
                        'accent-dark': '#3730A3',  // Darker purple background (indigo-800)
                        // Backgrounds
                        'bg-light-primary': '#FFFFFF',
                        'bg-light-secondary': '#F3F4F6', // gray-100
                        'bg-dark-primary': '#111827',   // gray-900
                        'bg-dark-secondary': '#1F2937', // gray-800
                        // Text
                        'text-light-primary': '#1F2937', // gray-800
                        'text-light-secondary': '#6B7280',// gray-500
                        'text-dark-primary': '#F3F4F6',   // gray-100
                        'text-dark-secondary': '#9CA3AF', // gray-400
                        // Cards
                        'card-light-bg': '#FFFFFF',
                        'card-dark-bg': '#1F2937', // gray-800
                        'border-light': '#E5E7EB', // gray-200
                        'border-dark': '#374151',  // gray-700
                    }
                }
            }
        }
    </script>

   <script src="https://cdn.jsdelivr.net/npm/echarts@5.5.0/dist/echarts.min.js"></script> {{-- <-- ADD THIS LINE --}}

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--app-bg-primary); /* Uses CSS variable */
            color: var(--app-text-primary);      /* Uses CSS variable */
            transition: background-color 0.3s, color 0.3s;
        }

        /* Light Theme Defaults */
        :root, html[data-theme="light"] {
            --app-bg-primary: #FFFFFF;              /* Tailwind: bg-white */
            --app-bg-secondary: #F3F4F6;            /* Tailwind: gray-100 */
            --app-text-primary: #1F2937;            /* Tailwind: gray-800 */
            --app-text-secondary: #6B7280;          /* Tailwind: gray-500 */
            --app-card-bg: #FFFFFF;                 /* Tailwind: bg-white */
            --app-border-color: #E5E7EB;            /* Tailwind: gray-200 */
            --app-primary-accent: #6D28D9;          /* Tailwind: violet-700 */
            --app-secondary-accent-bg: #EDE9FE;     /* Tailwind: violet-100 */
        }

        /* Dark Theme Variables */
        html[data-theme="dark"] {
            --app-bg-primary: #111827;              /* Tailwind: gray-900 */
            --app-bg-secondary: #1F2937;            /* Tailwind: gray-800 */
            --app-text-primary: #F3F4F6;            /* Tailwind: gray-100 */
            --app-text-secondary: #9CA3AF;          /* Tailwind: gray-400 */
            --app-card-bg: #1F2937;                 /* Tailwind: gray-800 (card bg slightly different from main dark bg) */
            --app-border-color: #374151;            /* Tailwind: gray-700 */
            --app-primary-accent: #8B5CF6;          /* Tailwind: violet-500 */
            --app-secondary-accent-bg: #3730A3;     /* Tailwind: indigo-800 (or a dark violet) */
        }

        /* Component styles using the variables */
        .app-card {
            background-color: var(--app-card-bg);
            border: 1px solid var(--app-border-color);
            /* color: var(--app-text-primary); Ensure text inside card uses theme color - this might be inherited or set on specific text elements */
        }
        .text-primary-accent { color: var(--app-primary-accent); }
        .bg-primary-accent { background-color: var(--app-primary-accent); }
        .border-primary-accent { border-color: var(--app-primary-accent); }
        .bg-secondary-accent { background-color: var(--app-secondary-accent-bg); }
    </style>

    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}} {{-- Enable this if you set up Vite --}}
</head>
<body class="antialiased">
    <div class="min-h-screen bg-[var(--app-bg-secondary)]">
        <nav class="bg-[var(--app-bg-primary)] shadow-md border-b border-[var(--app-border-color)]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <div class="flex items-center">
                        <a href="{{ route('home') }}" class="flex-shrink-0 text-2xl font-bold text-primary-accent">
                            DevLink Hub
                        </a>
                    </div>
                    <div class="hidden md:block">
                        <div class="ml-10 flex items-baseline space-x-4">
                            <a href="#" class="text-[var(--app-text-secondary)] hover:text-[var(--app-text-primary)] px-3 py-2 rounded-md text-sm font-medium">Hackathons</a>
                            <a href="#" class="text-[var(--app-text-secondary)] hover:text-[var(--app-text-primary)] px-3 py-2 rounded-md text-sm font-medium">Projects</a>
                            <a href="#" class="text-[var(--app-text-secondary)] hover:text-[var(--app-text-primary)] px-3 py-2 rounded-md text-sm font-medium">Developers</a>
                        </div>
                    </div>
                    <div class="flex items-center">
                         <div class="mr-4">
                            <input type="search" placeholder="Search..." class="px-3 py-1.5 rounded-md border border-[var(--app-border-color)] bg-[var(--app-bg-primary)] text-sm text-[var(--app-text-primary)] focus:ring-[var(--app-primary-accent)] focus:border-[var(--app-primary-accent)] hidden sm:block" style="width: 200px;">
                        </div>
                        <button id="theme-toggle-button" class="p-2 rounded-md text-[var(--app-text-secondary)] hover:text-[var(--app-text-primary)] focus:outline-none mr-2">
                            <svg id="theme-icon-light" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 hidden"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-6.364-.386l1.591-1.591M3 12h2.25m.386-6.364l1.591 1.591" /></svg>
                            <svg id="theme-icon-dark" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 hidden"><path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z" /></svg>
                        </button>
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ url('/dashboard') }}" class="text-sm text-[var(--app-text-secondary)] hover:text-[var(--app-text-primary)] underline">Dashboard</a>
                            @else
                                <a href="{{ route('login') }}" class="text-sm font-medium text-[var(--app-text-primary)] bg-primary-accent hover:opacity-90 px-4 py-2 rounded-md mr-2">Log in</a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="text-sm font-medium text-primary-accent hover:text-[var(--app-primary-accent)] border border-primary-accent px-4 py-2 rounded-md hidden sm:block">Register</a>
                                @endif
                            @endauth
                        @endif
                    </div>
                </div>
            </div>
        </nav>

        <main>
            {{ $slot ?? '' }} @yield('content')    </main>

        <footer class="py-8 text-center text-sm text-[var(--app-text-secondary)] border-t border-[var(--app-border-color)] bg-[var(--app-bg-primary)] mt-12">
            <p>&copy; {{ date('Y') }} DevLink Hub. All rights reserved.</p>
            <p class="mt-1">IMAV Learning Academy Pvt Ltd</p>
        </footer>
    </div>

    <script>
        // Theme switcher logic
        const themeToggleButton = document.getElementById('theme-toggle-button');
        const lightIcon = document.getElementById('theme-icon-light');
        const darkIcon = document.getElementById('theme-icon-dark');

        // Function to apply theme
        function applyTheme(theme) {
            if (theme === 'dark') {
                document.documentElement.setAttribute('data-theme', 'dark');
                lightIcon.classList.add('hidden');
                darkIcon.classList.remove('hidden');
            } else {
                document.documentElement.setAttribute('data-theme', 'light');
                darkIcon.classList.add('hidden');
                lightIcon.classList.remove('hidden');
            }
        }

        // Check local storage for saved theme
        const savedTheme = localStorage.getItem('theme');
        if (savedTheme) {
            applyTheme(savedTheme);
        } else {
            // Apply system preference or default to light
            if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                // applyTheme('dark'); // Uncomment to default to system dark mode
                applyTheme('light'); // Default to light if no preference or system preference not used
            } else {
                applyTheme('light');
            }
        }
        // Update icon visibility based on initial theme
         if (document.documentElement.getAttribute('data-theme') === 'dark') {
            lightIcon.classList.add('hidden');
            darkIcon.classList.remove('hidden');
        } else {
            darkIcon.classList.add('hidden');
            lightIcon.classList.remove('hidden');
        }


        themeToggleButton.addEventListener('click', () => {
            const currentTheme = document.documentElement.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            localStorage.setItem('theme', newTheme);
            applyTheme(newTheme);
        });

        // Confetti for a specific element (example)
        // To be used later, e.g. on the highlight card
        // const confettiButton = document.getElementById('confetti-trigger');
        // if(confettiButton) {
        //     confettiButton.addEventListener('click', () => {
        //         confetti({
        //             particleCount: 100,
        //             spread: 70,
        //             origin: { y: 0.6 }
        //         });
        //     });
        // }
    </script>
    {{-- Canvas Confetti for party poppers (already in <head> from previous plan, but if not) --}}
    {{-- <script async src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.2/dist/confetti.browser.min.js"></script> --}}


   <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


    @stack('scripts') {{-- For page-specific scripts --}}





</body>
</html>
