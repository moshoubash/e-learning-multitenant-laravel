<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("You're logged in!") }}
                </div>

                <!-- Roles & Permissions Section -->
                <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-8 px-6 pb-8">
                    <!-- Roles Card -->
                    <div class="bg-gray-50 rounded-2xl p-6 border border-gray-100 shadow-sm transition-all hover:shadow-md">
                        <div class="flex items-center space-x-3 mb-6">
                            <div class="p-2 bg-indigo-100 rounded-lg text-indigo-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-800 uppercase tracking-wider">{{ __('Your Roles') }}</h3>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            @forelse(Auth::user()->getRoleNames() as $role)
                                <span class="px-4 py-1.5 bg-indigo-600 text-white text-xs font-bold rounded-full shadow-sm hover:bg-indigo-700 transition-colors">
                                    {{ strtoupper($role) }}
                                </span>
                            @empty
                                <span class="text-sm text-gray-500 italic">{{ __('No roles assigned.') }}</span>
                            @endforelse
                        </div>
                    </div>

                    <!-- Permissions Card -->
                    <div class="bg-gray-50 rounded-2xl p-6 border border-gray-100 shadow-sm transition-all hover:shadow-md">
                        <div class="flex items-center space-x-3 mb-6">
                            <div class="p-2 bg-emerald-100 rounded-lg text-emerald-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.040L3 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622l-.382-3.016z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-800 uppercase tracking-wider">{{ __('Permissions') }}</h3>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @forelse(Auth::user()->getAllPermissions() as $permission)
                                <div class="flex items-center space-x-2 text-sm text-gray-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span>{{ $permission->name }}</span>
                                </div>
                            @empty
                                <span class="text-sm text-gray-500 italic col-span-2">{{ __('No permissions granted.') }}</span>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>