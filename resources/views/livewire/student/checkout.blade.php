<div>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                {{ __('messages.Checkout') }}
            </h2>
        </div>
    </x-slot>

    <div class="max-w-4xl px-4 py-6 mx-auto sm:px-6 lg:px-8">
        <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
            <!-- Course Summary -->
            <div class="p-6 border-b border-gray-200">
                <h3 class="mb-4 text-lg font-semibold text-gray-800">{{ __('messages.Course Summary') }}</h3>

                <div class="flex items-start @if(app()->getLocale() === 'ar') gap-4 @else space-x-4 @endif">
                    @if($course->thumbnail)
                        <img src="{{ Storage::url($course->thumbnail) }}" alt="{{ $course->title }}"
                            class="object-cover w-32 h-24 rounded-lg">
                    @else
                        <div class="flex items-center justify-center w-32 h-24 bg-gray-200 rounded-lg">
                            <i class="text-2xl text-gray-400 fas fa-book"></i>
                        </div>
                    @endif

                    <div class="flex-1">
                        <h4 class="font-semibold text-gray-800">{{ $course->title }}</h4>
                        <p class="mt-1 text-sm text-gray-500">
                            {{ __('messages.By') }} {{ $course->instructor->name ?? 'N/A' }}
                        </p>
                        <div class="flex items-center mt-2 text-sm text-gray-500">
                            <span class="@if(app()->getLocale() === 'ar') ml-4 @else mr-4 @endif">
                                <i class="@if(app()->getLocale() === 'ar') ml-1 @else mr-1 @endif fas fa-folder"></i>
                                {{ __('messages.sections') }}
                                {{ $course->sections->count() }}
                            </span>
                            <span>
                                <i class="@if(app()->getLocale() === 'ar') ml-1 @else mr-1 @endif fas fa-book"></i>
                                {{ __('messages.lessons') }}
                                {{ $course->sections->sum(fn($s) => $s->lessons->count()) }}
                            </span>
                        </div>
                    </div>

                    <div class="text-right">
                        <span class="text-2xl font-bold text-gray-800">
                            ${{ number_format($course->price, 2) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Payment Section -->
            <div class="p-6">
                <h3 class="mb-4 text-lg font-semibold text-gray-800">{{ __('messages.Payment Details') }}</h3>

                <!-- Error Message -->
                @if($errorMessage)
                    <div class="p-4 mb-4 border border-red-200 rounded-lg bg-red-50">
                        <div class="flex items-center">
                            <i class="mr-3 text-red-500 fas fa-exclamation-circle"></i>
                            <p class="text-red-700">{{ $errorMessage }}</p>
                        </div>
                    </div>
                @endif

                <!-- 3D Secure Action Required -->
                @if($requiresAction)
                    <div class="p-4 mb-4 border border-yellow-200 rounded-lg bg-yellow-50">
                        <div class="flex items-center mb-3">
                            <i class="mr-3 text-yellow-500 fas fa-exclamation-triangle"></i>
                            <p class="text-yellow-700">{{ __('messages.Additional authentication required') }}</p>
                        </div>
                        <p class="mb-3 text-sm text-gray-600">
                            {{ __('messages.Please complete the verification with your bank to complete the purchase.') }}
                        </p>
                        <div id="stripe-3ds-container" class="mb-4"></div>
                        <button wire:click="retryPayment"
                            class="px-4 py-2 text-sm text-white bg-gray-600 rounded-lg hover:bg-gray-700">
                            {{ __('messages.Use Different Payment Method') }}
                        </button>
                    </div>
                @endif

                <!-- Stripe Card Element -->
                <div class="mb-4">
                    <label class="block mb-2 text-sm font-medium text-gray-700">
                        {{ __('messages.Card Information') }}
                    </label>
                    <div id="card-element" class="p-4 bg-white border border-gray-300 rounded-lg"
                        style="min-height: 50px;">
                        <!-- Stripe Card Element will be mounted here -->
                    </div>
                    <div id="card-errors" class="mt-2 text-sm text-red-500" role="alert"></div>
                    <p class="mt-2 text-xs text-gray-500">
                        <i class="mr-1 fas fa-lock"></i>
                        {{ __('messages.Your payment information is secured with Stripe') }}
                    </p>
                </div>

                <!-- Processing State -->
                @if($isProcessing)
                    <div class="flex items-center justify-center py-4">
                        <div class="w-8 h-8 mr-3 border-b-2 border-green-600 rounded-full animate-spin"></div>
                        <span class="text-gray-600">{{ __('messages.Processing payment...') }}</span>
                    </div>
                @endif

                <!-- Pay Button -->
                <button x-data @click.prevent="processStripePayment()"
                    :disabled="{{ $isProcessing ? 'true' : 'false' }}"
                    :class="{{ $isProcessing }} ? 'opacity-50 cursor-not-allowed' : ''"
                    class="w-full px-6 py-3 mt-4 font-medium text-white transition-colors bg-green-600 rounded-lg hover:bg-green-700 disabled:bg-gray-400">
                    <span x-show="!{{ $isProcessing }}">
                        <i class="mr-2 fas fa-lock"></i>
                        {{ __('messages.Pay') }} ${{ number_format($course->price, 2) }}
                    </span>
                    <span x-show="{{ $isProcessing }}">
                        <i class="mr-2 fas fa-spinner fa-spin"></i>
                        {{ __('messages.Processing...') }}
                    </span>
                </button>
            </div>

            <!-- Security & Terms -->
            <div class="p-6 border-t border-gray-200 bg-gray-50">
                <div class="flex items-center justify-center text-sm text-gray-500">
                    <i class="mr-2 fas fa-shield-alt"></i>
                    {{ __('messages.Your payment is secured by Stripe. We never store your card details.') }}
                </div>
            </div>
        </div>

        <!-- Back Link -->
        <div class="mt-4 text-center">
            <a href="{{ route('tenant.student.courses') }}" class="text-sm text-gray-600 hover:text-gray-800">
                <i class="mr-1 fas fa-arrow-left"></i>
                {{ __('messages.Back to Courses') }}
            </a>
        </div>
    </div>

    @push('scripts')
        <script src="https://js.stripe.com/v3/"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Initialize Stripe
                const stripe = Stripe('{{ config('services.stripe.key') }}');
                const elements = stripe.elements();

                // Create and mount the card element
                const cardElement = elements.create('card', {
                    style: {
                        base: {
                            fontSize: '16px',
                            color: '#32325d',
                            fontFamily: '"Segoe UI", Roboto, Helvetica, Arial, sans-serif',
                            '::placeholder': {
                                color: '#aab7c4'
                            }
                        },
                        invalid: {
                            color: '#fa755a',
                            iconColor: '#fa755a'
                        }
                    }
                });

                // Wait for the element to be in the DOM
                setTimeout(function () {
                    cardElement.mount('#card-element');
                    console.log('Stripe card element mounted successfully');
                }, 100);

                // Handle validation errors
                cardElement.on('change', function (event) {
                    const displayError = document.getElementById('card-errors');
                    if (event.error) {
                        displayError.textContent = event.error.message;
                        console.error('Card error:', event.error.message);
                    } else {
                        displayError.textContent = '';
                    }
                });

                cardElement.on('ready', function () {
                    console.log('Card element is ready');
                });

                // Make card element globally accessible
                window.stripeCardElement = cardElement;
                window.stripeInstance = stripe;

                // Process payment function
                window.processStripePayment = async function () {
                    const card = window.stripeCardElement;
                    const stripe = window.stripeInstance;

                    if (!card || !stripe) {
                        alert('Payment system not loaded. Please refresh the page.');
                        return;
                    }

                    try {
                        const { paymentMethod, error } = await stripe.createPaymentMethod({
                            type: 'card',
                            card: card,
                        });

                        if (error) {
                            document.getElementById('card-errors').textContent = error.message;
                            console.error('Stripe error:', error.message);
                            return;
                        }

                        console.log('Payment method created:', paymentMethod.id);

                        // Call Livewire method
                        Livewire.dispatch('processPayment', { paymentMethodId: paymentMethod.id });

                    } catch (e) {
                        console.error('Payment error:', e);
                        alert('An error occurred. Please try again.');
                    }
                };
            });

            // Listen for Livewire events
            document.addEventListener('livewire:init', function () {
                console.log('Livewire initialized');
            });
        </script>
    @endpush
</div>
