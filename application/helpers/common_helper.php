<?php

function create_db_combo( $tblname, $key_field, $value_field, $order_field, $additional_value = '-Plese Select-', $param = '' )
 {
    //get main CodeIgniter object
    $ci = &get_instance();
    //load databse library
    $ci->load->database();

    $ci->db->from( $tblname );
    if ( $param != '' ) {
        $ci->db->where( $param );
    }
    $ci->db->order_by( $order_field );
    $result = $ci->db->get();

    $dd[''] = $additional_value;
    if ( $result->num_rows() > 0 ) {
        foreach ( $result->result() as $row ) {
            $dd[$row->$key_field] = $row->$value_field;
        }
    }
    return $dd;
}

function create_chosen_db_combo( $id_obj, $nm_obj, $tblname, $key_field, $value_field, $order_field, $additional_value, $selected_value = '', $param = '', $multiple = false, $dt_arr_multi = array(), $required = true)
 {
    $ci = &get_instance();
    $ci->load->database();
    if ( $order_field != '' ) {
        $sqlorder = " ORDER BY $order_field";
    }
    $sql = "SELECT $key_field, $value_field FROM $tblname $param $sqlorder";
    // echo $sql .'<br>';
    $query = $ci->db->query( $sql );
    $result = $query->result_array();
    $dd = '';
    $multi = '';
    $tanda = '';
    if ( $multiple == true ) {
        $multi = 'multiple';
        $tanda = '[]';
    }

    $required = ($required==true)? 'required' : '';

    $dd .= '<select ' . $multi . ' data-placeholder="Silahkan Pilih" id="' . $id_obj . '" name="' . $nm_obj . $tanda . '" single '.$required.' class="form-control chosen-select" tabindex="8" " >';
    $cntr = 0;
    if ( $additional_value == '' ) {
        //$additional_value = '-Please Select-';
    }

    foreach ( $result as $res ) {
        $r = array_values( $res );
        $flag = '';
        //echo ' data '. $r[0] .' selected '. $selected_value .'<br>';
        if ( $r[0] == $selected_value ) {
            $selected = 'Selected';
        } else {
            $selected = '';
        }

        if ( $cntr == 0 ) {
            $dd .= '<option value="">' . $additional_value . '</option>';
        }

        if ( $multiple == true ) {
            //jika dropdown multi select
            if ( count( $dt_arr_multi ) > 0 ) {
                //Jika nilai sudah ada di db utk user tertentu
                if ( in_array( $r[0], $dt_arr_multi, true ) ) {
                    $selected = 'Selected';
                } else {
                    $selected = '';
                }
                $dd .= '<option  style="fixed; z-index:99999"  ' . $flag . ' ' . $selected . ' value="' . $r[0] . '">' . $r[1] . '</option>';
            } else {
                //jika nilai blum ada di db utk user tertentu
                $dd .= '<option  style="fixed; z-index:99999"  ' . $flag . ' value="' . $r[0] . '">' . $r[1] . '</option>';
            }
        } else {
            //jika single select
            $dd .= '<option  style="fixed; z-index:99999"  ' . $flag . ' ' . $selected . ' value="' . $r[0] . '">' . $r[1] . '</option>';
        }

        $cntr++;
    }
    $dd .= '</select>';
    return $dd;
}

