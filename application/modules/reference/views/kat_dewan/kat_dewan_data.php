<script>
    $(document).ready(function() {
        init_grid_data_manipulation();
        $('.chosen-select', this).chosen({
            width: "100%"
        });
    });
</script>
<?php echo $list_data; ?>