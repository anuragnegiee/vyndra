<?php
/*
  Plugin Name:  Qikink Print On Demand and DropShipping
  Plugin URI:   https://www.qikink.com
  Description:  A plugin to integrate your woocommerce site with Qikink.
  Version:      1.1.2
  Author:       Qikink
 */



add_action('admin_enqueue_scripts', 'qikink_my_enqueue');

function qikink_my_enqueue($hook) {
    // Only load assets on the Qikink admin page
    if (isset($_GET['page']) && $_GET['page'] === 'qikink') {
        // Enqueue Bootstrap CSS
        wp_register_style('prefix_bootstrap', plugins_url('/assets/bootstrap-5.2.3-dist/css/bootstrap.min.css', __FILE__));
        wp_enqueue_style('prefix_bootstrap');

        // Enqueue custom CSS
        wp_register_style('local_css', plugins_url('/assets/Qikink.css', __FILE__));
        wp_enqueue_style('local_css');

        // Enqueue custom JS with jQuery dependency, loaded in footer
        wp_enqueue_script('huepress-script', plugins_url('/Qikink.js', __FILE__), array('jquery'), '1.0.2', true);

        // Localize script with AJAX data
        wp_localize_script('huepress-script', 'ajax_object', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'client_id' => 1234
        ));
    }
}

function qikink_fx_admin_notice_example_activation_hook() {
    set_transient('qikink_transient', true, 5);
}

add_action('admin_notices', 'qikink_fx_admin_notice_example_notice');

function qikink_fx_admin_notice_example_notice() {
    if (get_transient('qikink_transient')) {
        ?>
        <div class="updated notice is-dismissible">
            <p>Congrats! <strong>Qikink Plugin activated successfully</strong>.</p>
        </div>
        <?php
        delete_transient('qikink_transient');
    }
}


add_action('wp_ajax_qikink_endpoint', 'qikink_endpoint');

function qikink_endpoint() {
    $client_id = sanitize_text_field($_POST['client_id']);
    $store_url = get_site_url();
    $endpoint = '/wc-auth/v1/authorize';
    $params = [
        'app_name' => 'Qikink',
        'scope' => 'read_write',
        'user_id' => $client_id,
        'return_url' => get_site_url() . '/wp-admin/admin.php?page=qikink',
        'callback_url' => 'https://fulfilment.qikink.com/index.php/Autoc/get_woocommerce_keys'
    ];
    $query_string = http_build_query($params);
    $url = $store_url . $endpoint . '?' . $query_string;
    echo json_encode(array("url" => $url));
    exit;
}

function qikink_admin_menu() {
    add_menu_page('Huepress', 'Huepress', 'manage_options', 'qikink', 'qikink_woo_menu_signin', 'dashicons-admin-network', 7);
//    add_submenu_page('qikink', 'Huepress|Signup', 'Signupname', 'manage_options', 'SignUpPage', 'woo_menu_signup', 1);
}

add_action('admin_menu', 'qikink_admin_menu');
add_action('wp_ajax_get_qikink_email', 'get_qikink_email');

add_filter( 'plugin_action_links_qikink-pod-and-drop-shipping/huepress.php', 'qikink_settings' );
function qikink_settings( $links) {
	// Build and escape the URL.
	$url = esc_url( add_query_arg(
		'page',
		'qikink',
		get_admin_url() . 'admin.php'
	) );
	// Create the link.
	$settings_link = "<a href='$url'>" . __( 'Settings' ) . '</a>';
	// Adds the link to the end of the array.
	array_push(
		$links,
		$settings_link
	);
	return $links;
}