function create_chosen_array_combo( $id_obj, $nm_obj, $array_dt,  $additional_value, $selected_value = '',  $multiple = false, $dt_arr_multi = array() )
 {
    $ci = &get_instance();
    $ci->load->database();

    $dd = '';
    $multi = '';
    $tanda = '';
    if ( $multiple == true ) {
        $multi = 'multiple';
        $tanda = '[]';
    }

    $dd .= '<select ' . $multi . ' data-placeholder="Silahkan Pilih" id="' . $id_obj . '" name="' . $nm_obj . $tanda . '" single class="form-control chosen-select" tabindex="8" " >';
    $cntr = 0;
    if ( $additional_value == '' ) {
        //$additional_value = '-Please Select-';
    }

    foreach ( $array_dt as $value ) {
        $flag = '';
        //echo ' data '. $r[0] .' selected '. $selected_value .'<br>';
        if ( $value  == $selected_value ) {
            $selected = 'Selected';
        } else {
            $selected = '';
        }

        if ( $cntr == 0 ) {
            $dd .= '<option value="">' . $additional_value . '</option>';
        }

        if ( $multiple == true ) {
            //jika dropdown multi select
            if ( count( $dt_arr_multi ) > 0 ) {
                //Jika nilai sudah ada di db utk user tertentu
                if ( in_array( $value, $dt_arr_multi, true ) ) {
                    $selected = 'Selected';
                } else {
                    $selected = '';
                }
                $dd .= '<option  style="fixed; z-index:99999"  ' . $flag . ' ' . $selected . ' value="' .  $value  . '">' . $value . '</option>';
            } else {
                //jika nilai blum ada di db utk user tertentu
                $dd .= '<option  style="fixed; z-index:99999"  ' . $flag . ' value="' .  $value  . '">' .  $value  . '</option>';
            }
        } else {
            //jika single select
            $dd .= '<option  style="fixed; z-index:99999"  ' . $flag . ' ' . $selected . ' value="' .  $value  . '">' .  $value  . '</option>';
        }

        $cntr++;
    }
    $dd .= '</select>';
    return $dd;
}

function create_select2_db_combo( $id_obj, $nm_obj, $tblname, $key_field, $value_field, $order_field, $additional_value, $selected_value = '', $param = '', $multiple = false, $dt_arr_multi = array() )
 {
    $ci = &get_instance();
    $ci->load->database();
    if ( $order_field != '' ) {
        $sqlorder = " ORDER BY $order_field";
    }
    $sql = "SELECT $key_field, $value_field FROM $tblname $param $sqlorder";
    //  echo $sql .'<br>';
    $query = $ci->db->query( $sql );
    $result = $query->result_array();
    $dd = '';
    $multi = '';
    $tanda = '';
    if ( $multiple == true ) {
        $multi = 'multiple';
        $tanda = '[]';
    }

    $dd .= '<select ' . $multi . '  id="' . $id_obj . '" name="' . $nm_obj . $tanda . '" single class="form-control select2" " >';
    $cntr = 0;
    if ( $additional_value == '' ) {
        //$additional_value = '-Please Select-';
    }

    foreach ( $result as $res ) {
        $r = array_values( $res );
        $flag = '';
        //echo ' data '. $r[0] .' selected '. $selected_value .'<br>';
        if ( $r[0] == $selected_value ) {
            $selected = 'Selected';
        } else {
            $selected = '';
        }

        if ( $cntr == 0 ) {
            $dd .= '<option value="">' . $additional_value . '</option>';
        }

        if ( $multiple == true ) {
            //jika dropdown multi select
            if ( count( $dt_arr_multi ) > 0 ) {
                //Jika nilai sudah ada di db utk user tertentu
                if ( in_array( $r[0], $dt_arr_multi, true ) ) {
                    $selected = 'Selected';
                } else {
                    $selected = '';
                }
                $dd .= '<option   ' . $flag . ' ' . $selected . ' value="' . $r[0] . '">' . $r[1] . '</option>';
            } else {
                //jika nilai blum ada di db utk user tertentu
                $dd .= '<option  style="fixed; z-index:99999"  ' . $flag . ' value="' . $r[0] . '">' . $r[1] . '</option>';
            }
        } else {
            //jika single select
            $dd .= '<option    ' . $flag . ' ' . $selected . ' value="' . $r[0] . '">' . $r[1] . '</option>';
        }

        $cntr++;
    }
    $dd .= '</select>';
    return $dd;
}

function is_data_exist( $tblname, $fieldname, $param )
 {
    $ci = &get_instance();
    $ci->load->database();

    $sql = "SELECT $fieldname FROM $tblname WHERE $param ";
    //echo $sql;
    $query = $ci->db->query( $sql );
    $row = $query->row();
    if ( isset( $row ) ) {
        return true;
    } else {
        return false;
    }
}

