<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barangay Connect | Create Account</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-[#f8fafc] min-h-screen flex items-center justify-center px-4 py-12">
    <div class="bg-white w-full max-w-md p-10 rounded-[2.5rem] shadow-2xl shadow-slate-200 border border-white">
        <div class="text-center mb-8">
            <div class="inline-flex bg-blue-700 text-white p-3 rounded-2xl shadow-lg shadow-blue-200 mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                </svg>
            </div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Create Account</h1>
            <p class="text-slate-400 text-sm font-medium mt-1">Register to access barangay services</p>
        </div>

        @if($errors->any())
            <div class="bg-red-50 text-red-600 text-sm font-semibold px-4 py-3 rounded-2xl mb-6">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="/register" method="POST" class="space-y-5">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">First Name</label>
                    <input type="text" name="first_name" value="{{ old('first_name') }}" required
                    style="text-transform: uppercase;"
                        class="w-full px-5 py-4 bg-slate-50 rounded-2xl outline-none focus:ring-2 focus:ring-blue-500 font-semibold border-none">
                </div>
                  <div class="space-y-2">
                    <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Middle Name</label>
                    <input type="text" name="middle_name" value="{{ old('middle_name') }}" 
                    style="text-transform: uppercase;"
                        class="w-full px-5 py-4 bg-slate-50 rounded-2xl outline-none focus:ring-2 focus:ring-blue-500 font-semibold border-none">
              
            </div>
                <div class="space-y-2">
                    <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Last Name</label>
                    <input type="text" name="last_name" value="{{ old('last_name') }}" required
                    style="text-transform: uppercase;"
                        class="w-full px-5 py-4 bg-slate-50 rounded-2xl outline-none focus:ring-2 focus:ring-blue-500 font-semibold border-none">
                </div>
            </div>

            <div class="space-y-2">
                <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                    class="w-full px-5 py-4 bg-slate-50 rounded-2xl outline-none focus:ring-2 focus:ring-blue-500 font-semibold border-none">
            </div>

            <div class="space-y-2">
                <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Password</label>
                <div class="relative">
                    <input type="password" name="password" id="reg_password" required
                        class="w-full px-5 py-4 pr-14 bg-slate-50 rounded-2xl outline-none focus:ring-2 focus:ring-blue-500 font-semibold border-none">
                    <button type="button" onclick="togglePassword('reg_password', 'eye-reg')"
                        class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-700 transition-colors">
                        <svg id="eye-reg" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="space-y-2">
                <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Confirm Password</label>
                <div class="relative">
                    <input type="password" name="password_confirmation" id="reg_password_confirm" required
                        class="w-full px-5 py-4 pr-14 bg-slate-50 rounded-2xl outline-none focus:ring-2 focus:ring-blue-500 font-semibold border-none">
                    <button type="button" onclick="togglePassword('reg_password_confirm', 'eye-reg-confirm')"
                        class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-700 transition-colors">
                        <svg id="eye-reg-confirm" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>
            </div>

            <button type="submit"
                class="w-full bg-blue-700 hover:bg-blue-800 text-white py-5 rounded-[1.5rem] font-black uppercase tracking-[0.2em] text-xs transition-all shadow-xl shadow-blue-100 active:scale-95">
                Create Account
            </button>
        </form>

        <p class="text-center text-xs text-slate-400 mt-6 font-medium">
            Already have an account?
            <a href="{{ route('login') }}" class="text-blue-600 font-bold hover:underline">Sign In</a>
        </p>
    </div>

    <script>
        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            const isHidden = input.type === 'password';

            input.type = isHidden ? 'text' : 'password';

            const eyeOpen = `
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