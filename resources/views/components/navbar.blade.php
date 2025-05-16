<nav class="bg-white border-b border-gray-200 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 py-3 flex justify-between items-center">
        <!-- Logo & App Name -->
        <div class="flex items-center gap-3">
            <x-application-logo class="w-10 h-10 fill-current text-gray-500"/>
            <a href="{{ url('/') }}" class="text-xl font-bold text-gray-800">Fishing Trip Logger</a>
        </div>

        <!-- Navigation Links -->
        <div class="flex gap-4 items-center">
            @guest
                <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-500">Login</a>
                <a href="{{ route('register') }}" class="text-gray-700 hover:text-blue-500">Register</a>
            @else
                <!-- Common Authenticated Links -->
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'text-blue-600 font-semibold' : 'text-gray-700 hover:text-blue-500' }}">Dashboard</a>
                <a href="{{ route('trips.index') }}" class="{{ request()->routeIs('trips.*') ? 'text-green-600 font-semibold' : 'text-gray-700 hover:text-green-600' }}">My Trips</a>
                <a href="{{ route('catches.index') }}" class="{{ request()->routeIs('catches.*') ? 'text-purple-600 font-semibold' : 'text-gray-700 hover:text-purple-600' }}">Catches</a>
                <a href="{{ route('gallery.index') }}" class="{{ request()->routeIs('gallery.*') ? 'text-pink-600 font-semibold' : 'text-gray-700 hover:text-pink-600' }}">Gallery</a>

                <!-- Admin Panel (conditionally visible) -->
                @can('access-admin')
                    <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.*') ? 'text-red-600 font-semibold' : 'text-gray-700 hover:text-red-600' }}">Admin</a>
                @endcan

                <!-- Profile & Logout -->
                <a href="{{ route('profile.edit') }}" class="text-gray-700 hover:text-blue-500">{{ Auth::user()->name }}</a>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="text-red-600 hover:text-red-800 ml-2">Logout</button>
                </form>
            @endguest
        </div>
    </div>
</nav>