function display_menu_adm_lte( $parent = 0 )
 {
    require_once APPPATH . '/libraries/Cryptlib.php';

    $ci = &get_instance();
    $ci->load->database();
    $roles_id = $ci->session->userdata( 'roles_id' );
    $sqlhdr = "SELECT a.id_menu, a.menu_label, a.link_menu, Deriv1.jml, a.parent_id, a.icon_menu  FROM `sys_admin_menu` a
    LEFT OUTER JOIN (SELECT parent_id, COUNT(*) AS jml FROM `sys_admin_menu` GROUP BY parent_id) Deriv1
    ON a.id_menu = Deriv1.parent_id WHERE a.parent_id='$parent' and is_active = 'Y' 
    AND id_menu in (SELECT id_menu FROM sys_menu_role WHERE roles_id in($roles_id)) ORDER BY sort_order";
    $query = $ci->db->query( $sqlhdr ); 

    $result = $query->result();
    $menu_item = '';
 
    $converter = new Encryption;
    foreach ( $result as $row ) {
        if ( $row->jml > 0 ) {

            $menu_item .= "
            <li class='nav-item has-treeview' role='menu' data-accordion='true'>
                <a href='#' class='nav-link'> 
                    <i class='nav-icon fas fa-".$row->icon_menu."'></i> 
                        <p> ".$row->menu_label."<i class='right fas fa-angle-left'></i></p>
                </a>";
            $menu_item .= get_dtl_adm_lte( $row->id_menu );
            $menu_item .= "
            </li>";
        } else {
            $pid = $converter->encode( $row->id_menu );
            if ( strpos( $row->link_menu, '=' ) !== false ) {
                $url_sesid = '&sessid='.$pid;
            } else {
                $url_sesid = '?sessid='.$pid;
            }

            $url_link = "'".base_url().$row->link_menu.$url_sesid."'";
            $container = "'".'web_content'."'";
            $menu_item .= '
            <li class="nav-item has-treeview">
                <a href="#"  onclick="load_content('. $url_link.', '.$container.')"  class="nav-link">
                <i class="nav-icon fas fa-'.$row->icon_menu.'"></i>
                        <p>'.$row->menu_label.'<!--<span class="right badge badge-danger">New</span>--></p>
                </a>
           </li>';
        }
    }
    return $menu_item;
}

function get_dtl_adm_lte( $parent_id )
 {

    $ci = &get_instance();
    $ci->load->database();
    $converter = new Encryption;
    $flag_dashboard = $ci->input->get( 'q' );
    $roles_id = $ci->session->userdata( 'roles_id' );
    $ci->db->select( 'id_menu, menu_label, link_menu, icon_menu' );
    $ci->db->from( 'sys_admin_menu' );
    $ci->db->where( "parent_id ='$parent_id' AND id_menu in (SELECT id_menu FROM sys_menu_role WHERE roles_id in($roles_id))" );
    $ci->db->where( "is_active='Y' " );
    $ci->db->order_by( 'sort_order' );
    $query = $ci->db->get();

    $menu_item_dtl = '';
    if ( $query ) {
        foreach ( $query->result() as $rowdtl ) {
            //  $enc_menu = $converter->encode( $rowdtl->menu_label );
            // $curr_menu = $ci->input->get( 'm' );
            // $dec_menu = $converter->decode( $curr_menu );
            // if ( $dec_menu == $rowdtl->menu_label ) {
            //     $state = 'active';
            // } else {
            //     $state = '';
            // }
            // $url_link = "'".base_url().$rowdtl->link_menu."'";
            $pid = $converter->encode( $rowdtl->id_menu );
            $pid = $converter->encode( $rowdtl->id_menu );
            if ( strpos( $rowdtl->link_menu, '=' ) !== false ) {
                $url_sesid = '&sessid='.$pid;
            } else {
                $url_sesid = '?sessid='.$pid;
            }
            $url_link = "'".base_url().$rowdtl->link_menu.$url_sesid."'";
            $container = "'".'web_content'."'";
            $menu_item_dtl .= '
            <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="#"  onclick="load_content('. $url_link.', '.$container.')" class="nav-link">
              <i class="nav-icon fas fa-'.$rowdtl->icon_menu.'"></i>
                <p>'.$rowdtl->menu_label.'</p>
              </a>
            </li>
          </ul>';
        }
    }
    return $menu_item_dtl;
}



