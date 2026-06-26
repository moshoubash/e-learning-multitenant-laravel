<div>
    <header class="h-16 flex justify-between items-center px-[24px] bg-surface-container-lowest border-b-2 border-on-surface sticky top-0 z-40">
        <div>
            <h2 class="text-[24px] font-bold text-on-surface leading-none tracking-[0.08em]">{{ __('messages.Checkout') }}</h2>
        <p class="text-[12px] font-medium uppercase text-secondary mt-0.5 tracking-wider">Complete your enrollment</p>
    </div>
    <div class="flex items-center gap-2">
        @livewire('shared.notification-bell')
    </div>
</header>

    <div class="p-[24px] max-w-[1400px] mx-auto">
        <div class="overflow-hidden bg-surface-container-lowest neo-border neo-radius">
            {{-- Course Summary --}}
            <div class="p-[24px] border-b-2 border-on-surface">
                <h3 class="mb-4 text-xs font-bold tracking-widest uppercase text-on-surface">{{ __('messages.Course Summary') }}</h3>
                <div class="flex items-start gap-4">
                    @if($course->thumbnail)
                        <img src="{{ $course->thumbnail }}" alt="{{ $course->title }}"
                            class="object-cover w-32 h-24 neo-border-sm neo-radius shrink-0">
                    @else
                        <div class="flex items-center justify-center w-32 h-24 neo-border-sm neo-radius bg-surface-container shrink-0">
                            <i class="text-2xl text-secondary fas fa-book"></i>
                        </div>
                    @endif
                    <div class="flex-1 min-w-0">
                        <h4 class="text-sm font-bold text-on-surface">{{ $course->title }}</h4>
                        <p class="mt-1 text-sm text-secondary">{{ __('messages.By') }} {{ $course->instructor->name ?? 'N/A' }}</p>
                        <div class="flex items-center mt-2 text-xs text-secondary">
                            <span class="ltr:mr-4 rtl:ml-4">
                                <i class="fas fa-folder ltr:mr-1 rtl:ml-1"></i>
                                {{ __('messages.sections') }} {{ $course->sections->count() }}
                            </span>
                            <span>
                                <i class="fas fa-book ltr:mr-1 rtl:ml-1"></i>
                                {{ __('messages.lessons') }} {{ $course->sections->sum(fn($s) => $s->lessons->count()) }}
                            </span>
                        </div>
                    </div>
                    <div class="text-right shrink-0">
                        <span class="text-xl font-bold text-on-surface">${{ number_format($course->price, 2) }}</span>
                    </div>
                </div>
            </div>

            {{-- Payment Section --}}
            <div class="p-[24px]">
                <h3 class="mb-4 text-xs font-bold tracking-widest uppercase text-on-surface">{{ __('messages.Payment Details') }}</h3>

                @if($errorMessage)
                    <div class="p-4 mb-4 neo-border-sm neo-radius bg-error/10">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-circle text-error ltr:mr-3 rtl:ml-3"></i>
                            <p class="text-sm font-medium text-error">{{ $errorMessage }}</p>
                        </div>
                    </div>
                @endif

                @if($requiresAction)
                    <div class="p-4 mb-4 neo-border-sm neo-radius bg-primary-container/30">
                        <div class="flex items-center mb-3">
                            <i class="fas fa-exclamation-triangle text-on-primary-container ltr:mr-3 rtl:ml-3"></i>
                            <p class="text-sm font-bold text-on-surface">{{ __('messages.Additional authentication required') }}</p>
                        </div>
                        <p class="mb-3 text-xs text-on-surface/70">{{ __('messages.Please complete the verification with your bank to complete the purchase.') }}</p>
                        <div id="stripe-3ds-container" class="mb-4"></div>
                        <button wire:click="retryPayment"
                            class="px-4 py-2 text-xs font-bold transition-colors neo-border-sm neo-radius text-on-surface bg-surface-container hover:bg-on-surface hover:text-white">
                            {{ __('messages.Use Different Payment Method') }}
                        </button>
                    </div>
                @endif

                <div class="mb-4">
                    <label class="block mb-2 text-xs font-bold tracking-widest uppercase text-on-surface">
                        {{ __('messages.Card Information') }}
                    </label>
                    <div id="card-element" class="p-4 neo-border-sm neo-radius bg-surface-container-low"
                        style="min-height: 50px;"></div>
                    <div id="card-errors" class="mt-2 text-xs text-error"></div>
                    <p class="mt-2 text-xs text-secondary">
                        <i class="fas fa-lock ltr:mr-1 rtl:ml-1"></i>
                        {{ __('messages.Your payment information is secured with Stripe') }}
                    </p>
                </div>

                @if($isProcessing)
                    <div class="flex items-center justify-center py-4">
                        <div class="w-6 h-6 ltr:mr-3 rtl:ml-3 neo-border-sm neo-radius border-on-surface border-t-transparent animate-spin"></div>
                        <span class="text-xs font-bold tracking-widest uppercase text-on-surface">{{ __('messages.Processing payment...') }}</span>
                    </div>
                @endif

                <button x-data @click.prevent="processStripePayment()"
                    :disabled="{{ $isProcessing ? 'true' : 'false' }}"
                    class="w-full px-6 py-3 mt-4 text-xs font-bold tracking-widest uppercase transition-colors neo-border neo-radius bg-primary-container text-on-primary-container hover:bg-on-surface hover:text-white disabled:opacity-50 disabled:cursor-not-allowed">
                    <span x-show="!{{ $isProcessing }}">
                        <i class="fas fa-lock ltr:mr-2 rtl:ml-2"></i>
                        {{ __('messages.Pay with Card') }} ${{ number_format($course->price, 2) }}
                    </span>
                    <span x-show="{{ $isProcessing }}">
                        <i class="fas fa-spinner fa-spin ltr:mr-2 rtl:ml-2"></i>
                        {{ __('messages.Processing...') }}
                    </span>
                </button>

                @if($this->paypalEnabled)
                    <div class="relative my-4">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-on-surface/20"></div>
                        </div>
                        <div class="relative flex justify-center my-4">
                            <span class="px-3 text-xs font-bold tracking-widest uppercase bg-surface-container-lowest text-secondary">{{ __('messages.Or') }}</span>
                        </div>
                    </div>

                    <form action="{{ route('tenant.student.paypal.create', ['course' => $course->id]) }}" method="POST">
                        @csrf
                        <button type="submit" :disabled="{{ $isProcessing ? 'true' : 'false' }}"
                            class="w-full px-6 py-3 neo-border neo-radius bg-[#0070BA] text-white font-bold text-xs uppercase tracking-widest hover:bg-[#003087] transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center">
                            <i class="text-lg fab fa-paypal ltr:mr-2 rtl:ml-2"></i>
                            {{ __('messages.Pay with PayPal') }} ${{ number_format($course->price, 2) }}
                        </button>
                    </form>
                @endif
            </div>

            {{-- Security & Terms --}}
            <div class="p-[24px] border-t-2 border-on-surface bg-surface-container-low">
                <div class="flex items-center justify-center text-xs text-secondary">
                    <i class="fas fa-shield-alt ltr:mr-2 rtl:ml-2"></i>
                    {{ __('messages.Your payment is secured by Stripe. We never store your card details.') }}
                </div>
            </div>
        </div>

        <div class="mt-4 text-center">
            <a href="{{ route('tenant.student.courses') }}" class="text-xs font-bold tracking-widest uppercase transition-colors text-secondary hover:text-on-surface">
                <i class="fas fa-arrow-left ltr:mr-1 rtl:ml-1"></i>
                {{ __('messages.Back to Courses') }}
            </a>
        </div>
    </div>

    @push('scripts')
        <script src="https://js.stripe.com/v3/"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const stripe = Stripe('{{ config('services.stripe.key') }}');
                const elements = stripe.elements();

                const cardElement = elements.create('card', {
                    style: {
                        base: {
                            fontSize: '16px',
                            color: '#0A0A0A',
                            fontFamily: '"Space Grotesk", sans-serif',
                            '::placeholder': { color: '#5f5e5e' }
                        },
                        invalid: { color: '#ba1a1a', iconColor: '#ba1a1a' }
                    }
                });

                setTimeout(function () {
                    cardElement.mount('#card-element');
                }, 100);

                cardElement.on('change', function (event) {
                    const displayError = document.getElementById('card-errors');
                    if (event.error) {
                        displayError.textContent = event.error.message;
                    } else {
                        displayError.textContent = '';
                    }
                });

                window.stripeCardElement = cardElement;
                window.stripeInstance = stripe;

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
                            return;
                        }

                        Livewire.dispatch('processPayment', { paymentMethodId: paymentMethod.id });
                    } catch (e) {
                        console.error('Payment error:', e);
                        alert('An error occurred. Please try again.');
                    }
                };
            });
        </script>
    @endpush
</div>
