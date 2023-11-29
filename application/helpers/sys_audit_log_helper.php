<?php
function record_audit_log($process_name, $process_detail, $user_id){
    $ci = &get_instance();
    $arr_data = array(
        'process_name' => $process_name,
        'process_detail' =>$process_detail,
        'process_date' =>getauditdate(),
        'user_id' => $user_id,
    );
    $ci->db->insert("sys_audit_log", $arr_data);
}

function getauditdate($format = "Y-m-d H:i:s")
{
    date_default_timezone_set("Asia/Bangkok");
    $sysdate = date($format);
    return $sysdate;
}
