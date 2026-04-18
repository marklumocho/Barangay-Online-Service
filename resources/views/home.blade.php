<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barangay Connect | Services & Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .glass { background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(12px); }
        .tab-btn { transition: all 0.2s; }
        .tab-btn.active { background: #1d4ed8; color: #fff; }
        .tab-btn:not(.active) { background: #f1f5f9; color: #64748b; }
    </style>
</head>
<body class="bg-[#f8fafc] min-h-screen pb-12">

    <nav class="sticky top-0 z-50 glass border-b border-slate-200 px-6 py-4">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="bg-blue-700 text-white p-2 rounded-xl shadow-lg shadow-blue-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-10V4a1 1 0 011-1h2a1 1 0 011 1v3M12 7h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <span class="font-extrabold text-xl tracking-tighter text-slate-800">Barangay Connect</span>
            </div>
            <div class="flex items-center gap-4">
                @auth
                    <div class="hidden md:block text-right border-r border-slate-200 pr-4 mr-2">
                        <p class="text-xs font-bold text-slate-900 uppercase">{{ Auth::user()->name }}</p>
                        <p class="text-[10px] text-blue-600 font-black tracking-widest uppercase">
                            {{ Auth::user()->role === 'staff' ? 'Staff Officer' : 'Resident ID: ' . (Auth::user()->resident_id ?? 'N/A') }}
                        </p>
                    </div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-white border border-slate-200 text-slate-600 px-4 py-2 rounded-xl text-xs font-bold hover:bg-red-50 hover:text-red-600 transition-all">
                            Logout
                        </button>
                    </form>
                @endauth
                @guest
                    <a href="{{ route('login') }}" class="text-xs font-bold text-slate-600 px-4 py-2 hover:bg-slate-100 rounded-xl transition-all">Login</a>
                    <a href="{{ route('register') }}" class="text-xs font-bold text-white bg-blue-700 px-4 py-2 rounded-xl hover:bg-blue-800 transition-all">Create Account</a>
                @endguest
            </div>
        </div>
    </nav>

    <main class="max-w-6xl mx-auto mt-10 px-6">

        {{-- Alerts --}}
        @if($errors->has('not_resident'))
            <div class="max-w-3xl mx-auto mb-6 bg-red-50 border border-red-200 text-red-700 font-semibold text-sm px-6 py-4 rounded-2xl flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                </svg>
                {{ $errors->first('not_resident') }}
            </div>
        @endif

        @if($errors->has('duplicate_resident'))
            <div id="err-alert" class="mb-6 bg-red-50 border border-red-200 text-red-700 font-semibold text-sm px-6 py-4 rounded-2xl flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                </svg>
                {{ $errors->first('duplicate_resident') }}
            </div>
        @endif

        @if($errors->has('staff_error'))
            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 font-semibold text-sm px-6 py-4 rounded-2xl flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                </svg>
                {{ $errors->first('staff_error') }}
            </div>
        @endif

        @if(session('success'))
            <div id="flash-toast" class="fixed bottom-8 left-1/2 -translate-x-1/2 z-50 bg-slate-800 text-white text-sm font-bold px-6 py-4 rounded-2xl shadow-xl">
                {{ session('success') }}
            </div>
            <script>setTimeout(() => document.getElementById('flash-toast')?.remove(), 4000);</script>
        @endif

        @auth
            @if(Auth::user()->role === 'staff')

                {{-- ===== STAFF DASHBOARD ===== --}}
                <div class="space-y-8">
                    <header class="flex flex-col md:flex-row md:items-end justify-between gap-6">
                        <div>
                            <h1 class="text-4xl font-black text-slate-900 tracking-tight">Administrative Panel</h1>
                            <p class="text-slate-500 font-medium mt-1 text-lg">Manage applications, residents, and staff.</p>
                        </div>
                        <div class="flex gap-3 flex-wrap">
                            <div class="bg-white p-5 rounded-[2rem] shadow-sm border border-slate-200 flex items-center gap-4">
                                <div class="bg-blue-100 p-3 rounded-2xl text-blue-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Approved</p>
                                    <p class="text-xl font-extrabold text-slate-800">{{ $approvedCount ?? 0 }}</p>
                                </div>
                            </div>
                            <div class="bg-white p-5 rounded-[2rem] shadow-sm border border-slate-200 flex items-center gap-4">
                                <div class="bg-emerald-100 p-3 rounded-2xl text-emerald-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Ready</p>
                                    <p class="text-xl font-extrabold text-slate-800">{{ $readyCount ?? 0 }}</p>
                                </div>
                            </div>
                            <div class="bg-white p-5 rounded-[2rem] shadow-sm border border-slate-200 flex items-center gap-4">
                                <div class="bg-violet-100 p-3 rounded-2xl text-violet-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Residents</p>
                                    <p class="text-xl font-extrabold text-slate-800">{{ $residentCount ?? 0 }}</p>
                                </div>
                            </div>
                            <div class="bg-white p-5 rounded-[2rem] shadow-sm border border-slate-200 flex items-center gap-4">
                                <div class="bg-amber-100 p-3 rounded-2xl text-amber-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Staff</p>
                                    <p class="text-xl font-extrabold text-slate-800">{{ $staffCount ?? 0 }}</p>
                                </div>
                            </div>
                        </div>
                    </header>

                    {{-- Tab Buttons --}}
                    <div class="flex gap-2 flex-wrap">
                        <button onclick="switchTab('applications')" id="tab-applications"
                            class="tab-btn active px-6 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest">
                            Applications
                        </button>
                        <button onclick="switchTab('registry')" id="tab-registry"
                            class="tab-btn px-6 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest">
                            Resident Registry
                        </button>
                        <button onclick="switchTab('staff')" id="tab-staff"
                            class="tab-btn px-6 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest">
                            Staff Accounts
                        </button>
                    </div>

                    {{-- Applications Tab --}}
                    <div id="panel-applications">
                        <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
                            <table class="w-full text-left">
                                <thead>
                                    <tr class="bg-slate-50 border-b border-slate-100">
                                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Resident / ID</th>
                                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Requested Document</th>
                                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Status</th>
                                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50">
                                    @forelse($applications ?? [] as $app)
                                        <tr class="group transition-all hover:bg-slate-50/50">
                                            <td class="px-8 py-6">
                                                <p class="font-extrabold text-slate-800">{{ $app->resident_name }}</p>
                                                <p class="text-[10px] font-bold text-blue-600 tracking-wider">ID: {{ $app->resident_id }}</p>
                                            </td>
                                            <td class="px-8 py-6">
                                                <p class="text-sm font-bold text-slate-700">{{ $app->document_type }}</p>
                                                <p class="text-[11px] text-slate-400 mt-0.5 italic">"{{ $app->purpose }}"</p>
                                            </td>
                                            <td class="px-8 py-6">
                                                @if($app->status === 'approved')
                                                    <span class="px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-widest bg-blue-100 text-blue-600 border border-blue-200/50">Approved</span>
                                                @else
                                                    <span class="px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-widest bg-emerald-100 text-emerald-600 border border-emerald-200/50">Ready to Pick Up</span>
                                                @endif
                                            </td>
                                            <td class="px-8 py-6">
                                                <div class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-all">
                                                    @if($app->status === 'approved')
                                                        <form action="{{ route('applications.ready', $app->id) }}" method="POST">
                                                            @csrf @method('PATCH')
                                                            <button type="submit" class="flex items-center gap-1.5 px-3 py-2 bg-emerald-50 text-emerald-600 rounded-xl hover:bg-emerald-600 hover:text-white transition-all text-[10px] font-black uppercase tracking-wider">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8" />
                                                                </svg>
                                                                Ready to Pick Up
                                                            </button>
                                                        </form>
                                                    @endif
                                                    <form action="{{ route('applications.destroy', $app->id) }}" method="POST" onsubmit="return confirm('Delete this application permanently?')">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="p-2.5 bg-red-50 text-red-500 rounded-xl hover:bg-red-500 hover:text-white transition-all">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-8 py-12 text-center text-slate-400 font-semibold">No applications found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Resident Registry Tab --}}
                    <div id="panel-registry" class="hidden space-y-6">

                        {{-- Add Resident Form --}}
                        <div class="bg-white p-8 rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100">
                            <h2 class="text-lg font-black text-slate-800 mb-6">Add New Resident to Registry</h2>
                            <form action="{{ route('residents.store') }}" method="POST">
                                @csrf
                                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                    <div class="md:col-span-2 space-y-2">
                                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">First Name</label>
                                        <input type="text" name="first_name" value="{{ old('first_name') }}" required
                                            class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl outline-none focus:ring-2 focus:ring-blue-500 font-semibold">
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1 block text-center">M.I.</label>
                                        <input type="text" name="middle_initial" maxlength="1" value="{{ old('middle_initial') }}"
                                            class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl outline-none focus:ring-2 focus:ring-blue-500 font-semibold text-center uppercase">
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Last Name</label>
                                        <input type="text" name="last_name" value="{{ old('last_name') }}" required
                                            class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl outline-none focus:ring-2 focus:ring-blue-500 font-semibold">
                                    </div>
                                </div>
                                <div class="mt-4 space-y-2">
                                    <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Home Address</label>
                                    <input type="text" name="address" value="{{ old('address') }}" placeholder="e.g. Blk 1 Lot 2, Sampaguita St."
                                        class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl outline-none focus:ring-2 focus:ring-blue-500 font-semibold" required>
                                </div>
                                <div class="mt-6 flex justify-end">
                                    <button type="submit"
                                        class="bg-blue-700 hover:bg-blue-800 text-white px-8 py-4 rounded-2xl font-black uppercase tracking-widest text-xs transition-all shadow-lg shadow-blue-100 active:scale-95">
                                        + Add Resident
                                    </button>
                                </div>
                            </form>
                        </div>

                        {{-- Search Bar --}}
                        <form method="GET" action="/" class="flex gap-3">
                            <input type="hidden" name="tab" value="registry">
                            <div class="relative flex-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 absolute left-4 top-1/2 -translate-y-1/2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Search resident by name..."
                                    class="w-full pl-12 pr-5 py-4 bg-white border border-slate-200 rounded-2xl outline-none focus:ring-2 focus:ring-blue-500 font-semibold text-slate-700">
                            </div>
                            <button type="submit" class="bg-blue-700 hover:bg-blue-800 text-white px-6 py-4 rounded-2xl font-black text-xs uppercase tracking-widest transition-all">
                                Search
                            </button>
                            @if($search ?? false)
                                <a href="/?tab=registry" class="bg-slate-100 hover:bg-slate-200 text-slate-600 px-6 py-4 rounded-2xl font-black text-xs uppercase tracking-widest transition-all flex items-center">
                                    Clear
                                </a>
                            @endif
                        </form>

                        @if($search ?? false)
                            <p class="text-sm font-semibold text-slate-500">
                                Showing results for <span class="text-blue-600 font-black">"{{ $search }}"</span>
                                — {{ $residents->count() }} found
                            </p>
                        @endif

                        {{-- Residents Table --}}
                        <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
                            <table class="w-full text-left">
                                <thead>
                                    <tr class="bg-slate-50 border-b border-slate-100">
                                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Resident ID</th>
                                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Full Name</th>
                                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Address</th>
                                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50">
                                    @forelse($residents ?? [] as $res)
                                        <tr class="group transition-all hover:bg-slate-50/50">
                                            <td class="px-8 py-5">
                                                <span class="text-[11px] font-black text-blue-600 tracking-widest bg-blue-50 px-3 py-1.5 rounded-lg">{{ $res->resident_id }}</span>
                                            </td>
                                            <td class="px-8 py-5">
                                                <p class="font-extrabold text-slate-800">
                                                    {{ $res->first_name }}
                                                    {{ $res->middle_initial ? strtoupper($res->middle_initial) . '.' : '' }}
                                                    {{ $res->last_name }}
                                                </p>
                                            </td>
                                            <td class="px-8 py-5">
                                                <p class="text-sm text-slate-500 font-medium">{{ $res->address ?? '—' }}</p>
                                            </td>
                                            <td class="px-8 py-5">
                                                <div class="flex justify-end opacity-0 group-hover:opacity-100 transition-all">
                                                    <form action="{{ route('residents.destroy', $res->id) }}" method="POST" onsubmit="return confirm('Remove this resident from the registry?')">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="p-2.5 bg-red-50 text-red-500 rounded-xl hover:bg-red-500 hover:text-white transition-all">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-8 py-12 text-center text-slate-400 font-semibold">
                                                {{ ($search ?? false) ? 'No residents found matching "' . $search . '".' : 'No residents in the registry yet.' }}
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Staff Accounts Tab --}}
                    <div id="panel-staff" class="hidden space-y-6">

                        {{-- Create Staff Form --}}
                        <div class="bg-white p-8 rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100">
                            <h2 class="text-lg font-black text-slate-800 mb-6">Create Staff Account</h2>
                            <form action="{{ route('staff.store') }}" method="POST">
                                @csrf
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="space-y-2">
                                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">First Name</label>
                                        <input type="text" name="first_name" value="{{ old('first_name') }}" required
                                            class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl outline-none focus:ring-2 focus:ring-blue-500 font-semibold">
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Last Name</label>
                                        <input type="text" name="last_name" value="{{ old('last_name') }}" required
                                            class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl outline-none focus:ring-2 focus:ring-blue-500 font-semibold">
                                    </div>
                                </div>
                                <div class="mt-4 space-y-2">
                                    <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Email</label>
                                    <input type="email" name="email" value="{{ old('email') }}" required
                                        class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl outline-none focus:ring-2 focus:ring-blue-500 font-semibold">
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                                    <div class="space-y-2">
                                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Password</label>
                                        <div class="relative">
                                            <input type="password" name="password" id="staff_password" required
                                                class="w-full px-5 py-4 pr-14 bg-slate-50 border-none rounded-2xl outline-none focus:ring-2 focus:ring-blue-500 font-semibold">
                                            <button type="button" onclick="togglePassword('staff_password', 'eye-staff')"
                                                class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-700 transition-colors">
                                                <svg id="eye-staff" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Confirm Password</label>
                                        <div class="relative">
                                            <input type="password" name="password_confirmation" id="staff_password_confirm" required
                                                class="w-full px-5 py-4 pr-14 bg-slate-50 border-none rounded-2xl outline-none focus:ring-2 focus:ring-blue-500 font-semibold">
                                            <button type="button" onclick="togglePassword('staff_password_confirm', 'eye-staff-confirm')"
                                                class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-700 transition-colors">
                                                <svg id="eye-staff-confirm" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-6 flex justify-end">
                                    <button type="submit"
                                        class="bg-blue-700 hover:bg-blue-800 text-white px-8 py-4 rounded-2xl font-black uppercase tracking-widest text-xs transition-all shadow-lg shadow-blue-100 active:scale-95">
                                        + Create Staff Account
                                    </button>
                                </div>
                            </form>
                        </div>

                        {{-- Staff List --}}
                        <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
                            <table class="w-full text-left">
                                <thead>
                                    <tr class="bg-slate-50 border-b border-slate-100">
                                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Name</th>
                                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Email</th>
                                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Added</th>
                                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50">
                                    @forelse($staffAccounts ?? [] as $staff)
                                        <tr class="group transition-all hover:bg-slate-50/50">
                                            <td class="px-8 py-5">
                                                <p class="font-extrabold text-slate-800">{{ $staff->name }}</p>
                                                @if($staff->id === Auth::id())
                                                    <span class="text-[10px] font-black text-blue-500 bg-blue-50 px-2 py-0.5 rounded-lg">You</span>
                                                @endif
                                            </td>
                                            <td class="px-8 py-5">
                                                <p class="text-sm text-slate-500 font-medium">{{ $staff->email }}</p>
                                            </td>
                                            <td class="px-8 py-5">
                                                <p class="text-sm text-slate-400 font-medium">{{ $staff->created_at->format('M d, Y') }}</p>
                                            </td>
                                            <td class="px-8 py-5">
                                                <div class="flex justify-end opacity-0 group-hover:opacity-100 transition-all">
                                                    @if($staff->id !== Auth::id())
                                                        <form action="{{ route('staff.destroy', $staff->id) }}" method="POST" onsubmit="return confirm('Delete this staff account?')">
                                                            @csrf @method('DELETE')
                                                            <button type="submit" class="p-2.5 bg-red-50 text-red-500 rounded-xl hover:bg-red-500 hover:text-white transition-all">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                                </svg>
                                                            </button>
                                                        </form>
                                                    @else
                                                        <span class="text-[10px] text-slate-300 font-bold">Cannot delete own account</span>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-8 py-12 text-center text-slate-400 font-semibold">No staff accounts found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            @else
                {{-- ===== RESIDENT VIEW ===== --}}
                <div class="max-w-3xl mx-auto">
                    <header class="text-center mb-10">
                        <h1 class="text-3xl font-black text-slate-900 tracking-tight">Request a Document</h1>
                        <p class="text-slate-500 mt-2">Submit your details below to process your application.</p>
                    </header>

                    <div class="bg-white p-10 rounded-[2.5rem] shadow-2xl shadow-slate-200 border border-white">
                        <form action="{{ route('applications.store') }}" method="POST" class="space-y-6">
                            @csrf

                            @if($errors->any() && !$errors->has('not_resident'))
                                <div class="bg-red-50 border border-red-200 text-red-700 text-sm font-semibold px-5 py-3 rounded-2xl">
                                    {{ $errors->first() }}
                                </div>
                            @endif

                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                <div class="md:col-span-2 space-y-2">
                                    <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">First Name</label>
                                    <input type="text" name="first_name" value="{{ old('first_name', Auth::user()->first_name ?? '') }}"
                                        class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl outline-none focus:ring-2 focus:ring-blue-500 font-semibold" required>
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1 block text-center">M.I.</label>
                                    <input type="text" name="middle_initial" maxlength="1" value="{{ old('middle_initial') }}"
                                        class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl outline-none focus:ring-2 focus:ring-blue-500 font-semibold text-center uppercase">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Last Name</label>
                                    <input type="text" name="last_name" value="{{ old('last_name', Auth::user()->last_name ?? '') }}"
                                        class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl outline-none focus:ring-2 focus:ring-blue-500 font-semibold" required>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Service Type</label>
                                    <select name="service_type" class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl outline-none focus:ring-2 focus:ring-blue-500 font-bold text-slate-700">
                                        <option value="Barangay Clearance">Barangay Clearance</option>
                                        <option value="Certificate of Indigency">Certificate of Indigency</option>
                                        <option value="Certificate of Residency">Certificate of Residency</option>
                                        <option value="Business Permit">Business Permit</option>
                                    </select>
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Purpose</label>
                                    <input type="text" name="purpose" value="{{ old('purpose') }}" placeholder="e.g. Job Application"
                                        class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl outline-none focus:ring-2 focus:ring-blue-500 font-semibold" required>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Additional Notes (Optional)</label>
                                <textarea name="notes" rows="3"
                                    class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl outline-none focus:ring-2 focus:ring-blue-500 font-semibold resize-none">{{ old('notes') }}</textarea>
                            </div>

                            <button type="submit"
                                class="w-full bg-blue-700 hover:bg-blue-800 text-white py-5 rounded-[1.5rem] font-black uppercase tracking-[0.2em] text-xs transition-all shadow-xl shadow-blue-100 active:scale-95">
                                Submit Request
                            </button>
                        </form>
                    </div>

                    @php $myApps = Auth::user()->applications()->latest()->get(); @endphp
                    @if($myApps->count())
                        <div class="mt-10">
                            <h2 class="text-lg font-black text-slate-800 mb-4">My Applications</h2>
                            <div class="space-y-3">
                                @foreach($myApps as $app)
                                    <div class="bg-white rounded-2xl px-6 py-4 border border-slate-100 shadow-sm flex items-center justify-between">
                                        <div>
                                            <p class="font-bold text-slate-800 text-sm">{{ $app->document_type }}</p>
                                            <p class="text-[11px] text-slate-400 italic mt-0.5">"{{ $app->purpose }}"</p>
                                            <p class="text-[10px] text-slate-300 mt-1">{{ $app->created_at->format('M d, Y h:i A') }}</p>
                                        </div>
                                        <div>
                                            @if($app->status === 'approved')
                                                <span class="px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-widest bg-blue-100 text-blue-600 border border-blue-200/50">Approved</span>
                                            @else
                                                <span class="px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-widest bg-emerald-100 text-emerald-600 border border-emerald-200/50 animate-pulse">Ready to Pick Up</span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            @endif
        @endauth

        @guest
            <div class="text-center py-24">
                <div class="inline-flex bg-blue-700 text-white p-4 rounded-3xl shadow-lg shadow-blue-200 mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4v3M12 7h1m-1 4h1" />
                    </svg>
                </div>
                <h1 class="text-4xl font-black text-slate-900 tracking-tight">Barangay Connect</h1>
                <p class="text-slate-500 mt-3 text-lg font-medium">Access barangay services online — fast and easy.</p>
                <div class="flex justify-center gap-4 mt-8">
                    <a href="{{ route('login') }}" class="bg-white border border-slate-200 text-slate-700 px-8 py-4 rounded-2xl font-black uppercase tracking-widest text-xs transition-all hover:bg-slate-50 shadow-sm">
                        Sign In
                    </a>
                    <a href="{{ route('register') }}" class="bg-blue-700 hover:bg-blue-800 text-white px-8 py-4 rounded-2xl font-black uppercase tracking-widest text-xs transition-all shadow-xl shadow-blue-100">
                        Create Account
                    </a>
                </div>
            </div>
        @endguest

    </main>

    <footer class="mt-12 text-center">
        <p class="text-[10px] font-black text-slate-300 uppercase tracking-[0.5em]">&copy; {{ date('Y') }} Barangay Connect System</p>
    </footer>

  <script>
    function switchTab(tab) {
        const tabs = ['applications', 'registry', 'staff'];

        tabs.forEach(function(t) {
            const panel = document.getElementById('panel-' + t);
            const btn   = document.getElementById('tab-' + t);
            if (panel) panel.classList.add('hidden');
            if (btn)   btn.classList.remove('active');
        });

        const activePanel = document.getElementById('panel-' + tab);
        const activeBtn   = document.getElementById('tab-' + tab);
        if (activePanel) activePanel.classList.remove('hidden');
        if (activeBtn)   activeBtn.classList.add('active');
    }

    // Auto-open correct tab after form submit or on page load
    document.addEventListener('DOMContentLoaded', function () {
        @if(session('open_tab'))
            switchTab('{{ session("open_tab") }}');
        @elseif(request('tab'))
            switchTab('{{ request("tab") }}');
        @endif
    });

    function togglePassword(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon  = document.getElementById(iconId);
        if (!input || !icon) return;

        const isHidden = input.type === 'password';
        input.type = isHidden ? 'text' : 'password';

        const eyeOpen  = `
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
        `;
        const eyeSlash = `
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
        `;

        icon.innerHTML = isHidden ? eyeOpen : eyeSlash;
    }
</script>

</body>
</html>