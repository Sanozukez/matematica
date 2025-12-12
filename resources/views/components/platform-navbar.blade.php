{{-- Platform Navbar (Filament-style, used across platform) --}}
<nav class="platform-navbar flex h-16 items-center gap-x-4 bg-white px-4 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 md:px-6 lg:px-8">
    {{-- LEFT: Brand --}}
    <div class="flex items-center gap-x-2">
        <a href="{{ route('filament.admin.pages.dashboard') }}" class="flex items-center gap-x-2 font-semibold text-gray-950 dark:text-white hover:opacity-75 transition-opacity">
            {{-- Book Open Icon --}}
            <svg class="h-6 w-6 flex-shrink-0 text-yellow-600 dark:text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.75c-2.25-1.5-4.5-1.5-6.75 0v10.5c2.25-1.5 4.5-1.5 6.75 0m0-10.5c2.25-1.5 4.5-1.5 6.75 0v10.5c-2.25-1.5-4.5-1.5-6.75 0m0-10.5v10.5" />
            </svg>
            
            {{-- Brand text (always visible) --}}
            <span class="text-sm font-medium">
                Matemática
            </span>
        </a>
    </div>

    {{-- RIGHT: Actions & User Menu --}}
    <div class="ms-auto flex items-center gap-x-4">
        {{-- User Menu Dropdown --}}
        @auth
            <div class="relative group">
                {{-- Avatar Button --}}
                <button 
                    type="button"
                    aria-label="User menu"
                    class="flex h-8 w-8 items-center justify-center rounded-lg transition duration-75 hover:bg-gray-100 focus:outline-none focus-visible:ring-2 focus-visible:ring-yellow-600 dark:hover:bg-gray-800 dark:focus-visible:ring-yellow-500"
                >
                    {{-- User Avatar --}}
                    <img 
                        src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&color=FFFFFF&background=eab308"
                        alt="Avatar"
                        class="h-8 w-8 rounded-full object-cover"
                    />
                </button>

                {{-- Dropdown Menu (visible on hover) --}}
                <div 
                    class="absolute right-0 z-50 mt-2 w-56 rounded-lg bg-white shadow-lg ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 pointer-events-none group-hover:pointer-events-auto"
                >
                    {{-- User Info Section --}}
                    <div class="border-b border-gray-100 px-4 py-3 dark:border-gray-800">
                        <div class="flex items-center gap-3">
                            <img 
                                src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&color=FFFFFF&background=eab308"
                                alt="Avatar"
                                class="h-10 w-10 rounded-full object-cover"
                            />
                            <div class="flex-1 truncate">
                                <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                    {{ auth()->user()->name }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                                    {{ auth()->user()->email }}
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Theme Switcher --}}
                    <div class="border-b border-gray-100 px-4 py-3 dark:border-gray-800">
                        <div class="flex items-center justify-center gap-1">
                            {{-- Light Theme Button --}}
                            <button
                                type="button"
                                data-theme="light"
                                class="theme-btn flex items-center justify-center rounded-md p-2 transition-colors duration-75 bg-gray-50 text-gray-400 hover:text-gray-500 dark:bg-gray-800 dark:text-gray-500 dark:hover:text-gray-400"
                                title="Light theme"
                            >
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path d="M10 2a.75.75 0 0 1 .75.75v1.5a.75.75 0 0 1-1.5 0v-1.5A.75.75 0 0 1 10 2ZM10 15a.75.75 0 0 1 .75.75v1.5a.75.75 0 0 1-1.5 0v-1.5A.75.75 0 0 1 10 15ZM10 7a3 3 0 1 0 0 6 3 3 0 0 0 0-6ZM15.657 5.404a.75.75 0 1 0-1.06-1.06l-1.061 1.06a.75.75 0 0 0 1.06 1.06l1.06-1.06ZM6.464 14.596a.75.75 0 1 0-1.06-1.06l-1.06 1.06a.75.75 0 0 0 1.06 1.06l1.06-1.06ZM18 10a.75.75 0 0 1-.75.75h-1.5a.75.75 0 0 1 0-1.5h1.5A.75.75 0 0 1 18 10ZM5 10a.75.75 0 0 1-.75.75h-1.5a.75.75 0 0 1 0-1.5h1.5A.75.75 0 0 1 5 10ZM14.596 15.657a.75.75 0 0 0 1.06-1.06l-1.06-1.061a.75.75 0 1 0-1.06 1.06l1.06 1.06ZM5.404 6.464a.75.75 0 0 0 1.06-1.06l-1.06-1.06a.75.75 0 1 0-1.061 1.06l1.06 1.06Z"></path>
                                </svg>
                            </button>

                            {{-- Dark Theme Button --}}
                            <button
                                type="button"
                                data-theme="dark"
                                class="theme-btn flex items-center justify-center rounded-md p-2 transition-colors duration-75 bg-gray-50 text-gray-400 hover:text-gray-500 dark:bg-gray-800 dark:text-gray-500 dark:hover:text-gray-400"
                                title="Dark theme"
                            >
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M7.455 2.004a.75.75 0 0 1 .26.77 7 7 0 0 0 9.958 7.967.75.75 0 0 1 1.067.853A8.5 8.5 0 1 1 6.647 1.921a.75.75 0 0 1 .808.083Z" clip-rule="evenodd"></path>
                                </svg>
                            </button>

                            {{-- System Theme Button --}}
                            <button
                                type="button"
                                data-theme="system"
                                class="theme-btn flex items-center justify-center rounded-md p-2 transition-colors duration-75 bg-gray-50 text-gray-400 hover:text-gray-500 dark:bg-gray-800 dark:text-gray-500 dark:hover:text-gray-400"
                                title="System theme"
                            >
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M2 4.25A2.25 2.25 0 0 1 4.25 2h11.5A2.25 2.25 0 0 1 18 4.25v8.5A2.25 2.25 0 0 1 15.75 15h-3.105a3.501 3.501 0 0 0 1.1 1.677A.75.75 0 0 1 13.26 18H6.74a.75.75 0 0 1-.484-1.323A3.501 3.501 0 0 0 7.355 15H4.25A2.25 2.25 0 0 1 2 12.75v-8.5Zm1.5 0a.75.75 0 0 1 .75-.75h11.5a.75.75 0 0 1 .75.75v7.5a.75.75 0 0 1-.75.75H4.25a.75.75 0 0 1-.75-.75v-7.5Z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Logout Button --}}
                    <div class="px-2 py-2">
                        <form action="{{ route('logout') }}" method="POST" class="w-full">
                            @csrf
                            <button
                                type="submit"
                                class="flex w-full items-center gap-2 rounded-md px-3 py-2 text-sm text-gray-700 transition-colors duration-75 hover:bg-gray-50 dark:text-gray-200 dark:hover:bg-white/5"
                            >
                                <svg class="h-5 w-5 text-gray-400 dark:text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M3 4.25A2.25 2.25 0 0 1 5.25 2h5.5A2.25 2.25 0 0 1 13 4.25v2a.75.75 0 0 1-1.5 0v-2a.75.75 0 0 0-.75-.75h-5.5a.75.75 0 0 0-.75.75v11.5c0 .414.336.75.75.75h5.5a.75.75 0 0 0 .75-.75v-2a.75.75 0 0 1 1.5 0v2A2.25 2.25 0 0 1 10.75 18h-5.5A2.25 2.25 0 0 1 3 15.75V4.25Z" clip-rule="evenodd"></path>
                                    <path fill-rule="evenodd" d="M19 10a.75.75 0 0 0-.75-.75H8.704l1.048-.943a.75.75 0 1 0-1.004-1.114l-2.5 2.25a.75.75 0 0 0 0 1.114l2.5 2.25a.75.75 0 1 0 1.004-1.114l-1.048-.943h9.546A.75.75 0 0 0 19 10Z" clip-rule="evenodd"></path>
                                </svg>
                                <span>{{ __('Sair') }}</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endauth
    </div>
