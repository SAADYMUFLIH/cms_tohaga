<?php

function generateGridElement($column_title, $list_field, $posts, $search_id, $table_width = '100', $tbl_name, $custom_col = '', $nm_function = '', $icon_action = 'edit', $multi_select_displayed = true, $icon_size = " fa-lg", $view_action = true, $component = 'info-circle', $arr_tooltip = '', $col_title_complex = '', $custom_action = '', $export = false)
{

    if (is_array($arr_tooltip)) {
        $tooltip_text = "data-toggle='tooltip' data-placement='" . $arr_tooltip['placement'] . "' title='" . $arr_tooltip['title'] . "'";
        $icon = empty($arr_tooltip['icon']) ? $icon_action : $arr_tooltip['icon'];
    } else {
        $tooltip_text = "";
        $icon = $icon_action;
    }

    $script = "
    <script>
        
        $(document).ready(function() {
            $('#per_row').change(function() {
                searchFilter();            
            });
        });

        $(function() {
            $('[data-toggle=\"tooltip\"]').tooltip()            
        }) 

        new Tablesort(document.getElementById('myTable'));

        function searchFilter(page_num) {
            page_num = page_num ? page_num : 0;
            document.getElementById('page_pos').value = page_num;
            var keywords = $('#keywords').val();
            var sortBy = $('#sortBy').val();
            var rowPerPage = $('#per_row').val(); 
            // $('.loading').show();
            $.ajax({
                type: 'POST',
                url: '" . base_url() . $component['controller'] . "/ajaxPaginationData/'+ page_num,
                data: 'page=' + page_num + '&keywords=' + keywords + '&sortBy=' + sortBy+ '&rownum=' + rowPerPage,
                error: function(xhr, ajaxOptions, thrownError) {
                    console.log(xhr.status);
                    console.log(xhr.responseText);
                    console.log(thrownError);
                },
                success: function(html) {
                    $('#postList').html(html);
                    $('.loading').hide();
                    $('#per_row').val(rowPerPage);
                }
            });
        }

        function confirmDelete() {
            $.confirm({
                title: '<i style=\"font-size:20px; color:red\" class=\"fa fa-trash-alt\"></i>  Delete Data',
                content: '<div class=\"border-top mb-3\">Anda yakin akan melakukan pengapusan data?</div>',
                confirmButton: 'Process',
                cancelButton: 'Cancel',
                type: 'red',
                columnClass: 'small',
                buttons: {
                    proses: function() {
                        deleteData()
                    },
                    cancel: function() {
                    }
                }
            });
        }
    </script>";

    $jml_custom_col = (is_array($custom_col)) ? count($custom_col) : 0;
    $ci = &get_instance();
    $gridElement = '
            <style>
            .polaroid {
                background-color: white;
                box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
            }
            .table .thead-dark th {
                border-color: #e8e8e8 !important;
            }
            th[role=columnheader]:not(.no-sort).action:after { border-style: none; }
            </style>

            <input type="hidden" id="page_pos">
            <div id="messages"></div>
            <div class="post-list" id="postList">
                <div id="list_data_container" style="width:100%; ">
                ';
    $gridElement .= generateExport_to_Excel_button($component['rowcount'], $export);
    $gridElement .= '<table id="myTable" style="margin-bottom:0px; width:' . $table_width . '%;"  class="table table-bordered  table-hover table-striped">';
    $gridElement .= '<thead class=" thead-dark">';
    if (is_array($col_title_complex)) {
        $total_row = count($col_title_complex);
        $i = 0;
        foreach ($col_title_complex as $col) {
            $gridElement .= "<tr style='height:40px'>";
            if ($i == 0) {
                $gridElement .= '<th rowspan="' . $total_row . '" data-sort-method="none" style="text-align:right;vertical-align: middle; width:25px"  class="action" data-sort-method="none" >#</th>';
                if ($multi_select_displayed == true) {
                    $gridElement .= '<th rowspan="' . $total_row . '" data-sort-method="none"  style="width:25px;vertical-align: middle;text-align:center" class="action" ><input type="checkbox" OnChange="Populate()"  class="select-all"   /></th>';
                }
            }
            foreach ($col as $col_title) {
                $gridElement .= "<th rowspan='" . $col_title[1] . "' colspan='" . $col_title[2] . "' style='vertical-align : middle;text-align:center;'>" . $col_title[0] . "</th>";
            }
            if ($i == 0) {
                if ($view_action == true || $view_action == 1) {
                    $gridElement .= '<th rowspan="' . $total_row . '" data-sort-method="none" class="action" style="vertical-align: middle;text-align:center">Action</th>';
                }
                $i++;
            }
            $gridElement .= "</tr>";
        }
    } else {
        $gridElement .= '<tr style="height:40px">
                        <th data-sort-method="none" style="text-align:right; width:25px;vertical-align: middle" data-sort-method="none" class="action" >#</th>';
        if ($multi_select_displayed == true) {
            $gridElement .= '<th data-sort-method="none"  style="width:25px;vertical-align: middle;text-align:center" class="action" ><input type="checkbox" OnChange="Populate()"  class="select-all"   /></th>';
        }

        foreach ($column_title as $col_title) {
            $gridElement .= '<th style="vertical-align: middle;text-align:center" >' . $col_title . '</th>';
        }

        if ($view_action == true || $view_action == 1) {
            $gridElement .= '<th data-sort-method="none" style="vertical-align: middle;text-align:center" class="action" >Action</th></tr>';
        }
    }

    $gridElement .= '</thead><tbody>';
    $rec_no = 1;

    if (is_array($posts)) {
        $r = array_values($posts[0]);
    }

    if (!empty($posts) && !empty($r[0])) {
        foreach ($posts as $row) {

            $style_font_read = '';

            if($tbl_name == 'tbl_aspirasi' || $tbl_name ==  'tbl_ide_startup') {
                if(!$row['read']) {
                    $style_font_read = "font-weight:bold";
                }
            }

            $gridElement .= '
                            <tr style="height:40px;'. $style_font_read .'">
                                <td scope="row" align="right"  style="width:3%" >' . $rec_no . '. </td>';
            if ($multi_select_displayed == true) {
                $gridElement .= '<td style="width:25px;vertical-align: middle;text-align:center" data-sorter="false" scope="row" class="action"><input type="checkbox" class="chk-box"  Onclick="Populate()" name="id_content[]" value=' . $row[$search_id] . ' /></td>';
            }

            $i = 0;
            foreach ($list_field as $field) {

                $dataType = getFieldDataTypeByName($tbl_name, $field);

                $table_value = $row[$field];
                if (($dataType == 'varchar') || ($dataType == 'date') || ($dataType == 'enum')) {
                    $align_right = "";
                } else if ($dataType == 'datetime') {
                    $align_right = "align='center'";
                } else {
                    $align_right = "align='right'";
                    $table_value = number_format($table_value, 0, "", ".");
                }

                //ci is for column with custom content
                /*
                | contoh value yang harus di parsing
                | dalam bentuk array, contohnya seperti dibawah :
                |
                |   $custom_content[] =  array('custom_field' => 'custom1', 'content' => 'content 1');
                |   $custom_content[] =  array('custom_field' => 'custom2', 'content' => 'content 2');
                |   $custom_content[] =  array('custom_field' => 'custom3', 'content' => 'content 3');
                |
                */

                if ($jml_custom_col > 0) {
                    if ($i < $jml_custom_col) {

                        if ($custom_col[$i]['custom_field'] == $field) {

                            if (!empty($nm_function)) {
                                $gridElement .= "<td>" . $nm_function($row[$search_id], $component) . "</td>";
                            } else if (isset($custom_col[$i]['last_content'])) {
                                $gridElement .= "<td>" . $custom_col[$i]['content'] . $table_value . '"' . $custom_col[$i]['middle_content'] . $table_value . $custom_col[$i]['last_content'] . "</td>";
                            } else if (isset($custom_col[$i]['img_content'])) {
                                $gridElement .= "<td>" . $custom_col[$i]['content'] . $table_value . '?param=' . rand() . '>"' . "</td>";
                            } else {
                                $gridElement .= "<td>" . $custom_col[$i]['content'] . $table_value . '"' . "</td>";
                            }
                            $i++;
                        } else {
                            $gridElement .= "<td   $align_right> $table_value</td>";
                        }
                    }
                } else {
                    $gridElement .= "<td   $align_right> $table_value</td>";
                }
            }

            if (($view_action == true || $view_action == 1) && $custom_action == 'list_karyawan') {
                $gridElement .= '<td  style="width:107px"  align="center"><div style="display:inline-block">
                <a data-toggle="tooltip" data-placement="bottom" title="Lihat History Career" href="javascript:showListCareer(' . "'" . $row[$search_id] . "'" . ')"><i  style="font-size:20px" class="fas fa-chart-line" aria-hidden="true"></i></a> 
                <a data-toggle="tooltip" data-placement="bottom" title="Lihat History Penilaian" href="javascript:showListPenilaian(' . "'" . $row[$search_id] . "'" . ')"><i  style="font-size:20px" class="fas fa-check-double" aria-hidden="true"></i></a>
                <a data-toggle="tooltip" data-placement="bottom" title="Edit" href="javascript:editdata(' . "'" . $row[$search_id] . "'" . ')"><i  style="font-size:20px" class="fas fa-' . $icon . '" aria-hidden="true"></i></a>
                </div></td>';
                // <a data-toggle="tooltip" data-placement="bottom" title="Suspend" href="javascript:suspend(' . "'" . $row[$search_id] . "'" . ')"><i  style="font-size:20px" class="fas fa-user-slash" aria-hidden="true"></i></a>
                // $gridElement .= '<td  style="width:25px" ' . $tooltip_text . '  align="center"><a href="javascript:editdata(' . "'" . $row[$search_id] . "'" . ')"><i  style="font-size:20px" class="fas fa-' . $icon . '" aria-hidden="true"></i></a></td>';
                $gridElement .= '</tr>';
            } else if ($view_action == true || $view_action == 1) {
                $gridElement .= '<td  style="width:25px" ' . $tooltip_text . '  align="center"><a href="javascript:editdata(' . "'" . $row[$search_id] . "'" . ')"><i  style="font-size:20px" class="fas fa-' . $icon . '" aria-hidden="true"></i></a></td>';
                $gridElement .= '</tr>';
            }

            $rec_no++;
        }
    } else {
        $gridElement .= '<tr><td colspan="17" style="text-align:center;color:white" class="bg-info">Data not available or not found</td></tr>';
    }
    $gridElement .= '</table>';

    $gridElement .= '<div class="text-sm text-primary loading" style="display:none; position:absolute; padding-bottom:10px; " ><img src="' . base_url() . 'assets/images/elipsis.gif" height="30"></div></div>';
    $gridElement .=  $ci->ajax_pagination->create_links();
    $gridElement .= '</div>';

    // $gridElement .= generateGridPrint($column_title, $list_field, $posts, $search_id, $table_width, $tbl_name, $custom_col, $nm_function, $icon_action, $multi_select_displayed = true, $icon_size, $view_action = true, $component, $arr_tooltip, $col_title_complex, $custom_action, $export);

    return $script . $gridElement;
}

