<script>
    window.filterSources = {
        equipmentTypes: JSON.parse('{!! addslashes(json_encode($equipmentTypes->pluck("nombre", "id"))) !!}'),
        pointsOfSale: JSON.parse('{!! addslashes(json_encode($pointsOfSale->pluck("name", "id"))) !!}'),
    };
</script>
