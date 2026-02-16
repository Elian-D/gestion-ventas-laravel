{{-- resources/views/sales/pos/lock.blade.php --}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terminal Bloqueada | {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-950 antialiased overflow-hidden">
    {{-- Fondo Decorativo con Gradientes Dinámicos --}}
    <div class="fixed inset-0 z-0">
        <div class="absolute top-[-10%] left-[-10%] w-[50%] h-[50%] bg-indigo-900/30 rounded-full blur-[120px]"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[50%] h-[50%] bg-blue-900/20 rounded-full blur-[120px]"></div>
    </div>

    <main class="relative z-10 min-h-screen flex flex-col items-center justify-center p-6" 
          x-data="posLockSystem()">
        
        <div class="w-full max-w-md">
            {{-- Card Principal --}}
            <div class="bg-white/10 backdrop-blur-2xl border border-white/10 rounded-[2.5rem] shadow-2xl p-8 text-center"
                 :class="error ? 'animate-shake' : ''">
                
                {{-- Icono de la Terminal --}}
                <div class="mx-auto w-24 h-24 bg-gradient-to-tr from-indigo-500 to-blue-600 rounded-3xl flex items-center justify-center shadow-2xl mb-6 transform -rotate-3">
                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                </div>

                <h1 class="text-3xl font-black text-white tracking-tight mb-2">Terminal Bloqueada</h1>
                <p class="text-slate-400 font-medium mb-8">
                    Caja: <span class="text-indigo-400">{{ $terminal->name }}</span>
                </p>

                {{-- Visualizador de PIN (Dots) --}}
                <div class="flex justify-center gap-5 mb-10">
                    <template x-for="i in 4">
                        <div class="w-5 h-5 rounded-full border-2 border-white/20 transition-all duration-300"
                             :class="pin.length >= i ? 'bg-white border-white scale-125 shadow-[0_0_15px_rgba(255,255,255,0.6)]' : ''">
                        </div>
                    </template>
                </div>

                {{-- Teclado Numérico --}}
                <div class="grid grid-cols-3 gap-4 px-4">
                    <template x-for="n in [1, 2, 3, 4, 5, 6, 7, 8, 9]">
                        <button @click="addNumber(n)" 
                                class="h-16 rounded-2xl bg-white/5 border border-white/5 text-2xl font-bold text-white hover:bg-white/10 active:scale-95 transition-all">
                            <span x-text="n"></span>
                        </button>
                    </template>
                    <button @click="clear()" class="h-16 rounded-2xl bg-red-500/10 border border-red-500/20 text-red-400 font-bold hover:bg-red-500/20 active:scale-95 transition-all">
                        C
                    </button>
                    <button @click="addNumber(0)" class="h-16 rounded-2xl bg-white/5 border border-white/5 text-2xl font-bold text-white hover:bg-white/10 active:scale-95 transition-all">
                        0
                    </button>
                    <button @click="backspace()" class="h-16 rounded-2xl bg-white/5 border border-white/5 flex items-center justify-center text-white hover:bg-white/10 active:scale-95 transition-all">
                        <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2M3 12l6.414 6.414A2 2 0 0010.828 19h6.344a2 2 0 002-2V7a2 2 0 00-2-2h-6.344a2 2 0 00-1.414.586L3 12z"></path>
                        </svg>
                    </button>
                </div>

                <div class="mt-8">
                    <p x-show="error" x-text="error" class="text-red-400 font-bold text-sm mb-4"></p>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="text-slate-500 hover:text-white text-xs font-bold uppercase tracking-widest transition-colors">
                            Cerrar Sesión de Usuario
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <script>
        function posLockSystem() {
            return {
                pin: '',
                error: '',
                loading: false,

                addNumber(num) {
                    if (this.pin.length < 4) {
                        this.pin += num;
                        if (this.pin.length === 4) this.verify();
                    }
                },
                backspace() { this.pin = this.pin.slice(0, -1); this.error = ''; },
                clear() { this.pin = ''; this.error = ''; },

                async verify() {
                    this.loading = true;
                    try {
                        const response = await fetch('{{ route('sales.pos.verify-pin') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ 
                                terminal_id: {{ $terminal->id }}, 
                                pin: this.pin 
                            })
                        });

                        const data = await response.json();

                        if (response.ok) {
                            window.location.href = '{{ route('sales.pos.index') }}';
                        } else {
                            this.error = data.message || 'PIN incorrecto';
                            this.pin = '';
                            setTimeout(() => { this.error = ''; }, 3000);
                        }
                    } catch (e) {
                        this.error = 'Error de conexión';
                    } finally {
                        this.loading = false;
                    }
                }
            }
        }
    </script>

    <style>
        .animate-shake { animation: shake 0.4s cubic-bezier(.36,.07,.19,.97) both; }
        @keyframes shake {
            10%, 90% { transform: translate3d(-1px, 0, 0); }
            20%, 80% { transform: translate3d(2px, 0, 0); }
            30%, 50%, 70% { transform: translate3d(-4px, 0, 0); }
            40%, 60% { transform: translate3d(4px, 0, 0); }
        }
    </style>
</body>
</html>