function display_menu_architect_ui( $parent = 0 )
 {
    require_once APPPATH . '/libraries/Cryptlib.php';

    $ci = &get_instance();
    $ci->load->database();
    $roles_id = $ci->session->userdata( 'roles_id' );
    $sqlhdr = "SELECT a.id_menu, a.menu_label, a.link_menu, Deriv1.jml, a.parent_id, a.icon_menu  FROM `sys_admin_menu` a
    LEFT OUTER JOIN (SELECT parent_id, COUNT(*) AS jml FROM `sys_admin_menu` GROUP BY parent_id) Deriv1
    ON a.id_menu = Deriv1.parent_id WHERE a.parent_id='$parent' and is_active = 'Y' 
    AND id_menu in (SELECT id_menu FROM sys_menu_role WHERE roles_id in($roles_id)) ORDER BY sort_order";
    $query = $ci->db->query( $sqlhdr ); 

    $result = $query->result();
    $menu_item = '';
    // $actual_link = ( isset( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http' ) . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    //$arr_actual_link = explode( '/', $actual_link );
    //$ubound = sizeof( $arr_actual_link ) - 1;

    $table_unread = [
        'main/aspirasi' => 'tbl_aspirasi',
        'main/ide' => 'tbl_ide_startup'
    ];

    $query_unread_aspirasi = "Select count(*) from tbl_aspirasi where `read` = 0";
    $query_unread_aspi = "Select count(*) from tbl_aspirasi where `read` = 0";

    $converter = new Encryption;
    foreach ( $result as $row ) {
        if ( $row->jml > 0 ) {

            $menu_item .= "
            <li>
                <a href='#' class='id_menu' id='id_menu-".$row->id_menu."'>
                    <i class='metismenu-icon pe-7s-".$row->icon_menu."'></i>
                        ".$row->menu_label."
                        <i class='metismenu-state-icon pe-7s-angle-down caret-left'></i>
                </a>";
            $menu_item .= get_dtl_architect_ui( $row->id_menu );
            $menu_item .= "
            </li>";
        } else {
            $pid = $converter->encode( $row->id_menu );
            if ( strpos( $row->link_menu, '=' ) !== false ) {
                $url_sesid = '&sessid='.$pid;
            } else {
                $url_sesid = '?sessid='.$pid;
            }

            $url_link = "'".base_url().$row->link_menu.$url_sesid."'";
            $container = "'".'web_content'."'";
            $null = "''";

            $unread_notif = '';
            if($row->link_menu == 'main/aspirasi'|| $row->link_menu == 'main/ide' ) {
                $sql_unread = "Select count(*) as unread_notif from ".$table_unread[$row->link_menu]." where `read` = 0";
                $query_unread = $ci->db->query( $sql_unread ); 
                $result_unread = $query_unread->result_array();
                $unread_notif = ' ('.$result_unread[0]['unread_notif'].')';
            }

            $menu_item .= '
            <li>
                <a href="#" class="id_menu" id="id_menu-'.$row->id_menu.'" onclick="load_content('. $url_link.', '.$container.', '.$null.', '.$null.','.$row->id_menu.' )">
                <i class="metismenu-icon pe-7s-'.$row->icon_menu.'"></i>
                        <p>'.$row->menu_label.'<span class="notif_menu-'.$row->id_menu.'">'.$unread_notif.'</span></p>
                </a>
           </li>';
        }
    }
    return $menu_item;
}

function get_dtl_architect_ui( $parent_id )
 {

    $ci = &get_instance();
    $ci->load->database();
    $converter = new Encryption;
    $flag_dashboard = $ci->input->get( 'q' );
    $roles_id = $ci->session->userdata( 'roles_id' );
    $ci->db->select( 'id_menu, menu_label, link_menu, icon_menu' );
    $ci->db->from( 'sys_admin_menu' );
    $ci->db->where( "parent_id ='$parent_id' AND id_menu in (SELECT id_menu FROM sys_menu_role WHERE roles_id in($roles_id))" );
    $ci->db->where( "is_active='Y' " );
    $ci->db->order_by( 'sort_order' );
    $query = $ci->db->get();

    $menu_item_dtl = '';
    if ( $query ) {
        foreach ( $query->result() as $rowdtl ) {
            //  $enc_menu = $converter->encode( $rowdtl->menu_label );
            // $curr_menu = $ci->input->get( 'm' );
            // $dec_menu = $converter->decode( $curr_menu );
            // if ( $dec_menu == $rowdtl->menu_label ) {
            //     $state = 'active';
            // } else {
            //     $state = '';
            // }
            // $url_link = "'".base_url().$rowdtl->link_menu."'";
            $pid = $converter->encode( $rowdtl->id_menu );
            $pid = $converter->encode( $rowdtl->id_menu );
            if ( strpos( $rowdtl->link_menu, '=' ) !== false ) {
                $url_sesid = '&sessid='.$pid;
            } else {
                $url_sesid = '?sessid='.$pid;
            }
            $url_link = "'".base_url().$rowdtl->link_menu.$url_sesid."'";
            $container = "'".'web_content'."'";
            $null = "''";
            $menu_item_dtl .= '
            <ul>
            <li>
              <a href="#" class="id_menu" id="id_menu-'.$rowdtl->id_menu.'" onclick="load_content('. $url_link.', '.$container.', '.$null.', '.$null.','.$rowdtl->id_menu.' )" >
              <i class="metismenu-icon"></i>
                '.$rowdtl->menu_label.'
              </a>
            </li>
          </ul>';
        }
    }
    return $menu_item_dtl;
}




