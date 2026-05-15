<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Tenant Settings') }}
    </h2>
</x-slot>

<div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
    @if($tenant)
        <!-- Tenant Info Card -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-6 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">{{ __('Organization Information') }}</h3>
                        <p class="text-sm text-gray-500 mt-1">{{ __('Manage your organization details and settings') }}</p>
                    </div>
                    <span
                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $this->getPlanBadgeClass($tenant->plan) }}">
                        {{ ucfirst($tenant->plan) }} Plan
                    </span>
                </div>
            </div>
            <div class="p-6">
                <form wire:submit.prevent="save">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                                {{ __('Organization Name') }}
                            </label>
                            <input type="text" id="name" wire:model.lazy="name"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror">
                            @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Slug -->
                        <div>
                            <label for="slug" class="block text-sm font-medium text-gray-700 mb-1">
                                {{ __('URL Slug') }}
                            </label>
                            <div class="flex">
                                <span
                                    class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                                    /
                                </span>
                                <input type="text" id="slug" wire:model.lazy="slug"
                                    class="flex-1 border border-gray-300 rounded-none rounded-r-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('slug') border-red-500 @enderror">
                            </div>
                            @error('slug') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Domain -->
                        <div>
                            <label for="domain" class="block text-sm font-medium text-gray-700 mb-1">
                                {{ __('Custom Domain') }} <span class="text-gray-400">(optional)</span>
                            </label>
                            <input type="text" id="domain" wire:model.lazy="domain" placeholder="academy.example.com"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('domain') border-red-500 @enderror">
                            @error('domain') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Plan -->
                        <div>
                            <label for="plan" class="block text-sm font-medium text-gray-700 mb-1">
                                {{ __('Subscription Plan') }}
                            </label>
                            <select id="plan" wire:model.lazy="plan"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('plan') border-red-500 @enderror">
                                @foreach($planOptions as $option)
                                    <option value="{{ $option['value'] }}">{{ $option['label'] }} - {{ $option['description'] }}
                                    </option>
                                @endforeach
                            </select>
                            @error('plan') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Status Toggle -->
                    <div class="mt-6 flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div>
                            <h4 class="text-sm font-medium text-gray-700">{{ __('Organization Status') }}</h4>
                            <p class="text-sm text-gray-500">
                                @if($is_active)
                                    {{ __('This organization is currently active') }}
                                @else
                                    {{ __('This organization is currently inactive') }}
                                @endif
                            </p>
                        </div>
                        <div class="flex items-center">
                            @if($is_active)
                                <button type="button" wire:click="openDeleteModal"
                                    class="text-sm text-red-600 hover:text-red-800 mr-4">
                                    {{ __('Deactivate') }}
                                </button>
                                <span class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" wire:model.live="is_active" class="sr-only peer" checked>
                                    <div
                                        class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600">
                                    </div>
                                </span>
                            @else
                                <button type="button" wire:click="activateTenant"
                                    class="text-sm text-green-600 hover:text-green-800 mr-4">
                                    {{ __('Activate') }}
                                </button>
                                <span class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" wire:model.live="is_active" class="sr-only peer">
                                    <div
                                        class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600">
                                    </div>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="mt-6">
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                            {{ __('Save Changes') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Branding Card -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">{{ __('Branding') }}</h3>
                <p class="text-sm text-gray-500 mt-1">{{ __('Customize your organization\'s branding and appearance') }}</p>
            </div>
            <div class="p-6">
                <form wire:submit.prevent="saveBranding">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Branding Name -->
                        <div>
                            <label for="branding_name" class="block text-sm font-medium text-gray-700 mb-1">
                                {{ __('Display Name') }}
                            </label>
                            <input type="text" id="branding_name" wire:model.lazy="branding_name" placeholder="My Academy"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <p class="text-xs text-gray-500 mt-1">
                                {{ __('This name will appear in email notifications and invoices') }}</p>
                        </div>

                        <!-- Primary Color -->
                        <div>
                            <label for="branding_primary_color" class="block text-sm font-medium text-gray-700 mb-1">
                                {{ __('Primary Color') }}
                            </label>
                            <div class="flex items-center gap-3">
                                <input type="color" wire:model.live="branding_primary_color"
                                    class="h-10 w-20 rounded cursor-pointer border border-gray-300">
                                <input type="text" wire:model.lazy="branding_primary_color"
                                    class="flex-1 border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    placeholder="#3B82F6">
                            </div>
                            <p class="text-xs text-gray-500 mt-1">
                                {{ __('Used for buttons and accents throughout the platform') }}</p>
                        </div>
                    </div>

                    <!-- Color Preview -->
                    <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                        <h4 class="text-sm font-medium text-gray-700 mb-3">{{ __('Preview') }}</h4>
                        <div class="flex items-center gap-4">
                            <button class="px-4 py-2 rounded-md text-white text-sm font-medium"
                                style="background-color: {{ $branding_primary_color }}">
                                Primary Button
                            </button>
                            <div class="w-8 h-8 rounded-full border-2" style="border-color: {{ $branding_primary_color }}">
                            </div>
                        </div>
                    </div>

                    <div class="mt-6">
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                            {{ __('Save Branding') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Danger Zone -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-red-200">
            <div class="p-6 border-b border-red-200 bg-red-50">
                <h3 class="text-lg font-semibold text-red-800">{{ __('Danger Zone') }}</h3>
                <p class="text-sm text-red-600 mt-1">{{ __('These actions are irreversible. Please be careful.') }}</p>
            </div>
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h4 class="text-sm font-medium text-gray-700">{{ __('Deactivate Organization') }}</h4>
                        <p class="text-sm text-gray-500">
                            {{ __('This will prevent all users from accessing the platform.') }}</p>
                    </div>
                    <button type="button" wire:click="openDeleteModal"
                        class="text-red-600 hover:text-white border border-red-600 hover:bg-red-600 px-4 py-2 rounded-md text-sm font-medium transition-colors">
                        {{ __('Deactivate') }}
                    </button>
                </div>
            </div>
        </div>
    @else
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-center">
            <p class="text-gray-500">{{ __('Unable to load tenant information.') }}</p>
        </div>
    @endif

    <!-- Deactivate Confirmation Modal -->
    @if($showDeleteModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" x-data="{ show: true }" x-show="show">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" wire:click="closeModal"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
                <div
                    class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div
                                class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                <i class="fas fa-exclamation-triangle text-red-600"></i>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg font-medium leading-6 text-gray-900">{{ __('Deactivate Organization') }}
                                </h3>
                                <p class="mt-2 text-sm text-gray-500">
                                    {{ __('Are you sure you want to deactivate this organization?') }}<br>
                                    {{ __('All users will lose access until the organization is reactivated.') }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button wire:click="deactivateTenant" type="button"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                            {{ __('Deactivate') }}
                        </button>
                        <button wire:click="closeModal" type="button"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            {{ __('Cancel') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>