function getFieldDataTypeByName($tblname,  $fieldname)
{
    $ci = &get_instance();
    $fields = $ci->db->field_data($tblname);
    $dataType = '';
    foreach ($fields as $field) {
        if ($field->name == $fieldname) {
            $dataType = $field->type;
            break;
        }
    }
    if ($dataType == '') {
        $dataType = 'varchar';
    }
    return $dataType;
}

function generateSearch_add_new_button($search_place_holder, $include_add_button = true, $include_search = true)
{
    $search_element = "<style></style>";
    if ($include_search) {
        $search_element .= '<div class ="form-group row">
        <input type="text" name="searchText" id="keywords" onkeydown="searchFilter() " autocomplete="off" class="form-control rounded-text" placeholder="' . $search_place_holder . '">
        <div class="text-sm text-primary loading" style="display:none; position:absolute; padding-bottom:10px; " ><img src="' . base_url() . 'assets/images/elipsis.gif" height="30"></div>   
        </div>';
    }
    if ($include_add_button) {
        $search_element .= '<div class =" row">
        <button type="button" onclick="displayInputForm()" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Add New</button>
        </div>';
    }
    return $search_element;
}

function generateBottom_add_new_and_delete_button($display_add_button = true, $display_delete_button = true)
{
    $bottom_add_new_and_delete_button = '
    <style>
    .float{
        position:fixed;
        bottom:40px;
        right:40px;
    }
    
    </style>';

    $bottom_add_new_and_delete_button .= '
        <div class="col-md-12 float-dihapus">
            <div class="text-right form-group" >';
    if ($display_add_button == true) {
        $bottom_add_new_and_delete_button .= '<button type="button" onclick="displayInputForm()" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Add New</button>';
    }
    if ($display_delete_button == true) {
        $bottom_add_new_and_delete_button .= ' <button id="btndelete" disabled type="button" onclick="confirmDelete()"  class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Delete</button>';
    }
    $bottom_add_new_and_delete_button .= '</div>
        </div>';

    $button = $bottom_add_new_and_delete_button;
    return $button;
}

function generateExport_to_Excel_button($rowcount, $export = false)
{


    if ($rowcount > 0) {
        $export_button = '
        <div class="row">
            <div class="col-md-2 text-left mb-1"> <span>Record per page :</span>
                ' . create_chosen_db_combo("per_row", "per_row", "ref_row_per_page", "row_per_page as id", "row_per_page as nilai", "row_per_page", "") . '
            </div>';
        
        if($export) {
            $export_button .= '
            <div class="col-md-10 text-right mb-1">
                <button onclick="printData()" class="btn btn-info btn-xs"><i class="fa fa-print"></i> Print </button>
                <button type="button" onclick="exportToExcel()" class="btn btn-info btn-xs"><i class="fa fa-file-excel"></i> Export to Excel</button>
                <button onclick="displayImportForm()" type="button" class="btn btn-info btn-xs"><i class="fa fa-upload"></i> Import from Excel</button>
            </div>
            ';
        }

        $export_button .= '</div>';
    } else {
        $export_button = '';
    }
    return $export_button;
}
