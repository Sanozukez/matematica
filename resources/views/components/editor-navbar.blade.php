{{-- Editor Navbar - Isolated Component --}}
<style>
    /* Scoped styles for editor navbar only */
    .editor-navbar {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        height: 65px;
        background-color: #18181B;
        color: #FFFFFF;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 1.5rem;
        z-index: 1000;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        transition: background-color 0.3s, color 0.3s;
    }

    /* Light theme */
    body[data-theme="light"] .editor-navbar {
        background-color: #FFFFFF;
        color: #18181B;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    body[data-theme="light"] .editor-navbar-back-btn {
        color: #18181B;
    }

    body[data-theme="light"] .editor-navbar-dropdown {
        background-color: #F4F4F5;
        color: #18181B;
    }

    body[data-theme="light"] .editor-navbar-dropdown-header {
        border-bottom: 1px solid rgba(0, 0, 0, 0.1);
    }

    body[data-theme="light"] .editor-navbar-dropdown-email {
        color: rgba(0, 0, 0, 0.6);
    }

    body[data-theme="light"] .editor-navbar-dropdown-item {
        color: #18181B;
    }

    body[data-theme="light"] .editor-navbar-dropdown-item:hover {
        background-color: rgba(0, 0, 0, 0.05);
    }

    body[data-theme="light"] .editor-navbar-avatar {
        background-color: #E5E5E5;
        color: #18181B;
    }

    .editor-navbar-left {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .editor-navbar-back-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border-radius: 8px;
        background: transparent;
        border: none;
        cursor: pointer;
        transition: background-color 0.2s;
        color: #FFFFFF;
    }

    .editor-navbar-back-btn:hover {
        background-color: rgba(255, 255, 255, 0.1);
    }

    body[data-theme="light"] .editor-navbar-back-btn:hover {
        background-color: rgba(0, 0, 0, 0.05);
    }

    .editor-navbar-back-btn svg {
        width: 24px;
        height: 24px;
    }

    .editor-navbar-brand {
        font-size: 1.25rem;
        font-weight: 600;
        letter-spacing: -0.025em;
    }

    .editor-navbar-right {
        position: relative;
    }

    .editor-navbar-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: #000000;
        color: #FFFFFF;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        border: 2px solid transparent;
        transition: border-color 0.2s;
        text-transform: uppercase;
    }

    .editor-navbar-avatar:hover {
        border-color: rgba(255, 255, 255, 0.3);
    }

    body[data-theme="light"] .editor-navbar-avatar:hover {
        border-color: rgba(0, 0, 0, 0.2);
    }

    .editor-navbar-dropdown {
        position: absolute;
        top: calc(100% + 0.5rem);
        right: 0;
        min-width: 200px;
        background-color: #27272A;
        border-radius: 8px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
        overflow: hidden;
        opacity: 0;
        visibility: hidden;
        transform: translateY(-10px);
        transition: all 0.2s ease-in-out;
    }

    .editor-navbar-dropdown.show {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }

    .editor-navbar-dropdown-header {
        padding: 1rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .editor-navbar-dropdown-name {
        font-weight: 600;
        font-size: 0.875rem;
        margin-bottom: 0.25rem;
    }

    .editor-navbar-dropdown-email {
        font-size: 0.75rem;
        color: rgba(255, 255, 255, 0.6);
    }

    .editor-navbar-dropdown-menu {
        padding: 0.5rem 0;
    }

    .editor-navbar-dropdown-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        width: 100%;
        padding: 0.75rem 1rem;
        text-align: left;
        background: none;
        border: none;
        color: #FFFFFF;
        cursor: pointer;
        font-size: 0.875rem;
        transition: background-color 0.15s;
        text-decoration: none;
    }

    .editor-navbar-dropdown-item svg {
        width: 18px;
        height: 18px;
        flex-shrink: 0;
    }

    .editor-navbar-dropdown-item:hover {
        background-color: rgba(255, 255, 255, 0.1);
    }

    body[data-theme="light"] .editor-navbar-dropdown-item:hover {
        background-color: rgba(0, 0, 0, 0.05);
    }

    .editor-navbar-dropdown-item.danger {
        color: #EF4444;
    }

    .editor-navbar-dropdown-item.danger:hover {
        background-color: rgba(239, 68, 68, 0.1);
    }

    .editor-navbar-theme-row {
        display: flex;
        gap: 0.5rem;
        padding: 0.75rem 1rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    body[data-theme="light"] .editor-navbar-theme-row {
        border-bottom: 1px solid rgba(0, 0, 0, 0.1);
    }

    .editor-navbar-theme-btn {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0.5rem;
        background: transparent;
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.2s;
        color: rgba(255, 255, 255, 0.6);
    }

    body[data-theme="light"] .editor-navbar-theme-btn {
        border: 1px solid rgba(0, 0, 0, 0.2);
        color: rgba(0, 0, 0, 0.6);
    }

    .editor-navbar-theme-btn svg {
        width: 20px;
        height: 20px;
    }

    .editor-navbar-theme-btn:hover {
        border-color: rgba(255, 255, 255, 0.4);
        color: rgba(255, 255, 255, 0.9);
    }

    body[data-theme="light"] .editor-navbar-theme-btn:hover {
        border-color: rgba(0, 0, 0, 0.3);
        color: rgba(0, 0, 0, 0.8);
    }

    .editor-navbar-theme-btn.active {
        color: #38BDF8;
        border-color: #38BDF8;
    }

    /* Body padding to account for fixed navbar */
    body {
        padding-top: 65px;
        transition: background-color 0.3s, color 0.3s;
    }

    body[data-theme="light"] {
        background-color: #FFFFFF;
        color: #18181B;
    }
</style>

<nav class="editor-navbar" 
     x-data="{ 
        dropdownOpen: false,
        theme: localStorage.getItem('editorTheme') || 'dark',
        init() {
            document.body.setAttribute('data-theme', this.theme);
        },
        setTheme(newTheme) {
            this.theme = newTheme;
            localStorage.setItem('editorTheme', newTheme);
            document.body.setAttribute('data-theme', newTheme);
        }
     }" 
     @click.away="dropdownOpen = false"