function display_tree_menu( $parent = '' )
 {
    $ci = &get_instance();
    $ci->load->database();
    $roles_id = $ci->session->userdata( 'role_id' );
    $menu_spec = get_sys_setting( '008' );

    $sqlhdr = "SELECT a.id_menu, a.menu_label, a.sort_order, a.link_menu, Deriv1.Count, a.parent_id, a.icon_menu  FROM `sys_front_menu` a
    LEFT OUTER JOIN (SELECT parent_id, COUNT(*) AS Count FROM `sys_front_menu` GROUP BY parent_id) Deriv1
    ON a.id_menu = Deriv1.parent_id WHERE a.parent_id='$parent' and is_active = 'Y'";
    $queryhdr = $ci->db->query( $sqlhdr );

    // $menu_item = "<ul  id='tree1' class='sortable'>";
    $script = '';
    $menu_item = "<ul  id='tree1' class='sortable" . $parent . "'>";
    $script .= "<script>
      $( function() {
      $( '.sortable" . $parent . "' ).sortable({
        placeholder: 'ui-state-highlight',
        update: function( event, ui ) {
        //var sorted = $( '.sortable" . $parent . "' ).sortable( 'serialize');
       // data_id = $( '.sortable" . $parent . "' ).sortable('toArray', {attribute: 'data-item'});

        var dataObj = {};
        var attribArray = [];
        $('.sortable" . $parent . " li').each(function(el,i){
            var obj = new Object;
            attribArray.push({'id' : $(this).attr('data-id'), 'parent' : $(this).attr('data-parent'), 'sortorder' : $(this).attr('data-sortorder') })
        })
        dataObj['data'] = attribArray;
        alert(JSON.stringify(dataObj));

        $.ajax({
           url: 'update_sort_order_adm',
           type: 'POST',
           data: {data_order: sorted},
           success: function (data) {
              //  alert(data);
           }
          });
          }
      });
      $( '.sortable" . $parent . "' ).disableSelection();
      } );
      </script>";

    foreach ( $queryhdr->result() as $row ) {
        if ( $row->Count > 0 ) {
            $menu_item .= '<li data-id=' . $row->id_menu . ' data-parent=' . $row->parent_id . ' data-sortorder=' . $row->sort_order . " class='ui-state-default'  class='folder' title='Bookmarks'>" . $row->menu_label . "&nbsp;<button style='margin-top:-2px' type='button' class='btn btn-warning btn-xs align-top'><i class='fa fa-link'></i> Add sub menu </button>";
            $menu_item .= display_tree_menu( $row->id_menu );
            $menu_item .= '</li>';
        } elseif ( $row->Count == 0 ) {
            $menu_item .= '<li  data-id=' . $row->id_menu . ' data-parent=' . $row->parent_id . ' data-sortorder=' . $row->sort_order . " class='ui-state-default align-top' >" . $row->menu_label . "<button style='margin-top:-3px' type='button' class='btn btn-success btn-xs pull-right align-top' onclick=javascript:editdata('" . $row->id_menu . "')><i class='fa fa-link'></i> Maintain this Menu</button></li>";
        } else {
            ;
        }
    }
    $menu_item .= '</ul>';

    return $menu_item . $script;
}

