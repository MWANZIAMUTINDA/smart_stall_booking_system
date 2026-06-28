<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Receipt {{ $booking->receipt_number }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            color: #1e293b;
            background: #fff;
            padding: 30px;
        }

        .receipt-container {
            max-width: 500px;
            margin: 0 auto;
            border: 2px solid #e2e8f0;
            border-radius: 16px;
            overflow: hidden;
        }

        /* Header */
        .receipt-header {
            background: linear-gradient(135deg, #059669, #0d9488);
            color: white;
            padding: 24px;
            text-align: center;
        }
        .receipt-header h1 {
            font-size: 22px;
            font-weight: 900;
            letter-spacing: 2px;
            text-transform: uppercase;
        }
        .receipt-header p {
            font-size: 11px;
            opacity: 0.8;
            margin-top: 4px;
            letter-spacing: 1px;
        }

        /* Receipt Number Bar */
        .receipt-number-bar {
            background: #f0fdf4;
            border-bottom: 1px solid #bbf7d0;
            padding: 12px 24px;
            text-align: center;
        }
        .receipt-number-bar .label {
            font-size: 9px;
            font-weight: 700;
            color: #059669;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .receipt-number-bar .number {
            font-size: 20px;
            font-weight: 900;
            color: #065f46;
            margin-top: 2px;
        }

        /* Body */
        .receipt-body {
            padding: 24px;
        }

        .section-title {
            font-size: 9px;
            font-weight: 700;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 12px;
            padding-bottom: 6px;
            border-bottom: 1px solid #f1f5f9;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 6px 0;
            font-size: 12px;
        }
        .detail-row .label {
            color: #64748b;
            font-weight: 500;
        }
        .detail-row .value {
            color: #1e293b;
            font-weight: 700;
            text-align: right;
        }

        /* Amount Box */
        .amount-box {
            background: #f0fdf4;
            border: 2px solid #bbf7d0;
            border-radius: 12px;
            padding: 16px;
            text-align: center;
            margin: 20px 0;
        }
        .amount-box .label {
            font-size: 10px;
            font-weight: 700;
            color: #059669;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .amount-box .amount {
            font-size: 32px;
            font-weight: 900;
            color: #065f46;
            margin-top: 4px;
        }
        .amount-box .currency {
            font-size: 14px;
            font-weight: 700;
        }

        /* Status Badge */
        .status-badge {
            display: inline-block;
            background: #059669;
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Footer */
        .receipt-footer {
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
            padding: 16px 24px;
            text-align: center;
        }
        .receipt-footer p {
            font-size: 9px;
            color: #94a3b8;
            line-height: 1.6;
        }
        .receipt-footer .timestamp {
            font-size: 10px;
            color: #64748b;
            font-weight: 600;
            margin-top: 8px;
        }

        .divider {
            border: none;
            border-top: 1px dashed #e2e8f0;
            margin: 16px 0;
        }

        .section { margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="receipt-container">
        {{-- Header --}}
        <div class="receipt-header">
            <h1>Muthurwa Market</h1>
            <p>Official Stall Booking Receipt</p>
        </div>

        {{-- Receipt Number --}}
        <div class="receipt-number-bar">
            <div class="label">Receipt Number</div>
            <div class="number">{{ $booking->receipt_number }}</div>
        </div>

        {{-- Body --}}
        <div class="receipt-body">

            {{-- Trader Details --}}
            <div class="section">
                <div class="section-title">Trader Information</div>
                <div class="detail-row">
                    <span class="label">Name</span>
                    <span class="value">{{ $booking->user->name }}</span>
                </div>
                <div class="detail-row">
                    <span class="label">Email</span>
                    <span class="value">{{ $booking->user->email }}</span>
                </div>
                @if($payment)
                <div class="detail-row">
                    <span class="label">Phone</span>
                    <span class="value">{{ $payment->phone_number }}</span>
                </div>
                @endif
            </div>

            <hr class="divider">

            {{-- Stall Details --}}
            <div class="section">
                <div class="section-title">Stall Information</div>
                <div class="detail-row">
                    <span class="label">Stall Number</span>
                    <span class="value">#{{ $booking->stall->stall_number }}</span>
                </div>
                <div class="detail-row">
                    <span class="label">Zone</span>
                    <span class="value">{{ $booking->stall->zone ?? 'Main' }}</span>
                </div>
                <div class="detail-row">
                    <span class="label">Location</span>
                    <span class="value">{{ $booking->stall->location_desc ?? 'N/A' }}</span>
                </div>
            </div>

            <hr class="divider">

            {{-- Booking Period --}}
            <div class="section">
                <div class="section-title">Booking Period</div>
                <div class="detail-row">
                    <span class="label">Booking Date</span>
                    <span class="value">{{ $booking->booking_date->format('d M Y') }}</span>
                </div>
                <div class="detail-row">
                    <span class="label">From</span>
                    <span class="value">{{ $booking->start_time->format('d M Y, H:i') }}</span>
                </div>
                <div class="detail-row">
                    <span class="label">Until</span>
                    <span class="value">{{ $booking->end_time->format('d M Y, H:i') }}</span>
                </div>
                <div class="detail-row">
                    <span class="label">Status</span>
                    <span class="value"><span class="status-badge">✓ CONFIRMED</span></span>
                </div>
            </div>

            <hr class="divider">

            {{-- Payment Info --}}
            <div class="section">
                <div class="section-title">Payment Details</div>
                @if($payment)
                <div class="detail-row">
                    <span class="label">Payment Method</span>
                    <span class="value">M-Pesa</span>
                </div>
                <div class="detail-row">
                    <span class="label">M-Pesa Ref</span>
                    <span class="value">{{ $payment->mpesa_transaction_id }}</span>
                </div>
                <div class="detail-row">
                    <span class="label">Paid At</span>
                    <span class="value">{{ $payment->confirmed_at ? $payment->confirmed_at->format('d M Y, H:i') : 'N/A' }}</span>
                </div>
                @endif
            </div>

            {{-- Amount --}}
            <div class="amount-box">
                <div class="label">Total Amount Paid</div>
                <div class="amount">
                    <span class="currency">KES</span> {{ number_format($booking->stall->price, 2) }}
                </div>
            </div>

        </div>

        {{-- Footer --}}
        <div class="receipt-footer">
            <p>
                This is a computer-generated receipt for your stall booking at Muthurwa Market.<br>
                Please keep this receipt for your records. Present it to market officers if requested.
            </p>
            <div class="timestamp">
                Generated on {{ now()->format('d M Y \a\t H:i:s') }}
            </div>
        </div>
    </div>
</body>
</html>
