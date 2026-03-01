@extends('layouts.app')

@section('content')
<div class="min-h-[70vh] flex items-center justify-center px-4">
    <div class="max-w-md w-full bg-white rounded-3xl shadow-2xl border border-slate-100 overflow-hidden text-center p-8">
        <div class="mb-6">
            <span class="text-6xl">🚫</span>
        </div>
        
        <h1 class="text-2xl font-black text-slate-900 uppercase tracking-tight">Access Restricted</h1>
        
        <div class="my-6 p-4 bg-rose-50 rounded-2xl border border-rose-100">
            <p class="text-[10px] font-black text-rose-400 uppercase tracking-widest mb-1">Reason for {{ auth()->user()->account_restriction }}</p>
            <p class="text-rose-700 font-bold">{{ auth()->user()->restriction_reason ?? 'No specific reason provided.' }}</p>
        </div>

        <div class="text-left space-y-4 mb-8">
            <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest">How to resolve:</h3>
            <ul class="text-sm text-slate-600 space-y-2">
                <li class="flex gap-2"><span>📍</span> Visit the **Muthurwa Market Office** (Block B).</li>
                <li class="flex gap-2"><span>📞</span> Call Support: **+254 7XX XXX XXX**.</li>
                <li class="flex gap-2"><span>📄</span> Bring your **National ID** and latest **Payment Receipt**.</li>
            </ul>
        </div>

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="w-full py-3 bg-slate-900 text-white rounded-xl font-black text-xs uppercase tracking-widest hover:bg-slate-800 transition-all">
                Logout & Exit
            </button>
        </form>
    </div>
</div>
@endsection
