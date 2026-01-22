{{-- resources/views/clients/partials/filter-sources.blade.php --}}
<script>
    window.filterSources = {
        estadosClientes: JSON.parse('{!! addslashes(json_encode($estadosClientes->pluck("nombre", "id"))) !!}'),
        // tiposNegocio: JSON.parse('{!! addslashes(json_encode($tiposNegocio->pluck("nombre", "id"))) !!}'),
    };
</script>