<div class="px-3 space-y-2">
    <a href="{{ route('staff.dashboard') }}"
       class="sidebar-link {{ request()->routeIs('staff.dashboard') ? 'active' : '' }} flex items-center gap-3 px-3 py-2 rounded-lg text-blue-100 hover:bg-blue-800 transition-colors">
        <span class="text-lg flex-shrink-0">📊</span>
        <span x-show="sidebarOpen">Dashboard</span>
    </a>

    <a href="{{ route('staff.books.index') }}"
       class="sidebar-link {{ request()->routeIs('staff.books.*') ? 'active' : '' }} flex items-center gap-3 px-3 py-2 rounded-lg text-blue-100 hover:bg-blue-800 transition-colors">
        <span class="text-lg flex-shrink-0">📚</span>
        <span x-show="sidebarOpen">Books</span>
    </a>

    <a href="{{ route('staff.borrowings.index') }}"
       class="sidebar-link
