{{-- resources/views/clients/partials/filter-sources.blade.php --}}
<script>
    window.filterSources = {
        estadosClientes: JSON.parse('{!! addslashes(json_encode($estadosClientes->pluck("nombre", "id"))) !!}'),
    };
</script>