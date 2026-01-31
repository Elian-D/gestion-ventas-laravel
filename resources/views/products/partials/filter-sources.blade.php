<script>
    window.filterSources = {
        categories: JSON.parse('{!! addslashes(json_encode($categories->pluck("name", "id"))) !!}'),
        units: JSON.parse('{!! addslashes(json_encode($units->pluck("name", "id"))) !!}'),
    };
</script>