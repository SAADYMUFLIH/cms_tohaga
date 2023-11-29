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
        console.log($controller);
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

    // function setFilter() {
    //     var filter = $("#tampil").val();
    //     // console.log(filter[0]);
    //     filter.forEach((element) => {
    //         $("#" + element).show();
    //         // console.log(element);
    //     });
    // }
    $('#tampil').on('change', function(change, deselected) {
        console.log(deselected);
        if (deselected.deselected) {
            $("#" + deselected.deselected).hide();
        }
        if (deselected.selected) {
            $("#" + deselected.selected).show();
        }

    });

    $(document).on("click",".delprod", function () {
        var id = $(this).val()
        var name = $(this).attr('_name')
        $.confirm({
            title: 'Konfirmasi Hapus Dokumen',
            content: 'Yakin '+$(this).attr('_name')+' akan dihapus?',
            confirmButton: 'Process',
            cancelButton: 'Cancel',
            type: 'red',
            columnClass: 'medium',
            buttons: {
                proses: function() {
                    deleteproduct(id,name)
                },
                cancel: function() {}
            }
        });
        return false; //confirm
            
        
    });

    function deleteproduct(id,nameprod) { 
        toastr.options = {
            timeOut: 3000,
            positionClass: "toast-top-right"
        };
        
            $.ajax({
                type: "post",
                url: "<?php echo base_url() . $controller ?>/deleteData",
                data: {
                    id : id
                },
                dataType: "json",
                success: function (response) {
                    toastr["info"](nameprod +" berhasil dihapus");
                    //alert("berhasil dihapus")
                    $("#row-"+id).remove();
                },error: function (response) {  
                    // alert("error : data gagal di hapus");
                    toastr["danger"](nameprod +" gagal dihapus");
                }
            });
        //}
        
    }
</script>

<?php
$view_button = isset($access) ? $access : false;
echo generateSearch_add_new_button($search_place_holder, $view_button);
// echo generateSearch_add_new_button($search_place_holder, false);
?>

<div class="mt-3">
    <form id="frm_list">
        <?php echo $list_data; 
        ?>
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