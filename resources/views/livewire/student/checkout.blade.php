<div>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Checkout') }}
            </h2>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <!-- Course Summary -->
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ __('Course Summary') }}</h3>

                <div class="flex items-start space-x-4">
                    @if($course->thumbnail)
                        <img src="{{ Storage::url($course->thumbnail) }}" alt="{{ $course->title }}"
                            class="w-32 h-24 object-cover rounded-lg">
                    @else
                        <div class="w-32 h-24 bg-gray-200 rounded-lg flex items-center justify-center">
                            <i class="fas fa-book text-gray-400 text-2xl"></i>
                        </div>
                    @endif

                    <div class="flex-1">
                        <h4 class="font-semibold text-gray-800">{{ $course->title }}</h4>
                        <p class="text-sm text-gray-500 mt-1">
                            {{ __('By') }} {{ $course->instructor->name ?? 'N/A' }}
                        </p>
                        <div class="mt-2 flex items-center text-sm text-gray-500">
                            <span class="mr-4">
                                <i class="fas fa-folder mr-1"></i> {{ $course->sections->count() }} sections
                            </span>
                            <span>
                                <i class="fas fa-book mr-1"></i>
                                {{ $course->sections->sum(fn($s) => $s->lessons->count()) }} lessons
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
                <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ __('Payment Details') }}</h3>

                <!-- Error Message -->
                @if($errorMessage)
                    <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-circle text-red-500 mr-3"></i>
                            <p class="text-red-700">{{ $errorMessage }}</p>
                        </div>
                    </div>
                @endif

                <!-- 3D Secure Action Required -->
                @if($requiresAction)
                    <div class="mb-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <div class="flex items-center mb-3">
                            <i class="fas fa-exclamation-triangle text-yellow-500 mr-3"></i>
                            <p class="text-yellow-700">{{ __('Additional authentication required') }}</p>
                        </div>
                        <p class="text-sm text-gray-600 mb-3">
                            {{ __('Please complete the verification with your bank to complete the purchase.') }}
                        </p>
                        <div id="stripe-3ds-container" class="mb-4"></div>
                        <button wire:click="retryPayment"
                            class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm">
                            {{ __('Use Different Payment Method') }}
                        </button>
                    </div>
                @endif

                <!-- Stripe Card Element -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('Card Information') }}
                    </label>
                    <div id="card-element" class="p-4 border border-gray-300 rounded-lg bg-white"
                        style="min-height: 50px;">
                        <!-- Stripe Card Element will be mounted here -->
                    </div>
                    <div id="card-errors" class="mt-2 text-sm text-red-500" role="alert"></div>
                    <p class="mt-2 text-xs text-gray-500">
                        <i class="fas fa-lock mr-1"></i>
                        {{ __('Your payment information is secured with Stripe') }}
                    </p>
                </div>

                <!-- Processing State -->
                @if($isProcessing)
                    <div class="flex items-center justify-center py-4">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-green-600 mr-3"></div>
                        <span class="text-gray-600">{{ __('Processing payment...') }}</span>
                    </div>
                @endif

                <!-- Pay Button -->
                <button x-data @click.prevent="processStripePayment()"
                    :disabled="{{ $isProcessing ? 'true' : 'false' }}"
                    :class="{{ $isProcessing }} ? 'opacity-50 cursor-not-allowed' : ''"
                    class="w-full bg-green-600 hover:bg-green-700 disabled:bg-gray-400 text-white px-6 py-3 rounded-lg font-medium transition-colors mt-4">
                    <span x-show="!{{ $isProcessing }}">
                        <i class="fas fa-lock mr-2"></i>
                        {{ __('Pay') }} ${{ number_format($course->price, 2) }}
                    </span>
                    <span x-show="{{ $isProcessing }}">
                        <i class="fas fa-spinner fa-spin mr-2"></i>
                        {{ __('Processing...') }}
                    </span>
                </button>
            </div>

            <!-- Security & Terms -->
            <div class="p-6 bg-gray-50 border-t border-gray-200">
                <div class="flex items-center justify-center text-sm text-gray-500">
                    <i class="fas fa-shield-alt mr-2"></i>
                    {{ __('Your payment is secured by Stripe. We never store your card details.') }}
                </div>
            </div>
        </div>

        <!-- Back Link -->
        <div class="mt-4 text-center">
            <a href="{{ route('tenant.student.courses') }}" class="text-gray-600 hover:text-gray-800 text-sm">
                <i class="fas fa-arrow-left mr-1"></i>
                {{ __('Back to Courses') }}
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