function get_qikink_email() {
  
    $qikink_email = sanitize_email($_POST['EmailId']);
    $qikink_url = "https://fulfilment.qikink.com/index.php/autoc/wp_plugin";
//    $qikink_url = "http://localhost/qikink_elite/index.php/autoc/wp_plugin";
    
    $qikink_params = array(
        "EmailId" => $qikink_email,
        "store_url" => get_site_url(),
       
    );
    $encrypted_qikink_params= qikink_encryptor($qikink_params);
    $args = array(
            'body' => array("encrypted_data" => $encrypted_qikink_params),
            'timeout'     => '5',
        );
 
    $result = wp_remote_post($qikink_url, $args);
    $res_decode = json_decode($result['body']);
//    echo '<pre>' . var_export($result['body'], true) . '</pre>';exit;
//    $res_decode = json_decode($result);
//    var_dump($result);exit;
//    echo $res_decode;exit;
    if ($res_decode->status && isset($res_decode->status)) {
        echo json_encode(array("msg" => "success", "client_id" => $res_decode->client_id));
    } else {
        echo json_encode(array("msg" => "failure", "txt" => $res_decode->msg));
    }

    wp_die(); // this is required to terminate immediately and return a proper response
}

add_action('wp_ajax_get_qikink_otp', 'get_qikink_otp');

function get_qikink_otp() {
   
    $qikink_otp = sanitize_text_field($_POST['otp']);
    $client_id = sanitize_text_field($_POST['client_id']);
    $qikink_url = "https://fulfilment.qikink.com/index.php/autoc/wp_plugin_otp";
//    $qikink_url = "http://localhost/qikink_elite/index.php/autoc/wp_plugin_otp";
    $qikink_params = array(
        "otp" => $qikink_otp,
        "client_id" => $client_id
    );
    $encrypted_qikink_params = qikink_encryptor($qikink_params);
    
    $args = array(
            'body' => array("encrypted_data" => $encrypted_qikink_params),
            'timeout' => '5',
        );
    
    $result = wp_remote_post($qikink_url, $args);
    $res_decode = json_decode($result['body']);
//    $res_decode = json_decode($result);

    if ($res_decode->status && isset($res_decode->status)) {

        echo json_encode(array("msg" => "Success"));
    } else {
        echo json_encode(array("msg" => "failure"));
    }
    wp_die();
}

function qikink_woo_menu_signin() {
    remove_menu_page('Adminmenu');
  
        $qikink_url = "https://fulfilment.qikink.com/index.php/autoc/check_install";
//        $qikink_url = "http://localhost/qikink_elite/index.php/autoc/check_install";
        $qikink_params = array(
            "Store_URL" => get_site_url()
        );
         $encrypted_qikink_params= qikink_encryptor($qikink_params);
        $args = array(
            'body' => array("encrypted_data" => $encrypted_qikink_params),
            'timeout'     => '5',
        );
        
//        var_dump($qikink_params);exit;

        $result = wp_remote_post( $qikink_url, $args );
        $sdata= json_decode($result['body']);
//        echo '<pre>' . var_export($result, true) . '</pre>';exit;
//        $sdata = json_decode($result);
        if ($sdata && isset($sdata->client_id)) {
//            var_dump($sdata);exit;
            include( plugin_dir_path(__FILE__) . 'includes/Qikink_Thank_you.php');
            $obj=new Qikink_Thank_you();
            $obj->qikinkindex($sdata->client_business_name);
        } else {
            include( plugin_dir_path(__FILE__) . 'includes/Qikink_Verfication_page.php');
            $obj->index();
        }
    
}

function qikink_encryptor($qikink_params){
    $simple_string = json_encode($qikink_params);
    $ciphering = "AES-128-CTR";
    $iv_length = openssl_cipher_iv_length($ciphering);
    $options = 0;
    $encryption_iv = '8929770667187677';
    $encryption_key = "qikinkhuepress";
    $encryption = openssl_encrypt($simple_string, $ciphering,
                $encryption_key, $options, $encryption_iv);
    return trim($encryption);
}


function delete_plugin_data(){
    //        $qikink_url = "https://qikink.com/erp2/index.php/autoc/check_install";
        $qikink_url = "https://fulfilment.qikink.com/index.php/autoc/wordpress_uninstall";
        $qikink_params = array(
            "Store_URL" => get_site_url()
        );
         $encrypted_qikink_params= qikink_encryptor($qikink_params);
        $args = array(
            'body' => array("encrypted_data" => $encrypted_qikink_params),
            'timeout'     => '5',
        );
        
//        var_dump($qikink_params);exit;

         wp_remote_post( $qikink_url, $args );
}

register_deactivation_hook( __FILE__, 'delete_plugin_data' );

?>