function get_breadcrumb_info( $idmenu )
 {
    $ci = &get_instance();
    $ci->load->database();
    $item_bc = '';
    $sql = "SELECT b.menu_label main, a.menu_label child
            FROM sys_admin_menu AS a
            LEFT JOIN sys_admin_menu AS b ON a.parent_id = b.id_menu
            WHERE a.id_menu ='$idmenu' ";
    $query = $ci->db->query( $sql );
    $rows = $query->row();
    if ( isset( $rows ) ) {
        if ( $rows->main == '' ) {
            $item_bc = array( $rows->child );
        } else {
            $item_bc = array( $rows->main, $rows->child );
        }
    }
    return $item_bc;
}

function get_info_by_id( $tblname, $fieldinfo, $field_id, $nilai )
 {
    $ci = &get_instance();
    //load databse library
    $ci->load->database();

    $sql = "SELECT  $fieldinfo FROM $tblname WHERE $field_id = '$nilai'";
    // echo $sql.'<BR>';
    $query = $ci->db->query( $sql );
    $rows = $query->row();
    if ( isset( $rows ) ) {
        $info = $rows->$fieldinfo;
    } else {
        $info = '';
    }
    return $info;
}

function get_info_as_array($tblname, $fieldinfo, $param){
    $ci = &get_instance();
    $sql = "SELECT  $fieldinfo FROM $tblname $param "; //echo $sql ."<BR>";
    $query = $ci->db->query($sql);
    return ($query->num_rows() > 0) ? $query->result_array() : false;
}

function get_info_by_id_global_param( $tblname, $fieldinfo, $param )
 {
    $ci = &get_instance();
    //load databse library
    $ci->load->database();

    $sql = "SELECT  $fieldinfo FROM $tblname $param ";
    //echo $sql;
    $query = $ci->db->query( $sql );
    $rows = $query->row();
    if ( isset( $rows ) ) {
        $info = $rows->$fieldinfo;
    } else {
        $info = '';
    }
    return $info;
}

function get_sys_setting( $id )
 {
    $ret_val = '';
    //get main CodeIgniter object
    $ci = &get_instance();

    //load databse library
    $ci->load->database();

    //get data from database
    $sql = "SELECT value_setting FROM sys_settings WHERE id_setting = '$id'";
    $query = $ci->db->query( $sql );
    $row = $query->row();
    if ( $row ) {
        $ret_val = $row->value_setting;
    }
    return $ret_val;
}

function generate_table( $array_title, $array_data )
 {

    $Rows = count( $array_data );
    $Cols = count( $array_title );
    echo '<table class="table table-condensed table-bordered tablesorter">';
    echo '<thead>';
    foreach ( $array_title as $title ) {
        echo '<th>' . $title . '</th>';
    }
    echo '</thead>';
    echo '<tbody>';
    foreach ( $array_data as $data ) {
        $r = array_values( $data );
        echo '<tr>';
        for ( $j = 0; $j <= $Cols - 1; $j++ ) {
            echo '<td>' . $r[$j] . '</td>';
        }
        echo '</tr>';
    }
    echo '</tbody>';

    echo '</table>';
}

function get_menu_by_role_id( $role_id )
 {
    $ci = &get_instance();
    $ci->load->database();
    $ci->db->select( 'id_user_role, b.roles_name, c.menu_label' );
    $ci->db->from( 'sys_menu_role a' );
    $ci->db->join( 'sys_roles b', 'a.roles_id=b.roles_id', 'inner' );
    $ci->db->join( 'sys_admin_menu c', 'c.id_menu=a.id_menu', 'inner' );
    $ci->db->where( "b.roles_id='$role_id'" );
    $query = $ci->db->get();
    $menu = '';
    foreach ( $query->result() as $row ) {
        $menu .= ' <span class="badge badge-info">' . $row->menu_label . '</span>';
    }

    return $menu;
}

function get_menu_id( $id_encode = '' )
 {
    require_once APPPATH . '/libraries/Cryptlib.php';
    $converter = new Encryption;
    $id_menu = $converter->decode( $id_encode );
    return $id_menu;
}

function format_date_as_db_format( $strdate, $oriformat = 'd/m/Y', $db_ormat = 'Y-m-d' )
 {
    $date = DateTime::createFromFormat( 'd/m/Y', $strdate );
    return $date->format( 'Y-m-d' );
}

function format_date_as_id_format( $strdate, $oriformat = 'Y-m-d' )
 {
    $date = DateTime::createFromFormat( 'Y-m-d', $strdate );
    if ( $date ) {
        return $date->format( 'd/m/Y' );
    } else {
        return '';
    }
}

