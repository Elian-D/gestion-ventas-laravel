<div x-data="{ 
        show: true, 
        remaining: {{ $duration }}, 
        interval: null,
        paused: false,
        percent: 100,
        
        init() { this.startTimer(); },
        startTimer() {
            this.paused = false;
            let lastTick = Date.now();
            this.interval = setInterval(() => {
                if (!this.paused) {
                    const now = Date.now();
                    const delta = now - lastTick;
                    lastTick = now;
                    this.remaining -= delta;
                    this.percent = Math.max(0, (this.remaining / {{ $duration }}) * 100);
                    if (this.remaining <= 0) this.close();
                } else { lastTick = Date.now(); }
            }, 10);
        },
        pause() { this.paused = true; },
        resume() {
            this.remaining = {{ $duration }};
            this.percent = 100;
            this.paused = false;
        },
        close() {
            this.show = false;
            setTimeout(() => { clearInterval(this.interval); }, 300);
        }
    }" 
    x-show="show"
    x-transition:enter="transition ease-out duration-500"
    x-transition:enter-start="opacity-0 scale-90 translate-y-4 sm:translate-y-0 sm:translate-x-12"
    x-transition:enter-end="opacity-100 scale-100 translate-x-0"
    x-transition:leave="transition ease-in duration-300"
    x-transition:leave-start="opacity-100 scale-100"
    x-transition:leave-end="opacity-0 scale-95 translate-x-8"
    @mouseenter="pause()" 
    @mouseleave="resume()"
    class="group relative w-full max-w-sm overflow-hidden rounded-2xl border-l-4 {{ $borderColor }} bg-white/90 backdrop-blur-md shadow-[0_8px_30px_rgb(0,0,0,0.12)] transition-all duration-300 hover:shadow-[0_20px_50px_rgba(0,0,0,0.15)] hover:-translate-y-1"
>
    {{-- Hilo de luz (Barra de progreso) --}}
    <div class="absolute top-0 left-0 w-full h-[3px] bg-gray-100/50">
        <div class="h-full {{ $progressColor }} shadow-[0_0_10px_rgba(255,255,255,0.8)] transition-all ease-linear" 
             :style="`width: ${percent}%`"
        ></div>
    </div>

    <div class="p-4 flex items-start gap-4">
        {{-- Icono con efecto de círculo sutil --}}
        <div class="flex-shrink-0 flex items-center justify-center w-10 h-10 rounded-full {{ $lightBgColor }} {{ $iconColor }} transition-transform duration-500 group-hover:rotate-12">
            <x-dynamic-component :component="$icon" class="w-6 h-6" />
        </div>

        {{-- Contenido --}}
        <div class="flex-1 pt-0.5">
            <h3 class="text-sm font-bold text-gray-900 flex items-center justify-between">
                {{ $title }}
                <button @click="close()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <x-heroicon-s-x-mark class="w-4 h-4" />
                </button>
            </h3>
            <p class="mt-1 text-sm text-gray-600 leading-relaxed font-medium">
                {{ $message }}
            </p>
        </div>
    </div>

    {{-- Reflejo decorativo inferior --}}
    <div class="absolute bottom-0 left-0 w-full h-1 opacity-10 bg-gradient-to-r from-transparent via-white to-transparent"></div>
</div>