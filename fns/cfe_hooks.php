<?php
    function check_form_edit(){
        $status = get_option('status');
        if($status == "open"){
            update_option('form_lock', 'no');
        } else {
            update_option('form_lock', 'yes');
        }
    }
    add_action('cust_pre_render', 'check_form_edit');
?>