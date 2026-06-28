<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="Terms and Conditions — Muthurwa Market Digital Platform, Nairobi City County.">
<title>Terms & Conditions — Muthurwa Market</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;900&display=swap" rel="stylesheet">
@vite(['resources/css/app.css', 'resources/js/app.js'])
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
body{font-family:'Inter',sans-serif;background:#F8FAFC;color:#1A1A1B;line-height:1.7;}
:root{--green:#068930;--yellow:#FCDD07;--blue:#0F47AF;--red:#DC2626;--black:#1A1A1B;}

/* ── Header ── */
.tc-header{background:linear-gradient(135deg,#068930,#046122,#034d1a);padding:3rem 2rem;text-align:center;position:relative;overflow:hidden;}
.tc-header::after{content:'';position:absolute;bottom:-1px;left:0;right:0;height:40px;background:#F8FAFC;border-radius:50% 50% 0 0/100% 100% 0 0;}
.tc-badge{display:inline-block;background:rgba(252,221,7,0.15);border:1px solid rgba(252,221,7,0.4);color:#FCDD07;font-size:0.65rem;font-weight:800;letter-spacing:0.3em;text-transform:uppercase;padding:5px 14px;border-radius:999px;margin-bottom:1rem;}
.tc-header h1{color:#fff;font-size:2rem;font-weight:900;letter-spacing:-0.03em;margin-bottom:0.5rem;}
.tc-header p{color:rgba(255,255,255,0.6);font-size:0.85rem;}

/* ── Layout ── */
.tc-wrap{max-width:860px;margin:0 auto;padding:3rem 2rem 5rem;}

/* ── Table of Contents ── */
.toc{background:#fff;border:1.5px solid #E2E8F0;border-radius:1.25rem;padding:1.5rem 2rem;margin-bottom:2.5rem;box-shadow:0 2px 12px rgba(0,0,0,0.05);}
.toc h2{font-size:0.75rem;font-weight:900;text-transform:uppercase;letter-spacing:0.2em;color:var(--green);margin-bottom:1rem;}
.toc ol{padding-left:1.25rem;}
.toc li{margin-bottom:0.4rem;}
.toc a{color:var(--blue);font-weight:600;font-size:0.875rem;text-decoration:none;}
.toc a:hover{text-decoration:underline;}

/* ── Sections ── */
.tc-section{background:#fff;border-radius:1.25rem;border:1.5px solid #E2E8F0;padding:2rem 2.25rem;margin-bottom:1.5rem;box-shadow:0 2px 8px rgba(0,0,0,0.04);}
.tc-section-header{display:flex;align-items:center;gap:0.875rem;margin-bottom:1.25rem;padding-bottom:1rem;border-bottom:2px solid #F1F5F9;}
.tc-icon{width:42px;height:42px;border-radius:0.875rem;display:flex;align-items:center;justify-content:center;font-size:1.2rem;flex-shrink:0;}
.tc-section-header h2{font-size:1.05rem;font-weight:900;color:var(--black);}
.tc-section-header span{font-size:0.7rem;font-weight:700;text-transform:uppercase;letter-spacing:0.15em;color:#94A3B8;display:block;margin-top:2px;}

/* ── Content Typography ── */
.tc-section p{font-size:0.875rem;color:#374151;line-height:1.8;margin-bottom:0.875rem;}
.tc-section p:last-child{margin-bottom:0;}
.tc-section ul,.tc-section ol{padding-left:1.5rem;margin-bottom:0.875rem;}
.tc-section li{font-size:0.875rem;color:#374151;line-height:1.75;margin-bottom:0.3rem;}
.tc-section strong{color:var(--black);font-weight:700;}

/* ── Highlight Boxes ── */
.highlight-green{background:#F0FAF3;border-left:4px solid var(--green);border-radius:0 0.75rem 0.75rem 0;padding:1rem 1.25rem;margin:1rem 0;}
.highlight-red{background:#FFF5F5;border-left:4px solid var(--red);border-radius:0 0.75rem 0.75rem 0;padding:1rem 1.25rem;margin:1rem 0;}
.highlight-blue{background:#EFF6FF;border-left:4px solid var(--blue);border-radius:0 0.75rem 0.75rem 0;padding:1rem 1.25rem;margin:1rem 0;}
.highlight-yellow{background:#FFFBEB;border-left:4px solid var(--yellow);border-radius:0 0.75rem 0.75rem 0;padding:1rem 1.25rem;margin:1rem 0;}
.highlight-green p,.highlight-red p,.highlight-blue p,.highlight-yellow p{margin-bottom:0;}

/* ── Effective Date Banner ── */
.effective-banner{background:var(--black);border-radius:1rem;padding:1.25rem 2rem;margin-bottom:2rem;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:1rem;}
.effective-banner span{font-size:0.8rem;font-weight:700;color:rgba(255,255,255,0.55);text-transform:uppercase;letter-spacing:0.1em;}
.effective-banner strong{color:#FCDD07;font-size:0.875rem;}

/* ── Back / Accept Button ── */
.btn-bar{display:flex;gap:1rem;flex-wrap:wrap;margin-top:2.5rem;align-items:center;justify-content:center;}
.btn-back{display:inline-flex;align-items:center;gap:0.5rem;padding:12px 24px;border:2px solid #E2E8F0;border-radius:0.875rem;background:#fff;color:#374151;font-weight:700;font-size:0.875rem;text-decoration:none;transition:all 0.2s;cursor:pointer;}
.btn-back:hover{border-color:var(--blue);color:var(--blue);}
.btn-accept{display:inline-flex;align-items:center;gap:0.5rem;padding:14px 32px;border:none;border-radius:0.875rem;background:var(--green);color:#fff;font-weight:900;font-size:0.875rem;text-decoration:none;transition:all 0.2s;cursor:pointer;box-shadow:0 6px 20px rgba(6,137,48,0.3);}
.btn-accept:hover{transform:translateY(-2px);box-shadow:0 10px 28px rgba(6,137,48,0.4);}

/* ── Footer ── */
.tc-footer{background:var(--black);padding:2rem;text-align:center;margin-top:4rem;}
.tc-footer p{color:rgba(255,255,255,0.3);font-size:0.7rem;font-weight:600;text-transform:uppercase;letter-spacing:0.15em;line-height:1.8;}
</style>
</head>
<body>

{{-- ── Header ── --}}
<div class="tc-header">
    <div class="tc-badge">Legal Document</div>
    <h1>🦁 Terms & Conditions</h1>
    <p>Muthurwa Market Digital Platform · Nairobi City County Government</p>
</div>

{{-- ── Main Content ── --}}
<div class="tc-wrap">

    {{-- Effective Date Banner --}}
    <div class="effective-banner">
        <div>
            <span>Document Version</span>
            <strong>Version 1.0 — 2026</strong>
        </div>
        <div>
            <span>Effective Date</span>
            <strong>{{ date('d F Y') }}</strong>
        </div>
        <div>
            <span>Governing Authority</span>
            <strong>Nairobi City County Government</strong>
        </div>
    </div>

    {{-- Introduction Box --}}
    <div class="tc-section" style="border-color:#D1FAE5;">
        <p>
            Welcome to the <strong>Muthurwa Market Smart Stall Booking System</strong>, an official digital platform developed and managed by the <strong>Nairobi City County Government</strong>. By registering an account or using this platform, you agree to be legally bound by the following Terms and Conditions.
        </p>
        <p>
            Please read this document carefully. If you do not agree to these terms, you should not register or use the platform.
        </p>
        <div class="highlight-green">
            <p>✅ Your use of this platform constitutes acceptance of all terms stated herein.</p>
        </div>
    </div>

    {{-- Table of Contents --}}
    <div class="toc">
        <h2>📋 Table of Contents</h2>
        <ol>
            <li><a href="#s1">System Usage Rules</a></li>
            <li><a href="#s2">User Responsibilities</a></li>
            <li><a href="#s3">Stall Booking Policies</a></li>
            <li><a href="#s4">Violation Consequences</a></li>
            <li><a href="#s5">Data Privacy Statement</a></li>
            <li><a href="#s6">Payment & Refund Rules</a></li>
            <li><a href="#s7">Account Security</a></li>
            <li><a href="#s8">Limitation of Liability</a></li>
            <li><a href="#s9">Amendments to Terms</a></li>
            <li><a href="#s10">Contact & Governing Law</a></li>
        </ol>
    </div>

    {{-- Section 1 --}}
    <div class="tc-section" id="s1">
        <div class="tc-section-header">
            <div class="tc-icon" style="background:#F0FAF3;">📜</div>
            <div>
                <h2>1. System Usage Rules</h2>
                <span>Permitted and prohibited uses of the platform</span>
            </div>
        </div>
        <p>The Muthurwa Market Smart Stall Booking System is intended exclusively for:</p>
        <ul>
            <li>Registered traders operating within Muthurwa Market, Nairobi</li>
            <li>Market officers assigned by Nairobi City County Government</li>
            <li>System administrators authorized by the County Government</li>
        </ul>
        <p><strong>The following are strictly prohibited:</strong></p>
        <ul>
            <li>Registering multiple accounts with the same identity or phone number</li>
            <li>Using the platform for purposes other than stall booking and market management</li>
            <li>Attempting to access another user's account or booking records</li>
            <li>Submitting false or misleading information during registration or booking</li>
            <li>Using automated bots or scripts to interact with the system</li>
            <li>Exploiting any system vulnerabilities or bugs for personal gain</li>
        </ul>
        <div class="highlight-red">
            <p>⚠ Violation of any usage rule may result in immediate account suspension and referral to the County Enforcement Department.</p>
        </div>
    </div>

    {{-- Section 2 --}}
    <div class="tc-section" id="s2">
        <div class="tc-section-header">
            <div class="tc-icon" style="background:#EFF6FF;">👤</div>
            <div>
                <h2>2. User Responsibilities</h2>
                <span>What you are responsible for as a registered user</span>
            </div>
        </div>
        <p>As a registered user of this platform, you accept full responsibility for:</p>
        <ul>
            <li><strong>Account security:</strong> Keeping your login credentials confidential at all times</li>
            <li><strong>Accurate information:</strong> Providing true and up-to-date personal and contact information</li>
            <li><strong>Timely payments:</strong> Paying all stall booking fees promptly via the provided M-Pesa payment channel</li>
            <li><strong>Proper conduct:</strong> Operating your stall within market rules and county by-laws</li>
            <li><strong>Notification:</strong> Informing the Market Office of any changes to your business operations or contact details</li>
            <li><strong>Compliance:</strong> Adhering to all directives issued by market officers and county enforcement staff</li>
        </ul>
        <div class="highlight-blue">
            <p>ℹ Users are fully responsible for all actions performed under their registered account, even if performed by a third party.</p>
        </div>
    </div>

    {{-- Section 3 --}}
    <div class="tc-section" id="s3">
        <div class="tc-section-header">
            <div class="tc-icon" style="background:#FFFBEB;">🏪</div>
            <div>
                <h2>3. Stall Booking Policies</h2>
                <span>Rules governing stall reservation and occupancy</span>
            </div>
        </div>
        <p>The following policies govern the stall booking process on this platform:</p>
        <ul>
            <li><strong>One active booking per trader:</strong> A trader may hold only one active confirmed stall booking at a time</li>
            <li><strong>Availability-based:</strong> Stall bookings are granted on a first-come, first-served basis</li>
            <li><strong>Booking duration:</strong> Traders may book stalls for a minimum of 1 day and a maximum of 1 month</li>
            <li><strong>Confirmation requirement:</strong> Bookings are only confirmed after full payment via M-Pesa</li>
            <li><strong>Renewal:</strong> Traders may renew bookings before expiry, subject to stall availability</li>
            <li><strong>Cancellation:</strong> Cancellations must be made before the booking start time; fees may apply</li>
            <li><strong>Stall assignment:</strong> Specific stall allocation is subject to availability and market officer approval</li>
        </ul>
        <div class="highlight-yellow">
            <p>💡 Booked stalls that are abandoned or unused for more than 48 hours without prior notice may be reallocated by market management.</p>
        </div>
    </div>

    {{-- Section 4 --}}
    <div class="tc-section" id="s4">
        <div class="tc-section-header">
            <div class="tc-icon" style="background:#FFF5F5;">⚖️</div>
            <div>
                <h2>4. Violation Consequences</h2>
                <span>Actions taken for non-compliance with market rules</span>
            </div>
        </div>
        <p>Traders found in violation of market rules are subject to the following graduated enforcement measures:</p>
        <ol>
            <li><strong>Formal Warning:</strong> First violations typically result in a written warning notice</li>
            <li><strong>Account Restriction:</strong> Repeat violations may lead to booking restrictions or account suspension</li>
            <li><strong>Stall Revocation:</strong> Serious or repeated violations may result in permanent loss of stall allocation</li>
            <li><strong>Financial Penalty:</strong> Fines may be imposed as prescribed by Nairobi City County market by-laws</li>
            <li><strong>Legal Action:</strong> Severe violations may be referred to the Nairobi City Court for prosecution</li>
        </ol>
        <p>Violation types subject to enforcement include but are not limited to:</p>
        <ul>
            <li>Waste management failures and sanitation violations</li>
            <li>Late or non-payment of market cess</li>
            <li>Unauthorized subletting or transfer of stall</li>
            <li>Obstruction of market walkways</li>
            <li>Selling unlicensed or prohibited goods</li>
            <li>Illegal electricity connections</li>
            <li>Damage to market infrastructure</li>
        </ul>
        <div class="highlight-red">
            <p>⚠ All violation notices are issued under the authority of the Nairobi City County Government and are legally enforceable.</p>
        </div>
    </div>

    {{-- Section 5 --}}
    <div class="tc-section" id="s5">
        <div class="tc-section-header">
            <div class="tc-icon" style="background:#F0FAF3;">🔒</div>
            <div>
                <h2>5. Data Privacy Statement</h2>
                <span>How your personal information is collected and used</span>
            </div>
        </div>
        <p>The Nairobi City County Government is committed to protecting your personal data in accordance with the <strong>Kenya Data Protection Act, 2019</strong>.</p>
        <p><strong>Information we collect:</strong></p>
        <ul>
            <li>Full name, email address, and phone number (for account registration)</li>
            <li>Booking history and payment records (for service delivery)</li>
            <li>Device and browser information (for system security and diagnostics)</li>
            <li>Market activity logs (for enforcement and compliance monitoring)</li>
        </ul>
        <p><strong>How your data is used:</strong></p>
        <ul>
            <li>To process and confirm stall bookings</li>
            <li>To send booking confirmations, receipts, and notices via email</li>
            <li>To enforce market rules and generate violation reports</li>
            <li>To improve system performance and security</li>
        </ul>
        <div class="highlight-blue">
            <p>🔒 Your personal data will never be sold to third parties. It is used solely for the operation of the Muthurwa Market platform and county governance purposes.</p>
        </div>
        <p>You have the right to request access to, correction of, or deletion of your personal data by contacting the Market Office.</p>
    </div>

    {{-- Section 6 --}}
    <div class="tc-section" id="s6">
        <div class="tc-section-header">
            <div class="tc-icon" style="background:#FFFBEB;">💰</div>
            <div>
                <h2>6. Payment & Refund Rules</h2>
                <span>Payment methods, fees, and refund eligibility</span>
            </div>
        </div>
        <p><strong>Accepted Payment Method:</strong></p>
        <ul>
            <li>All payments must be made via <strong>M-Pesa</strong> through the platform's integrated payment system</li>
            <li>Cash payments at the market office are accepted only with official receipts</li>
        </ul>
        <p><strong>Fee Structure:</strong></p>
        <ul>
            <li>Stall booking fees are calculated based on duration and stall zone</li>
            <li>A tiered discount structure applies: every 7th booking day is offered at a discounted rate</li>
            <li>All fees are subject to change by the County Government with 14 days' notice</li>
        </ul>
        <p><strong>Refund Policy:</strong></p>
        <ul>
            <li><strong>Full refund:</strong> Cancellations made more than 24 hours before booking start time</li>
            <li><strong>No refund:</strong> Cancellations made less than 24 hours before the start time</li>
            <li><strong>No refund:</strong> Bookings cancelled due to violation enforcement actions</li>
            <li>Refunds, where applicable, are processed within <strong>7 working days</strong></li>
        </ul>
        <div class="highlight-yellow">
            <p>💡 Always retain your M-Pesa confirmation and digital receipt as proof of payment. Refund requests require valid payment proof.</p>
        </div>
    </div>

    {{-- Section 7 --}}
    <div class="tc-section" id="s7">
        <div class="tc-section-header">
            <div class="tc-icon" style="background:#EFF6FF;">🛡️</div>
            <div>
                <h2>7. Account Security</h2>
                <span>Your responsibilities for keeping your account safe</span>
            </div>
        </div>
        <ul>
            <li>You are solely responsible for maintaining the confidentiality of your account password</li>
            <li>Immediately notify the Market Office if you suspect unauthorized access to your account</li>
            <li>Do not share your login credentials, OTP verification codes, or M-Pesa PINs with anyone</li>
            <li>The County Government and platform staff will <strong>never</strong> ask for your password by phone or email</li>
            <li>Accounts found to have been compromised due to user negligence will not be eligible for compensation</li>
        </ul>
    </div>

    {{-- Section 8 --}}
    <div class="tc-section" id="s8">
        <div class="tc-section-header">
            <div class="tc-icon" style="background:#FFF5F5;">⚠️</div>
            <div>
                <h2>8. Limitation of Liability</h2>
                <span>Extent of the County Government's responsibility</span>
            </div>
        </div>
        <p>The Nairobi City County Government shall not be held liable for:</p>
        <ul>
            <li>Loss of business income resulting from system downtime or technical errors</li>
            <li>Incorrect information submitted by the user during registration or booking</li>
            <li>M-Pesa transaction failures caused by the mobile money provider</li>
            <li>Unauthorized account access resulting from user negligence</li>
            <li>Any indirect, consequential, or incidental damages arising from platform use</li>
        </ul>
        <div class="highlight-red">
            <p>⚠ The platform is provided "as is." The County Government makes no warranties regarding uninterrupted or error-free service.</p>
        </div>
    </div>

    {{-- Section 9 --}}
    <div class="tc-section" id="s9">
        <div class="tc-section-header">
            <div class="tc-icon" style="background:#F0FAF3;">📝</div>
            <div>
                <h2>9. Amendments to Terms</h2>
                <span>How changes to these terms are communicated</span>
            </div>
        </div>
        <p>The Nairobi City County Government reserves the right to amend these Terms and Conditions at any time. Changes will be communicated through:</p>
        <ul>
            <li>Email notification to all registered users</li>
            <li>Notice on the platform dashboard</li>
            <li>Updated version date on this page</li>
        </ul>
        <p>Continued use of the platform after amendments are published constitutes your acceptance of the updated terms.</p>
    </div>

    {{-- Section 10 --}}
    <div class="tc-section" id="s10">
        <div class="tc-section-header">
            <div class="tc-icon" style="background:#FFFBEB;">📞</div>
            <div>
                <h2>10. Contact & Governing Law</h2>
                <span>How to reach us and the law that applies</span>
            </div>
        </div>
        <p>These Terms and Conditions are governed by the laws of the <strong>Republic of Kenya</strong>, including:</p>
        <ul>
            <li>The Nairobi City County Government Act</li>
            <li>The Kenya Data Protection Act, 2019</li>
            <li>The Kenya Information and Communications Act</li>
            <li>All applicable Nairobi City County Market By-Laws</li>
        </ul>
        <p><strong>Contact the Market Office:</strong></p>
        <div class="highlight-green">
            <p>
                📍 Muthurwa Market, Market Enforcement Office, Nairobi<br>
                📞 Tel: 0710 618 973<br>
                📧 Email: info@muthurwamarket.indevs.in<br>
                🕐 Office Hours: Monday – Friday, 8:00 AM – 5:00 PM
            </p>
        </div>
    </div>

    {{-- Action Buttons --}}
    <div class="btn-bar">
        @if(url()->previous() && str_contains(url()->previous(), 'register'))
            <a href="{{ url()->previous() }}" class="btn-back">
                ← Back to Registration
            </a>
            <a href="{{ url()->previous() }}" class="btn-accept">
                ✅ I Accept — Return to Registration
            </a>
        @else
            <a href="{{ route('register') }}" class="btn-accept">
                ← Register an Account
            </a>
        @endif
    </div>

</div>

{{-- ── Footer ── --}}
<div class="tc-footer">
    <p>
        © {{ date('Y') }} Nairobi City County Government · Muthurwa Market Digital Platform<br>
        Document Reference: MUTH-TC-V1-{{ date('Y') }} · All rights reserved
    </p>
</div>

{{-- Smooth scroll --}}
<script>
document.querySelectorAll('a[href^="#"]').forEach(a => {
    a.addEventListener('click', e => {
        e.preventDefault();
        const target = document.querySelector(a.getAttribute('href'));
        if (target) target.scrollIntoView({ behavior: 'smooth', block: 'start' });
    });
});
</script>
</body>
</html>
