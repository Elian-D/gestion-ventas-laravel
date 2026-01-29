@props(['route', 'label'])

<a href="{{ $route }}" class="text-xs text-blue-600 hover:text-blue-800 hover:underline flex items-center transition-colors duration-200">
    <x-heroicon-s-list-bullet class="w-3.5 h-3.5 mr-1.5 opacity-70" />
    {{ $label }}
</a>