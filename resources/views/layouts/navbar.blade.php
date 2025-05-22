<nav class="bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 py-3 flex justify-between items-center">
        <!-- Logo & App Name -->
        <div class="flex items-center gap-3">
            <x-application-logo class="w-10 h-10 text-gray-500 dark:text-gray-300" />
            <a href="{{ url('/') }}" class="text-xl font-bold text-gray-800 dark:text-gray-200">Fishing Trip Logger</a>
        </div>

        <!-- Navigation Links -->
        <div class="flex gap-4 items-center">
            @guest
                <a href="{{ route('login') }}"
                    class="text-gray-700 hover:text-blue-500 dark:text-gray-300 dark:hover:text-blue-400">Login</a>
                <a href="{{ route('register') }}"
                    class="text-gray-700 hover:text-blue-500 dark:text-gray-300 dark:hover:text-blue-400">Register</a>
            @else
                <a href="{{ route('dashboard') }}"
                    class="{{ request()->routeIs('dashboard') ? 'text-blue-600 dark:text-blue-400 font-semibold' : 'text-gray-700 dark:text-gray-300 hover:text-blue-500 dark:hover:text-blue-400' }}">Dashboard</a>
                <a href="{{ route('profile.edit') }}"
                    class="text-gray-700 hover:text-blue-500 dark:text-gray-300 dark:hover:text-blue-400">Profile</a>
                <a href="{{ route('trips.index') }}"
                    class="{{ request()->routeIs('trips.*') ? 'text-green-600 dark:text-green-400 font-semibold' : 'text-gray-700 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-400' }}">My
                    Trips</a>

                @can('access-admin')
                    <a href="{{ route('admin.dashboard') }}"
                        class="{{ request()->routeIs('admin.*') ? 'text-red-600 dark:text-red-400 font-semibold' : 'text-gray-700 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-400' }}">Admin</a>
                @endcan

                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit"
                        class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 ml-2">Logout</button>
                </form>
            @endguest
        </div>
    </div>
</nav>