@extends('layouts.app')

@section('page-title', 'Manual Stall Assignment')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    
    <!-- Header with Back Button -->
    <div class="flex items-center justify-between bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.dashboard') }}" class="p-2 bg-slate-100 hover:bg-slate-200 rounded-xl transition-all">
                ⬅️
            </a>
            <div>
                <h2 class="text-xl font-black text-slate-800 uppercase tracking-tight">New Manual Assignment</h2>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Muthurwa Market Service Desk</p>
            </div>
        </div>
        <span class="bg-indigo-100 text-indigo-700 text-[10px] font-black px-3 py-1 rounded-full uppercase">Step-by-Step Assignment</span>
    </div>

    <!-- The Assignment Form -->
    <div class="bg-white rounded-3xl shadow-xl border border-slate-100 overflow-hidden">
        <div class="p-8">
            <form action="{{ route('admin.stalls.assign.store') }}" method="POST" class="space-y-8">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    
                    <!-- 1. Select Stall -->
                    <div class="space-y-3">
                        <label class="flex items-center gap-2 text-xs font-black text-slate-700 uppercase tracking-widest">
                            <span class="w-6 h-6 bg-slate-900 text-white rounded-lg flex items-center justify-center text-[10px]">1</span>
                            Choose Available Stall
                        </label>
                        <select name="stall_id" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-4 text-sm focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all">
                            <option value="" disabled selected>Search by Stall # or Zone...</option>
                            @foreach($stalls as $stall)
                                <option value="{{ $stall->id }}">
                                    #{{ $stall->stall_number }} — {{ $stall->zone }} (KES {{ number_format($stall->price) }})
                                </option>
                            @endforeach
                        </select>
                        <p class="text-[10px] text-slate-400 italic px-2">Only stalls marked as 'Available' are listed here.</p>
                    </div>

                    <!-- 2. Select Trader -->
                    <div class="space-y-3">
                        <label class="flex items-center gap-2 text-xs font-black text-slate-700 uppercase tracking-widest">
                            <span class="w-6 h-6 bg-slate-900 text-white rounded-lg flex items-center justify-center text-[10px]">2</span>
                            Select Registered Trader
                        </label>
                        <select name="user_id" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-4 text-sm focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all">
                            <option value="" disabled selected>Search by Name or Phone...</option>
                            @foreach($traders as $trader)
                                <option value="{{ $trader->id }}">
                                    {{ $trader->name }} — {{ $trader->phone_number }}
                                </option>
                            @endforeach
                        </select>
                        <p class="text-[10px] text-slate-400 italic px-2">Traders must be registered in the system before assignment.</p>
                    </div>

                    <!-- 3. Start Time (Auto-filled but editable) -->
                    <div class="space-y-3">
                        <label class="flex items-center gap-2 text-xs font-black text-slate-700 uppercase tracking-widest">
                            <span class="w-6 h-6 bg-slate-900 text-white rounded-lg flex items-center justify-center text-[10px]">3</span>
                            Booking Start
                        </label>
                        <input type="datetime-local" name="start_time" value="{{ now()->format('Y-m-d\TH:i') }}" required 
                               class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-4 text-sm focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all">
                    </div>

                    <!-- 4. End Time -->
                    <div class="space-y-3">
                        <label class="flex items-center gap-2 text-xs font-black text-slate-700 uppercase tracking-widest">
                            <span class="w-6 h-6 bg-slate-900 text-white rounded-lg flex items-center justify-center text-[10px]">4</span>
                            Booking End
                        </label>
                        <input type="datetime-local" name="end_time" required 
                               class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-4 text-sm focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all">
                    </div>

                </div>

                <!-- Action Footer -->
                <div class="pt-8 border-t border-slate-50 flex items-center justify-end gap-4">
                    <a href="{{ route('admin.dashboard') }}" class="text-xs font-black text-slate-400 uppercase hover:text-slate-600 transition-all">Cancel</a>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-10 py-4 rounded-2xl font-black text-xs uppercase tracking-[0.2em] shadow-xl shadow-indigo-600/20 active:scale-95 transition-all">
                        Finalize Assignment
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection
