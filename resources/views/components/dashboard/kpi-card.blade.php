@php
    $wrapperClasses = "relative overflow-hidden bg-white rounded-xl shadow-sm border-b-4 {$colorClasses()} p-5 transition-all";
    if($href) {
        $wrapperClasses .= " hover:shadow-md hover:scale-[1.02] cursor-pointer";
    }
@endphp

<{{ $href ? 'a' : 'div' }} 
    @if($href) href="{{ $href }}" @endif 
    class="{{ $wrapperClasses }}"
>
    <div class="flex justify-between items-start">
        <div>
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">
                {{ $title }}
            </p>
            <h3 class="text-2xl font-black text-gray-800 tabular-nums">
                {{ $value }}
            </h3>
            
            @if($trend)
                <div class="flex items-center mt-1 {{ $trendUp ? 'text-green-500' : 'text-red-500' }}">
                    <x-dynamic-component :component="$trendUp ? 'heroicon-s-arrow-trending-up' : 'heroicon-s-arrow-trending-down'" class="w-3 h-3 mr-1"/>
                    <span class="text-[10px] font-bold">{{ $trend }}</span>
                </div>
            @endif

            @if($secondaryText)
                <p class="text-[10px] text-gray-400 mt-2 italic">
                    {{ $secondaryText }}
                </p>
            @endif
        </div>

        <div class="p-2 rounded-lg {{ $colorClasses() }} bg-opacity-10">
            <x-dynamic-component :component="'heroicon-s-' . $icon" class="w-6 h-6 opacity-80" />
        </div>
    </div>
</{{ $href ? 'a' : 'div' }}>