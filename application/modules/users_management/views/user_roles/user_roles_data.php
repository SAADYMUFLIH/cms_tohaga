<script>
    $(document).ready(function() {
        init_grid_data_manipulation();
        $('[data-toggle="popover"]').popover();
        $('.chosen-select', this).chosen({
            width: "100%"
        });
    });
</script>
<?php echo $list_data; ?>