function indonesian_date( $timestamp = '', $date_format = 'l, j F Y | H:i', $suffix = 'WIB' )
 {
    date_default_timezone_set( 'Asia/Bangkok' );
    if ( trim( $timestamp ) == '' ) {
        $timestamp = time();
    } elseif ( !ctype_digit( $timestamp ) ) {
        $timestamp = strtotime( $timestamp );
    }
    # remove S ( st, nd, rd, th ) there are no such things in indonesia :p
    $date_format = preg_replace( '/S/', '', $date_format );
    $pattern = array(
        '/Mon[^day]/', '/Tue[^sday]/', '/Wed[^nesday]/', '/Thu[^rsday]/',
        '/Fri[^day]/', '/Sat[^urday]/', '/Sun[^day]/', '/Monday/', '/Tuesday/',
        '/Wednesday/', '/Thursday/', '/Friday/', '/Saturday/', '/Sunday/',
        '/Jan[^uary]/', '/Feb[^ruary]/', '/Mar[^ch]/', '/Apr[^il]/', '/May/',
        '/Jun[^e]/', '/Jul[^y]/', '/Aug[^ust]/', '/Sep[^tember]/', '/Oct[^ober]/',
        '/Nov[^ember]/', '/Dec[^ember]/', '/January/', '/February/', '/March/',
        '/April/', '/June/', '/July/', '/August/', '/September/', '/October/',
        '/November/', '/December/',
    );
    $replace = array(
        'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min',
        'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu',
        'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des',
        'Januari', 'Februari', 'Maret', 'April', 'Juni', 'Juli', 'Agustus', 'September',
        'Oktober', 'November', 'Desember',
    );
    $date = date( $date_format, $timestamp );
    $date = preg_replace( $pattern, $replace, $date );
    $date = "{$date} {$suffix}";
    return $date;
}

function getsysdate( $format = 'Y-m-d H:i:s' )
 {
    date_default_timezone_set( 'Asia/Bangkok' );
    $sysdate = date( $format );
    return $sysdate;
}

function getsysdate_without_time( $format = 'Y-m-d' )
 {
    date_default_timezone_set( 'Asia/Bangkok' );
    $sysdate = date( $format );
    return $sysdate;
}

function fcharword( $string, $upper = true )
 {
    $words = explode( ' ', $string );
    $acronym = '';

    foreach ( $words as $w ) {
        if ( $upper == true ) {
            $fchar = strtoupper( $w[0] );
        }
        $acronym .= $fchar;
    }
    return $acronym;
}

function generateNumber( $idSelect, $much, $selected = '' )
 {
    $html = "<select id='$idSelect' name='$idSelect' class=' form-control chosen-select' tabindex='8'>
    <option value=''></option>";

    for ( $i = 1; $i <= $much; $i++ ) {
        $s = str_pad( $i, 2, '0', STR_PAD_LEFT );

        if ( $selected == $s ) {
            $html .= "<option value='$s' selected >$s</option>";
        } else {
            $html .= "<option value='$s'>$s</option>";
        }
    }

    $html .= '</select>';

    return $html;
}

/**
* Fungsi ini digunakan untuk menghapus sparator ribuan
* Pada saat data di simpan ke dalam database
* @param String $str
* @return String
*/

function removeThousandSparator( $str )
 {

    $arr_spa = explode( '.', $str );
    $dtcount = count( $arr_spa );
    $numeric_simbol = array();
    if ( $dtcount > 1 ) {
        if ( $arr_spa[1] == '00' ) {
            $value_to_remove = $arr_spa[0];
            $numeric_simbol = array( ',' );
        } else {
            $value_to_remove = $str;
            $numeric_simbol = array( ',' );
        }
    } else {
        $value_to_remove = $str;
    }

    $value = str_replace( $numeric_simbol, '', $value_to_remove );
    return $value;
}

function get_extension( $file )
 {
    $extension = explode( '.', $file );
    $ext = $extension[1];
    return $ext;
}

function get_icon_file( $fname )
 {
    $ext = get_extension( $fname );
    $icon_file = base_url() . 'img/icon/' . $ext . '_ico.png';
    return $icon_file;
}