>
    {{-- Left side: Back button + Brand --}}
    <div class="editor-navbar-left">
        <button 
            class="editor-navbar-back-btn" 
            onclick="window.location.href='{{ route('filament.admin.resources.lessons.index') }}'"
            title="Voltar para Lessons"
        >
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
        </button>
        
        <span class="editor-navbar-brand">
            {{ config('app.name', 'Matemática') }}
        </span>
    </div>

    {{-- Right side: User avatar + dropdown --}}
    <div class="editor-navbar-right">
        <div 
            class="editor-navbar-avatar" 
            @click="dropdownOpen = !dropdownOpen"
            title="{{ auth()->user()->name }}"
        >
            {{ substr(auth()->user()->name, 0, 1) }}
        </div>

        <div class="editor-navbar-dropdown" :class="{ 'show': dropdownOpen }">
            {{-- User info header --}}
            <div class="editor-navbar-dropdown-header">
                <div class="editor-navbar-dropdown-name">
                    {{ auth()->user()->name }}
                </div>
                <div class="editor-navbar-dropdown-email">
                    {{ auth()->user()->email }}
                </div>
            </div>

            {{-- Theme switcher --}}
            <div class="editor-navbar-theme-row">
                <button 
                    @click="setTheme('light')"
                    class="editor-navbar-theme-btn"
                    :class="{ 'active': theme === 'light' }"
                    title="Tema Claro"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" />
                    </svg>
                </button>
                
                <button 
                    @click="setTheme('dark')"
                    class="editor-navbar-theme-btn"
                    :class="{ 'active': theme === 'dark' }"
                    title="Tema Escuro"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z" />
                    </svg>
                </button>
            </div>

            {{-- Menu items --}}
            <div class="editor-navbar-dropdown-menu">
                <a 
                    href="{{ route('filament.admin.pages.dashboard') }}" 
                    class="editor-navbar-dropdown-item"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
                    </svg>
                    Dashboard
                </a>
                
                <a 
                    href="{{ route('filament.admin.resources.lessons.index') }}" 
                    class="editor-navbar-dropdown-item"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                    </svg>
                    Lessons
                </a>

                <form method="POST" action="{{ route('filament.admin.auth.logout') }}" style="margin: 0;">
                    @csrf
                    <button type="submit" class="editor-navbar-dropdown-item danger">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                        </svg>
                        Sair
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>
