<div class="px-3 space-y-2">
    <a href="{{ route('admin.dashboard') }}"
       class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }} flex items-center gap-3 px-3 py-2 rounded-lg text-blue-100 hover:bg-blue-800 transition-colors">
        <span class="text-lg flex-shrink-0">📊</span>
        <span x-show="sidebarOpen">Dashboard</span>
    </a>

    <a href="{{ route('admin.users.index') }}"
       class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }} flex items-center gap-3 px-3 py-2 rounded-lg text-blue-100 hover:bg-blue-800 transition-colors">
        <span class="text-lg flex-shrink-0">👥</span>
        <span x-show="sidebarOpen">Users</span>
    </a>

    <a href="{{ route('admin.books.index') }}"
       class="sidebar-link {{ request()->routeIs('admin.books.*') ? 'active' : '' }} flex items-center gap-3 px-3 py-2 rounded-lg text-blue-100 hover:bg-blue-800 transition-colors">
        <span class="text-lg flex-shrink-0">📚</span>
        <span x-show="sidebarOpen">Books</span>
    </a>

    <a href="{{ route('admin.borrowings.index') }}"
       class="sidebar-link {{ request()->routeIs('admin.borrowings.*') ? 'active' : '' }} flex items-center gap-3 px-3 py-2 rounded-lg text-blue-100 hover:bg-blue-800 transition-colors">
        <span class="text-lg flex-shrink-0">📖</span>
        <span x-show="sidebarOpen">Borrowings</span>
    </a>

    <a href="{{ route('admin.reports.index') }}"
       class="sidebar-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }} flex items-center gap-3 px-3 py-2 rounded-lg text-blue-100 hover:bg-blue-800 transition-colors">
        <span class="text-lg flex-shrink-0">📈</span>
        <span x-show="sidebarOpen">Reports</span>
    </a>

    <a href="{{ route('profile.edit') }}"
       class="sidebar-link {{ request()->routeIs('profile.edit') ? 'active' : '' }} flex items-center gap-3 px-3 py-2 rounded-lg text-blue-100 hover:bg-blue-800 transition-colors">
        <span class="text-lg flex-shrink-0">👤</span>
        <span x-show="sidebarOpen">Profile</span>
    </a>
</div>