function postCURL( $url, $data )
 {
    $api_key = get_sys_setting( '011' );
    $api_end_point = get_sys_setting( '012' );
    $url = $api_end_point . $url;

    $curl_handle = curl_init();

    $data = http_build_query( $data );

    curl_setopt( $curl_handle, CURLOPT_HTTPHEADER, array(
        'api_auth_key: ' . $api_key . '',
    ) );
    curl_setopt( $curl_handle, CURLOPT_URL, $url );
    curl_setopt( $curl_handle, CURLOPT_POSTFIELDS, $data );
    curl_setopt( $curl_handle, CURLOPT_POST, 1 );
    curl_setopt( $curl_handle, CURLOPT_TIMEOUT, 30 );
    //curl_setopt( $curl_handle, CURLOPT_HTTPAUTH, CURLAUTH_ANY );
    curl_setopt( $curl_handle, CURLOPT_RETURNTRANSFER, 1 );
    $output = curl_exec( $curl_handle );

    curl_close( $curl_handle );

    return $output;
}

function resizeImage( $filename, $source_path, $target_path )
 {
    $ci = &get_instance();
    $source = $source_path . $filename;
    $target = $target_path;

    $config_manip = array(
        'quality' => '70%',
        'image_library' => 'gd2',
        'source_image' => $source,
        'new_image' => $target,
        'maintain_ratio' => TRUE,
        'width' => 446,
        'height' => 576,
    );

    // $ci->load->library( 'image_lib', $config_manip );
    $ci->load->library( 'image_lib');
    $ci->image_lib->initialize($config_manip);
    if ( !$ci->image_lib->resize() ) {
        echo '<br>compression result :' . $source . $ci->image_lib->display_errors();
    }
}

function stringIsImage( $content )
 {
    // check if string ends with image extension
    if ( preg_match( '/(\.jpg|\.png|\.bmp|\.jpeg)$/', $content ) ) {
        return 'image';
        // check if there is youtube.com in string
    } elseif ( strpos( $content, 'youtube.com' ) !== false ) {
        return 'youtube';
        // check if there is vimeo.com in string
    } elseif ( strpos( $content, 'vimeo.com' ) !== false ) {
        return 'vimeo';
    } else {
        return 'text';
    }
}

// FUNGSI TERBILANG OLEH : MALASNGODING.COM
// WEBSITE : WWW.MALASNGODING.COM
// AUTHOR : https://www.malasngoding.com/author/admin

function penyebut( $nilai )
 {
    $nilai = abs( $nilai );
    $huruf = array( '', 'satu', 'dua', 'tiga', 'empat', 'lima', 'enam', 'tujuh', 'delapan', 'sembilan', 'sepuluh', 'sebelas' );
    $temp = '';
    if ( $nilai < 12 ) {
        $temp = ' ' . $huruf[$nilai];
    } else if ( $nilai < 20 ) {
        $temp = penyebut( $nilai - 10 ) . ' belas';
    } else if ( $nilai < 100 ) {
        $temp = penyebut( $nilai / 10 ) . ' puluh' . penyebut( $nilai % 10 );
    } else if ( $nilai < 200 ) {
        $temp = ' seratus' . penyebut( $nilai - 100 );
    } else if ( $nilai < 1000 ) {
        $temp = penyebut( $nilai / 100 ) . ' ratus' . penyebut( $nilai % 100 );
    } else if ( $nilai < 2000 ) {
        $temp = ' seribu' . penyebut( $nilai - 1000 );
    } else if ( $nilai < 1000000 ) {
        $temp = penyebut( $nilai / 1000 ) . ' ribu' . penyebut( $nilai % 1000 );
    } else if ( $nilai < 1000000000 ) {
        $temp = penyebut( $nilai / 1000000 ) . ' juta' . penyebut( $nilai % 1000000 );
    } else if ( $nilai < 1000000000000 ) {
        $temp = penyebut( $nilai / 1000000000 ) . ' milyar' . penyebut( fmod( $nilai, 1000000000 ) );
    } else if ( $nilai < 1000000000000000 ) {
        $temp = penyebut( $nilai / 1000000000000 ) . ' trilyun' . penyebut( fmod( $nilai, 1000000000000 ) );
    }
    return $temp;
}

function terbilang( $nilai )
 {
    if ( $nilai < 0 ) {
        $hasil = 'minus ' . trim( penyebut( $nilai ) );
    } else {
        $hasil = trim( penyebut( $nilai ) );
    }
    return $hasil;
}
