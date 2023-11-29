<script>
    $(document).ready(function() {
        init_grid_data_manipulation();
        $('.chosen-select', this).chosen({
            width: "100%"
        });

        $(document).on('shown.bs.modal', function(e) {
            $('input:visible:enabled:first', e.target).focus();
        });

        $('#mdl_add').on('hidden.bs.modal', function() {
            $(this).find("input[type=text],textarea,checkbox").val('').end();
        });

        $('.modal-dialog').draggable({
            handle: ".modal-header"
        });
    });

    function displayInputForm(id) {
        editdata_popup(id, '<?php echo base_url() . $controller ?>/displayInputForm', 'frmContainer', 'mdl_add');
    }

    function displayImportForm(id) {
        editdata_popup(id, '<?php echo base_url() . $controller ?>/displayImportForm', 'frmContainer', 'mdl_add');
    }

    function deleteData() {
        delete_record('<?php echo base_url() . $controller ?>/rowDelete', 'frm_list')
    }

    function editdata(id) {
        editdata_popup(id, '<?php echo base_url() . $controller ?>/editData', 'frmContainer', 'mdl_add');
    }

    function exportToExcel() {
        var keywords = $('#keywords').val();
        //var rowNum = $('#sortBy').val();
        $(location).attr('href', '<?php echo base_url() . $controller ?>/exportToExcel?keywords=' + keywords);
    }

    function printData()
    {
        var divToPrint=document.getElementById("myTable");
        newWin= window.open("");
        newWin.document.write(divToPrint.outerHTML);
        newWin.document.write("<style>  th:nth-child(2){display:none;} td:nth-child(2){display:none;} td { text-align: center; vertical-align: middle; }</style>");
        newWin.document.write("<style>  th:nth-child(5){display:none;} td:nth-child(5){display:none;}</style>");
        newWin.document.write("<style>  th:nth-child(6){display:none;} td:nth-child(6){display:none;}</style>");
        newWin.print();
        newWin.close();
    }

    function printData()
    {
        var divToPrint=document.getElementById("myTable");
        newWin= window.open("");
        newWin.document.write(divToPrint.outerHTML);
        newWin.document.write("<style>  th:nth-child(2){display:none;} td:nth-child(2){display:none;} td { text-align: center; vertical-align: middle; }</style>");
        newWin.document.write("<style>  th:nth-child(5){display:none;} td:nth-child(5){display:none;}</style>");
        newWin.document.write("<style>  th:nth-child(6){display:none;} td:nth-child(6){display:none;}</style>");
        newWin.print();
        newWin.close();
    }
</script>

<?php
$view_button = isset($access) ? $access : false;
echo generateSearch_add_new_button($search_place_holder, $view_button);
?>
<div class="mt-3">
    <form id="frm_list">
        <?php echo $list_data; ?>
    </form>
</div>
<?php
$view_button = isset($access) ? $access : false;
echo generateBottom_add_new_and_delete_button($view_button, $view_button)
?>

<!-- Modal input data start here -->
<div class="modal fade" id="mdl_add" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div id="frmContainer">
                <!-- Modal content start here -->

                <!-- Modal content End Here -->
            </div><!-- form Container -->
        </div>
    </div>
</div>