</nav>

<script>
    // Theme button functionality
    document.querySelectorAll('.theme-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            const theme = e.currentTarget.dataset.theme;
            localStorage.setItem('theme', theme);
            
            const html = document.documentElement;
            if (theme === 'dark') {
                html.classList.add('dark');
            } else if (theme === 'light') {
                html.classList.remove('dark');
            } else {
                // system
                if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
                    html.classList.add('dark');
                } else {
                    html.classList.remove('dark');
                }
            }
            
            // Update button styles
            updateThemeButtons(theme);
        });
    });

    function updateThemeButtons(currentTheme) {
        document.querySelectorAll('.theme-btn').forEach(btn => {
            const btnTheme = btn.dataset.theme;
            if (btnTheme === currentTheme) {
                btn.classList.remove('bg-gray-50', 'text-gray-400', 'hover:text-gray-500', 'dark:bg-gray-800', 'dark:text-gray-500', 'dark:hover:text-gray-400');
                btn.classList.add('bg-gray-100', 'text-yellow-600', 'dark:bg-gray-800', 'dark:text-yellow-400');
            } else {
                btn.classList.remove('bg-gray-100', 'text-yellow-600', 'dark:bg-gray-800', 'dark:text-yellow-400');
                btn.classList.add('bg-gray-50', 'text-gray-400', 'hover:text-gray-500', 'dark:bg-gray-800', 'dark:text-gray-500', 'dark:hover:text-gray-400');
            }
        });
    }

    // Initialize theme buttons on load
    const savedTheme = localStorage.getItem('theme') || 'system';
    updateThemeButtons(savedTheme);
</script>
