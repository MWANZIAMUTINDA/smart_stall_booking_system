@extends('layouts.app')

@section('page-title', 'Complete Payment')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">

    {{-- Payment Header --}}
    <div class="bg-gradient-to-r from-emerald-500 via-teal-500 to-cyan-500 text-white p-8 rounded-3xl shadow-xl relative overflow-hidden">
        <div class="absolute -top-10 -right-10 w-48 h-48 bg-white/10 rounded-full blur-3xl animate-pulse"></div>
        <h2 class="text-3xl font-black relative z-10">💳 Complete Your Payment</h2>
        <p class="text-emerald-50 mt-2 font-medium relative z-10">Pay via M-Pesa to confirm your stall reservation</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- Left: Booking Summary --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 sticky top-24">
                <span class="text-[10px] font-black text-emerald-600 uppercase tracking-widest">Booking Summary</span>
                <h3 class="text-3xl font-black text-gray-800 mt-1">Stall #{{ $booking->stall->stall_number }}</h3>

                <div class="space-y-3 border-t border-gray-100 pt-4 mt-4 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-400 font-medium">Zone</span>
                        <span class="text-gray-800 font-bold">{{ $booking->stall->zone ?? 'Main' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400 font-medium">Location</span>
                        <span class="text-gray-800 font-bold">{{ $booking->stall->location_desc ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400 font-medium">Duration</span>
                        <span class="text-gray-800 font-bold">{{ $booking->duration_days }} day{{ $booking->duration_days > 1 ? 's' : '' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400 font-medium">From</span>
                        <span class="text-gray-800 font-bold">{{ $booking->start_time->format('d M, H:i') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400 font-medium">Until</span>
                        <span class="text-gray-800 font-bold">{{ $booking->end_time->format('d M, H:i') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400 font-medium">Receipt No.</span>
                        <span class="text-emerald-600 font-bold">{{ $booking->receipt_number }}</span>
                    </div>
                </div>

                <div class="mt-6 p-4 bg-emerald-50 rounded-2xl border border-emerald-100">
                    <p class="text-xs text-emerald-500 font-bold uppercase tracking-wider mb-1">Amount Due</p>
                    <div class="flex items-baseline gap-1">
                        <span class="text-sm font-bold text-emerald-600">KES</span>
                        <span class="text-4xl font-black text-emerald-700">{{ number_format($booking->amount, 0) }}</span>
                    </div>
                    <p class="text-[10px] text-emerald-600 font-bold mt-1">{{ $booking->duration_days }} day{{ $booking->duration_days > 1 ? 's' : '' }} · KES 1/day rate</p>
                </div>

                {{-- Status Badge --}}
                <div class="mt-4 text-center">
                    <span id="statusBadge" class="inline-block px-4 py-2 rounded-full text-xs font-black uppercase tracking-wider bg-amber-100 text-amber-600 border border-amber-200">
                        ⏳ Awaiting Payment
                    </span>
                </div>
            </div>
        </div>

        {{-- Right: Payment Form --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8" id="paymentFormCard">
                
                {{-- M-Pesa Payment Form --}}
                <div id="paymentForm">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-12 h-12 bg-green-500 rounded-2xl flex items-center justify-center text-white text-xl font-black shadow-lg">M</div>
                        <div>
                            <h3 class="text-xl font-black text-gray-800">Pay with M-Pesa</h3>
                            <p class="text-gray-400 text-sm">You'll receive an STK Push on your phone</p>
                        </div>
                    </div>

                    <div class="space-y-5">
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-widest ml-1 block mb-2">M-Pesa Phone Number</label>
                            <input type="tel" id="mpesaPhone" 
                                   value="{{ auth()->user()->phone_number ?? '' }}"
                                   placeholder="0712345678"
                                   class="w-full bg-gray-50 border-gray-200 rounded-2xl px-5 py-4 text-gray-700 font-bold text-lg focus:ring-4 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all outline-none"
                                   maxlength="13">
                            <p class="text-xs text-gray-400 mt-1 ml-1">Safaricom number registered with M-Pesa</p>
                        </div>

                        <button id="payBtn" onclick="initiatePayment()"
                                class="w-full bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white py-4 rounded-2xl font-black text-lg shadow-xl shadow-green-500/20 hover:scale-[1.02] active:scale-95 transition-all flex items-center justify-center gap-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                            Pay KES {{ number_format($booking->amount, 0) }} via M-Pesa
                        </button>

                        {{-- Simulate button for dev --}}
                        @if(app()->environment('local'))
                        <button id="simulateBtn" onclick="simulatePayment()"
                                class="w-full bg-gray-100 hover:bg-gray-200 text-gray-600 py-3 rounded-2xl font-bold text-sm transition-all border border-gray-200">
                            🧪 Simulate Payment (Dev Only)
                        </button>
                        @endif
                    </div>

                    <p id="paymentError" class="text-red-500 text-sm font-bold mt-4 hidden"></p>
                </div>

                {{-- Loading State --}}
                <div id="paymentLoading" class="hidden text-center py-12">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-emerald-50 rounded-full mb-6">
                        <svg class="animate-spin w-10 h-10 text-emerald-500" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-black text-gray-800 mb-2">Check Your Phone</h3>
                    <p class="text-gray-500 font-medium">Enter your M-Pesa PIN to complete the payment</p>
                    <p class="text-sm text-gray-400 mt-2">Waiting for confirmation...</p>
                    <div class="mt-6 h-2 w-48 mx-auto bg-gray-200 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-emerald-400 to-green-500 rounded-full animate-pulse" style="width: 60%"></div>
                    </div>
                </div>

                {{-- Success State --}}
                <div id="paymentSuccess" class="hidden text-center py-12">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-emerald-100 rounded-full mb-6">
                        <svg class="w-10 h-10 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <h3 class="text-2xl font-black text-gray-800 mb-2">Payment Successful! 🎉</h3>
                    <p class="text-gray-500 font-medium mb-2">Your stall has been booked and confirmed.</p>
                    <p class="text-emerald-600 font-bold text-sm" id="successReceipt"></p>

                    <div class="flex flex-col sm:flex-row gap-3 justify-center mt-8">
                        <a href="{{ route('trader.bookings.receipt', $booking->id) }}" 
                           class="inline-flex items-center gap-2 bg-gradient-to-r from-emerald-500 to-green-600 text-white px-6 py-3 rounded-2xl font-bold shadow-lg hover:scale-105 transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Download Receipt
                        </a>
                        <a href="{{ route('trader.bookings.index') }}" 
                           class="inline-flex items-center gap-2 bg-gray-100 text-gray-700 px-6 py-3 rounded-2xl font-bold hover:bg-gray-200 transition-all">
                            View My Bookings
                        </a>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>

@endsection

@push('scripts')
<script>
const bookingId = {{ $booking->id }};
const csrfToken = '{{ csrf_token() }}';
let pollingInterval = null;

function initiatePayment() {
    const phone = document.getElementById('mpesaPhone').value.trim();
    const errorEl = document.getElementById('paymentError');
    
    if (!phone || phone.length < 10) {
        errorEl.textContent = 'Please enter a valid M-Pesa phone number';
        errorEl.classList.remove('hidden');
        return;
    }

    errorEl.classList.add('hidden');
    document.getElementById('paymentForm').classList.add('hidden');
    document.getElementById('paymentLoading').classList.remove('hidden');

    fetch('{{ route("trader.mpesa.pay") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
        },
        body: JSON.stringify({
            booking_id: bookingId,
            phone: phone,
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            startPolling();
        } else {
            document.getElementById('paymentLoading').classList.add('hidden');
            document.getElementById('paymentForm').classList.remove('hidden');
            errorEl.textContent = data.message || 'Payment failed. Please try again.';
            errorEl.classList.remove('hidden');
        }
    })
    .catch(err => {
        document.getElementById('paymentLoading').classList.add('hidden');
        document.getElementById('paymentForm').classList.remove('hidden');
        errorEl.textContent = 'Network error. Please check your connection.';
        errorEl.classList.remove('hidden');
    });
}

function simulatePayment() {
    document.getElementById('paymentForm').classList.add('hidden');
    document.getElementById('paymentLoading').classList.remove('hidden');

    fetch('{{ route("trader.mpesa.simulate") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
        },
        body: JSON.stringify({ booking_id: bookingId })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            showSuccess(data.receipt_number);
        } else {
            document.getElementById('paymentLoading').classList.add('hidden');
            document.getElementById('paymentForm').classList.remove('hidden');
        }
    });
}

function startPolling() {
    let attempts = 0;
    pollingInterval = setInterval(() => {
        attempts++;
        if (attempts > 40) { // ~2 min timeout
            clearInterval(pollingInterval);
            document.getElementById('paymentLoading').classList.add('hidden');
            document.getElementById('paymentForm').classList.remove('hidden');
            document.getElementById('paymentError').textContent = 'Payment timed out. Please try again.';
            document.getElementById('paymentError').classList.remove('hidden');
            return;
        }

        fetch(`/trader/mpesa/status/${bookingId}`, {
            headers: { 'Accept': 'application/json' }
        })
        .then(res => res.json())
        .then(data => {
            if (data.payment_status === 'paid') {
                clearInterval(pollingInterval);
                showSuccess(data.receipt_number);
            } else if (data.payment_status === 'failed') {
                clearInterval(pollingInterval);
                document.getElementById('paymentLoading').classList.add('hidden');
                document.getElementById('paymentForm').classList.remove('hidden');
                document.getElementById('paymentError').textContent = 'Payment was declined. Please try again.';
                document.getElementById('paymentError').classList.remove('hidden');
            }
        });
    }, 3000);
}

function showSuccess(receiptNumber) {
    document.getElementById('paymentLoading').classList.add('hidden');
    document.getElementById('paymentSuccess').classList.remove('hidden');
    document.getElementById('successReceipt').textContent = 'Receipt: ' + receiptNumber;
    document.getElementById('statusBadge').className = 'inline-block px-4 py-2 rounded-full text-xs font-black uppercase tracking-wider bg-emerald-100 text-emerald-600 border border-emerald-200';
    document.getElementById('statusBadge').textContent = '✅ Paid & Confirmed';
}
</script>
@endpush
