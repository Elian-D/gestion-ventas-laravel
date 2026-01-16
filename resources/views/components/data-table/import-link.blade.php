@props([
    'route',          // route('clients.import')
    'title' => 'Importar datos'
])

<a href="{{ $route }}"
   title="{{ $title }}"
   class="inline-flex items-center p-2 border border-sky-200 rounded-lg bg-sky-50 text-sky-600 hover:bg-sky-100 transition shadow-sm">
    <x-heroicon-s-arrow-up-tray class="w-5 h-5" />
</a>
