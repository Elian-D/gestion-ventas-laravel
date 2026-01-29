<script>
    window.filterSources = {
        clients: JSON.parse('{!! addslashes(json_encode($clients->pluck("name", "id"))) !!}'),
        businessTypes: JSON.parse('{!! addslashes(json_encode($businessTypes->pluck("nombre", "id"))) !!}'),
        states: JSON.parse('{!! addslashes(json_encode($states->pluck("name", "id"))) !!}'),
    };
</script>