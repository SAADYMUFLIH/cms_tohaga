<script src="<?php echo base_url() ?>assets/js/choosen_auto_direction.js"></script>
<script>
    $(document).ready(function() {
        $('.chosen-select', this).chosen({
      width: "100%"
    });
        init_grid_data_manipulation();
    });
</script>

<?php echo $list_data; ?>