@extends('layouts.app')

@section('page-title', 'Report Violation')

@section('content')
<div class="space-y-6">

    {{-- ── Progress Steps ─────────────────────────────────────────── --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
        <div class="flex items-center justify-between max-w-2xl mx-auto">
            <div class="flex items-center gap-2" id="step1indicator">
                <span class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-black text-white" style="background:#1E5128;">1</span>
                <span class="text-xs font-black uppercase tracking-wider" style="color:#1E5128;">Identify Trader</span>
            </div>
            <div class="flex-1 h-0.5 mx-3 rounded" style="background:#e2e8f0;" id="prog1">
                <div class="h-full rounded transition-all duration-500" style="background:#1E5128;width:0%;" id="prog1fill"></div>
            </div>
            <div class="flex items-center gap-2" id="step2indicator">
                <span class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-black border-2" style="color:#94a3b8;border-color:#e2e8f0;background:#f8fafc;" id="step2dot">2</span>
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400" id="step2label">Log Violation</span>
            </div>
            <div class="flex-1 h-0.5 mx-3 rounded" style="background:#e2e8f0;">
                <div class="h-full rounded transition-all duration-500" style="background:#1E5128;width:0%;" id="prog2fill"></div>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-black border-2" style="color:#94a3b8;border-color:#e2e8f0;background:#f8fafc;" id="step3dot">3</span>
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400" id="step3label">Generate Notice</span>
            </div>
        </div>
    </div>

    {{-- ── Hero Banner ────────────────────────────────────────────── --}}
    <div class="relative overflow-hidden rounded-2xl shadow-lg" style="background:linear-gradient(135deg,#132a13,#1E5128,#2d6a2e);">
        <div class="absolute top-0 left-0 right-0 h-1" style="background:linear-gradient(90deg,#1E5128 0%,#1E5128 33%,#D4A373 33%,#D4A373 66%,#1E5128 66%);"></div>
        {{-- Skyline watermark --}}
        <div class="absolute bottom-0 right-0 opacity-[0.04] pointer-events-none" style="font-size:180px;line-height:1;font-weight:900;color:white;letter-spacing:-8px;">🏙️</div>
        <div class="p-6 flex flex-col md:flex-row md:items-center justify-between gap-4 relative z-10">
            <div class="flex items-center gap-4">
                <div style="width:48px;height:48px;border-radius:14px;background:rgba(212,163,115,.2);border:1px solid rgba(212,163,115,.4);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <svg style="width:24px;height:24px;" fill="none" stroke="#D4A373" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-black text-white tracking-tight" style="font-family:'Montserrat',sans-serif;">Report Violation</h2>
                    <p style="color:rgba(255,255,255,.55);font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;">
                        Official Enforcement Entry · Nairobi City County
                    </p>
                </div>
            </div>
            {{-- Officer Stats --}}
            <div class="flex gap-2">
                <div style="background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.2);border-radius:.75rem;padding:.5rem 1rem;text-align:center;">
                    <span class="text-white font-black text-lg tabular-nums">{{ $violationsToday }}</span>
                    <p style="color:#D4A373;font-size:.55rem;font-weight:900;text-transform:uppercase;letter-spacing:.08em;margin:0;">Today</p>
                </div>
                <div style="background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.2);border-radius:.75rem;padding:.5rem 1rem;text-align:center;">
                    <span class="text-white font-black text-lg tabular-nums">{{ $violationsThisWeek }}</span>
                    <p style="color:#D4A373;font-size:.55rem;font-weight:900;text-transform:uppercase;letter-spacing:.08em;margin:0;">This Week</p>
                </div>
                <div style="background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.2);border-radius:.75rem;padding:.5rem 1rem;text-align:center;">
                    <span class="text-white font-black text-lg tabular-nums">{{ $violationsTotal }}</span>
                    <p style="color:#D4A373;font-size:.55rem;font-weight:900;text-transform:uppercase;letter-spacing:.08em;margin:0;">All Time</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="flex items-center gap-3 p-4 rounded-2xl border" style="background:#f0fdf4;border-color:#86efac;">
            <span class="text-xl">✅</span>
            <p class="text-sm font-bold" style="color:#166534;">{{ session('success') }}</p>
        </div>
    @endif
    @if($errors->any())
        <div class="p-4 rounded-2xl border" style="background:#fef2f2;border-color:#fca5a5;">
            <div class="flex items-center gap-2 mb-2">
                <span class="text-lg">⚠️</span>
                <strong class="text-sm font-black uppercase" style="color:#991b1b;">Correction Required</strong>
            </div>
            <ul class="list-disc list-inside text-sm font-medium" style="color:#b91c1c;">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    {{-- ── Main Form ──────────────────────────────────────────────── --}}
    <form method="POST" action="{{ route('officer.violations.store') }}" enctype="multipart/form-data" id="violationForm">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- ══ LEFT COLUMN (2/3) ══════════════════════════════ --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- Card 1: Trader Information --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden vio-card transition-shadow hover:shadow-md">
                    <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-2" style="background:linear-gradient(90deg,#f0f7f0,#fff);">
                        <div class="w-1 h-6 rounded-full" style="background:#1E5128;"></div>
                        <h3 class="text-sm font-black uppercase tracking-tight" style="color:#1E5128;">
                            Step 1 · Trader Information
                        </h3>
                    </div>
                    <div class="p-6">
                        <label class="text-[11px] font-bold uppercase tracking-widest ml-1 mb-2 block" style="color:#1E5128;">
                            Select Active Trader / Occupant
                        </label>
                        <select name="trader_id" id="trader_select" required
                                class="w-full border-2 rounded-xl px-4 py-3 text-sm font-bold focus:outline-none transition-all vio-input"
                                style="border-color:#e2e8f0;background:#f8fafc;">
                            <option value="">— Select Occupant —</option>
                            @foreach($activeTraders as $trader)
                                <option value="{{ $trader->user_id }}">
                                    Stall #{{ $trader->stall_number }} — {{ strtoupper($trader->trader_name) }}
                                </option>
                            @endforeach
                        </select>
                        <p class="text-[10px] text-slate-400 mt-2 px-1 italic">Only traders with active or recent bookings are listed.</p>
                    </div>
                </div>

                {{-- Card 2: Violation Details --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden vio-card transition-shadow hover:shadow-md">
                    <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-2" style="background:linear-gradient(90deg,#f0f7f0,#fff);">
                        <div class="w-1 h-6 rounded-full" style="background:#1E5128;"></div>
                        <h3 class="text-sm font-black uppercase tracking-tight" style="color:#1E5128;">
                            Step 2 · Violation Details
                        </h3>
                    </div>
                    <div class="p-6 space-y-5">

                        {{-- Quick Templates --}}
                        <div>
                            <label class="text-[11px] font-bold uppercase tracking-widest ml-1 mb-2 block" style="color:#1E5128;">Quick Templates</label>
                            <div class="flex flex-wrap gap-2" id="quickTemplates">
                                <button type="button" class="tpl-btn px-3 py-1.5 rounded-lg text-[10px] font-bold uppercase border transition-all hover:scale-105 active:scale-95"
                                        style="border-color:#D4A373;color:#92400e;background:#fffbeb;"
                                        data-type="Waste Management" data-notes="Trader observed disposing waste improperly near stall area. Waste bins not utilized. Immediate cleanup required.">
                                    🗑️ Waste Management
                                </button>
                                <button type="button" class="tpl-btn px-3 py-1.5 rounded-lg text-[10px] font-bold uppercase border transition-all hover:scale-105 active:scale-95"
                                        style="border-color:#D4A373;color:#92400e;background:#fffbeb;"
                                        data-type="Obstructing Walkway" data-notes="Goods and merchandise placed outside designated stall area, obstructing the main market walkway. Fire hazard and pedestrian obstruction.">
                                    🚧 Obstructing Walkway
                                </button>
                                <button type="button" class="tpl-btn px-3 py-1.5 rounded-lg text-[10px] font-bold uppercase border transition-all hover:scale-105 active:scale-95"
                                        style="border-color:#D4A373;color:#92400e;background:#fffbeb;"
                                        data-type="Food Hygiene Violation" data-notes="Food items found stored improperly. Hygiene standards not maintained as required under Public Health Act regulations.">
                                    🍽️ Food Hygiene
                                </button>
                                <button type="button" class="tpl-btn px-3 py-1.5 rounded-lg text-[10px] font-bold uppercase border transition-all hover:scale-105 active:scale-95"
                                        style="border-color:#D4A373;color:#92400e;background:#fffbeb;"
                                        data-type="Unauthorized Stall Use" data-notes="Stall found being used for activities not authorized under the original booking terms. Unauthorized goods or services observed.">
                                    🚫 Unauthorized Use
                                </button>
                                <button type="button" class="tpl-btn px-3 py-1.5 rounded-lg text-[10px] font-bold uppercase border transition-all hover:scale-105 active:scale-95"
                                        style="border-color:#D4A373;color:#92400e;background:#fffbeb;"
                                        data-type="Encroaching Stall Space" data-notes="Trader has expanded operations beyond allocated stall boundaries, encroaching on adjacent trader's space.">
                                    📐 Encroaching Space
                                </button>
                                <button type="button" class="tpl-btn px-3 py-1.5 rounded-lg text-[10px] font-bold uppercase border transition-all hover:scale-105 active:scale-95"
                                        style="border-color:#D4A373;color:#92400e;background:#fffbeb;"
                                        data-type="Noise Violation" data-notes="Excessive noise emanating from stall area disturbing other traders and market operations. Sound levels exceed acceptable market guidelines.">
                                    🔊 Noise Violation
                                </button>
                            </div>
                        </div>

                        {{-- Violation Type --}}
                        <div>
                            <label class="text-[11px] font-bold uppercase tracking-widest ml-1 mb-2 block" style="color:#1E5128;">Violation Type</label>
                            <select name="violation_type" id="violation_type"
                                    class="w-full border-2 rounded-xl px-4 py-3 text-sm font-bold focus:outline-none transition-all vio-input"
                                    style="border-color:#e2e8f0;background:#f8fafc;">
                                <option value="Waste Management">Waste Management (Improper disposal)</option>
                                <option value="Late Payment">Late Payment of Stall Fees</option>
                                <option value="Unauthorized Stall Use">Unauthorized Stall Use</option>
                                <option value="Subletting Stall">Subletting Stall Without Approval</option>
                                <option value="Obstructing Walkway">Obstructing Market Walkway</option>
                                <option value="Encroaching Stall Space">Encroaching on Another's Space</option>
                                <option value="Selling Unlicensed Goods">Selling Unlicensed Goods</option>
                                <option value="Food Hygiene Violation">Food Hygiene Violation</option>
                                <option value="Noise Violation">Noise Disturbance</option>
                                <option value="Illegal Electricity Connection">Illegal Electricity Connection</option>
                                <option value="Damage to Market Property">Damage to Market Infrastructure</option>
                            </select>
                        </div>

                        {{-- Officer Notes --}}
                        <div>
                            <label class="text-[11px] font-bold uppercase tracking-widest ml-1 mb-2 block" style="color:#1E5128;">Officer Notes</label>
                            <textarea name="officer_notes" id="officer_notes" rows="5" required
                                      class="w-full border-2 rounded-xl px-4 py-3 text-sm font-medium focus:outline-none transition-all resize-none vio-input"
                                      style="border-color:#e2e8f0;background:#f8fafc;"
                                      placeholder="Describe the violation in detail..."></textarea>
                            <p class="text-[10px] text-slate-400 mt-1 px-1 italic">Be specific — this feeds into the official notice.</p>
                        </div>
                    </div>
                </div>

                {{-- Card 3: Evidence Upload --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden vio-card transition-shadow hover:shadow-md">
                    <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-2" style="background:linear-gradient(90deg,#f0f7f0,#fff);">
                        <div class="w-1 h-6 rounded-full" style="background:#1E5128;"></div>
                        <h3 class="text-sm font-black uppercase tracking-tight" style="color:#1E5128;">
                            Evidence Upload
                        </h3>
                        <span class="text-[9px] font-bold text-slate-400 uppercase ml-auto">Optional</span>
                    </div>
                    <div class="p-6">
                        <div id="dropZone" class="border-2 border-dashed rounded-2xl p-8 text-center transition-all cursor-pointer"
                             style="border-color:#d1d5db;background:#fafafa;">
                            <div id="uploadPlaceholder">
                                <svg class="w-10 h-10 mx-auto mb-3" style="color:#D4A373;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <p class="text-sm font-bold text-slate-600">Click to upload or drag photo here</p>
                                <p class="text-[10px] text-slate-400 mt-1">JPG or PNG · Max 4MB</p>
                            </div>
                            <div id="previewArea" class="hidden">
                                <img id="previewImg" class="max-h-48 mx-auto rounded-xl shadow-md border-2" style="border-color:#D4A373;" alt="Preview">
                                <p id="previewName" class="text-xs font-bold text-slate-600 mt-3"></p>
                                <button type="button" id="removePhoto" class="mt-2 text-[10px] font-bold uppercase text-rose-600 hover:text-rose-800 transition-colors">
                                    ✕ Remove Photo
                                </button>
                            </div>
                        </div>
                        <input type="file" name="photo" id="photoInput" accept="image/jpeg,image/png" class="hidden">
                    </div>
                </div>
            </div>

            {{-- ══ RIGHT COLUMN (1/3) — Action Panel ══════════════ --}}
            <div class="lg:col-span-1 space-y-6">

                {{-- Action Card --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden sticky top-24 vio-card transition-shadow hover:shadow-md">
                    <div class="px-6 py-4 border-b border-gray-100" style="background:linear-gradient(135deg,#1E5128,#2d6a2e);">
                        <h3 class="text-sm font-black uppercase tracking-tight text-white">Generate Notice</h3>
                        <p class="text-[9px] font-bold uppercase tracking-wider mt-1" style="color:#D4A373;">Step 3 · Final Action</p>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="p-4 rounded-xl" style="background:#faf7f2;border:1px solid #e8ddd0;">
                            <p class="text-xs font-bold" style="color:#78350f;">
                                📋 A formal violation notice will be generated with legal references. You can preview, sign, and email it.
                            </p>
                        </div>

                        <button type="submit" id="submitBtn"
                                class="w-full py-4 rounded-xl font-black text-sm uppercase tracking-wider text-white transition-all hover:-translate-y-1 active:scale-95 flex items-center justify-center gap-2"
                                style="background:linear-gradient(135deg,#b8860b,#D4A373);box-shadow:0 6px 20px rgba(212,163,115,.4);">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <span id="btnText">Generate Letter</span>
                        </button>

                        {{-- Loading state (hidden by default) --}}
                        <div id="loadingState" class="hidden text-center py-4">
                            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-xl" style="background:#f0fdf4;border:1px solid #bbf7d0;">
                                <svg class="w-4 h-4 animate-spin" style="color:#1E5128;" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                </svg>
                                <span class="text-xs font-bold" style="color:#1E5128;">Generating notice...</span>
                            </div>
                        </div>

                        <p class="text-[9px] text-slate-400 text-center italic">
                            This will draft a legal notice referencing applicable Nairobi County bylaws.
                        </p>
                    </div>

                    <div class="h-1" style="background:linear-gradient(90deg,#1E5128,#D4A373,#1E5128);"></div>
                </div>

                {{-- Back link --}}
                <a href="{{ route('officer.dashboard') }}"
                   class="flex items-center justify-center gap-2 text-xs font-bold uppercase tracking-wider transition-all hover:gap-3"
                   style="color:#94a3b8;">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    Back to Dashboard
                </a>
            </div>
        </div>
    </form>

    {{-- Footer --}}
    <p class="text-center text-[10px] font-bold uppercase tracking-[.2em] pb-2" style="color:#94a3b8;">
        Market Enforcement Office · Muthurwa Market · Nairobi City County
    </p>
</div>

{{-- ── Google Font: Montserrat ────────────────────────────────── --}}
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;900&display=swap" rel="stylesheet">

<style>
    .vio-input:focus {
        border-color: #1E5128 !important;
        box-shadow: 0 0 0 3px rgba(30,81,40,.12) !important;
    }
    .vio-card { transition: box-shadow .3s ease; }
    .tpl-btn:hover { background: #D4A373 !important; color: #fff !important; }
    .tpl-btn.active { background: #1E5128 !important; color: #fff !important; border-color: #1E5128 !important; }
    #dropZone:hover, #dropZone.dragover { border-color: #D4A373 !important; background: #faf7f2 !important; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // ── Quick Templates ──
    document.querySelectorAll('.tpl-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.tpl-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            document.getElementById('violation_type').value = this.dataset.type;
            document.getElementById('officer_notes').value = this.dataset.notes;
            // Update progress
            updateProgress();
        });
    });

    // ── Photo Upload & Preview ──
    var dropZone = document.getElementById('dropZone');
    var photoInput = document.getElementById('photoInput');
    var previewArea = document.getElementById('previewArea');
    var placeholder = document.getElementById('uploadPlaceholder');
    var previewImg = document.getElementById('previewImg');
    var previewName = document.getElementById('previewName');

    dropZone.addEventListener('click', function() { photoInput.click(); });
    dropZone.addEventListener('dragover', function(e) { e.preventDefault(); this.classList.add('dragover'); });
    dropZone.addEventListener('dragleave', function() { this.classList.remove('dragover'); });
    dropZone.addEventListener('drop', function(e) {
        e.preventDefault(); this.classList.remove('dragover');
        if (e.dataTransfer.files.length) { photoInput.files = e.dataTransfer.files; showPreview(e.dataTransfer.files[0]); }
    });
    photoInput.addEventListener('change', function() { if (this.files[0]) showPreview(this.files[0]); });

    document.getElementById('removePhoto').addEventListener('click', function(e) {
        e.stopPropagation(); photoInput.value = '';
        previewArea.classList.add('hidden'); placeholder.classList.remove('hidden');
    });

    function showPreview(file) {
        var reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            previewName.textContent = file.name + ' (' + (file.size / 1024).toFixed(0) + ' KB)';
            placeholder.classList.add('hidden'); previewArea.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    }

    // ── Progress Indicator ──
    document.getElementById('trader_select').addEventListener('change', updateProgress);
    document.getElementById('violation_type').addEventListener('change', updateProgress);
    document.getElementById('officer_notes').addEventListener('input', updateProgress);

    function updateProgress() {
        var traderDone = document.getElementById('trader_select').value !== '';
        var notesDone = document.getElementById('officer_notes').value.trim().length > 10;
        document.getElementById('prog1fill').style.width = traderDone ? '100%' : '0%';
        if (traderDone) {
            document.getElementById('step2dot').style.background = '#1E5128';
            document.getElementById('step2dot').style.color = '#fff';
            document.getElementById('step2dot').style.borderColor = '#1E5128';
            document.getElementById('step2label').style.color = '#1E5128';
        }
        document.getElementById('prog2fill').style.width = (traderDone && notesDone) ? '100%' : '0%';
        if (traderDone && notesDone) {
            document.getElementById('step3dot').style.background = '#1E5128';
            document.getElementById('step3dot').style.color = '#fff';
            document.getElementById('step3dot').style.borderColor = '#1E5128';
            document.getElementById('step3label').style.color = '#1E5128';
        }
    }

    // ── Submit Loading State ──
    document.getElementById('violationForm').addEventListener('submit', function() {
        document.getElementById('submitBtn').style.display = 'none';
        document.getElementById('loadingState').classList.remove('hidden');
    });
});
</script>
@endsection