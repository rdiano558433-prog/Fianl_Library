<div class="px-3 space-y-2">
    <a href="{{ route('user.dashboard') }}"
       class="sidebar-link {{ request()->routeIs('user.dashboard') ? 'active' : '' }} flex items-center gap-3 px-3 py-2 rounded-lg text-blue-100 hover:bg-blue-800 transition-colors">
        <span class="text-lg flex-shrink-0">📊</span>
        <span x-show="sidebarOpen">Dashboard</span>
    </a>

    <a href="{{ route('user.books.index') }}"
       class="sidebar-link {{ request()->routeIs('user.books.*') ? 'active' : '' }} flex items-center gap-3 px-3 py-2 rounded-lg text-blue-100 hover:bg-blue-800 transition-colors">
        <span class="text-lg flex-shrink-0">📚</span>
        <span x-show="sidebarOpen">Browse Books</span>
    </a>

    <a href="{{ route('user.my-books') }}"
       class="sidebar-link {{ request()->routeIs('user.my-books') ? 'active' : '' }} flex items-center gap-3 px-3 py-2 rounded-lg text-blue-100 hover:bg-blue-800 transition-colors">
        <span class="text-lg flex-shrink-0">🔖</span>
        <span x-show="sidebarOpen">My Books</span>
    </a>

    <a href="{{ route('profile.edit') }}"
       class="sidebar-link {{ request()->routeIs('profile.edit') ? 'active' : '' }} flex items-center gap-3 px-3 py-2 rounded-lg text-blue-100 hover:bg-blue-800 transition-colors">
        <span class="text-lg flex-shrink-0">👤</span>
        <span x-show="sidebarOpen">Profile</span>
    </a>
</div>