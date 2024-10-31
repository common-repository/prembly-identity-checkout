<?php

    // add_action("init", "my_script_enqueuer");
    // add_action("wp_ajax_my_price_request", "my_price_request");

    // function my_price_request(){

    //     if(!wp_verify_nonce( $_REQUEST['nonce'], "my_nonce")) {
    //     exit();
    //     }

    //     $result['type'] = 'success';
    //     $result['data'] = $_REQUEST['data'];

    //     if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    //     $result = json_encode($result);
    //     echo $result;
    //     }else{
    //     header("Location: ".$_SERVER["HTTP_REFERER"]);
    //     }

    //     die();
    // }

    // function my_script_enqueuer() {
    //     wp_register_script( "my_form_script", WP_PLUGIN_URL.'/myFormProcessor/my_form_ajax_script.js', array('jquery') );
    //     wp_localize_script( 'my_form_script', 'myAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));        

    //     wp_enqueue_script('jquery');
    //     wp_enqueue_script('my_form_script');
    // }
?>