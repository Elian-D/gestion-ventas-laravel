@props(['route', 'title' => 'Plantilla Base', 'format' => '.xlsx'])

<a href="{{ $route }}" class="flex items-center p-4 border rounded-xl hover:bg-indigo-50 transition-all duration-200 border-indigo-100 group shadow-sm hover:shadow-md">
    <div class="bg-indigo-100 p-2 rounded-lg group-hover:bg-white transition-colors">
        <x-heroicon-s-document-text class="w-8 h-8 text-indigo-600" />
    </div>
    <div class="ml-4">
        <span class="block font-bold text-indigo-900 text-sm">{{ $title }}</span>
        <span class="text-xs text-indigo-500 font-medium">Descargar formato {{ $format }}</span>
    </div>
</a>