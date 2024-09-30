<?php
/**
* Real Estate Management Main Class - Since 1.0.0
*/

class CAN_Email
{
    
    function __construct(){
        add_action( 'can_new_seller_registered', array($this, 'seller_registered' ), 10, 1 );
        add_action( 'can_new_seller_approved', array($this, 'seller_approved' ), 10, 1 );
        add_action( 'can_new_seller_rejected', array($this, 'seller_rejected' ), 10, 1 );
        add_action( 'can_new_listing_submitted', array($this, 'new_listing_submitted' ), 10, 1 );
        add_action( 'can_new_listing_approved', array($this, 'new_listing_approved' ), 10, 1 );

        // Contact Seller
        add_action( 'wp_ajax_can_contact_seller', array($this, 'contact_seller' ) );
        add_action( 'wp_ajax_nopriv_can_contact_seller', array($this, 'contact_seller' ) );
    }

    function send_email($to, $subject, $message){       
        $site_title = get_bloginfo();
        $admin_email = apply_filters( "can_admin_email", get_bloginfo('admin_email') );

        $from_title = apply_filters( 'can_email_sender_title', $site_title );
        $from_email = apply_filters( 'can_email_sender_email', $admin_email );

        $headers = array();
        $headers[] = "From: {$from_title} <{$from_email}>";
        $headers[] = "Content-Type: text/html";
        $headers[] = "MIME-Version: 1.0\r\n";

        $headers = apply_filters( 'can_email_headers', $headers );
        if (can_get_option('email_br', 'enable') == 'enable') {
    	   $message = nl2br(stripcslashes($message)); 
        }
    	wp_mail( $to, $subject, $message, $headers );
    }

    function seller_registered($new_agent){
        // Sending Email to Admin
        $admin_email = apply_filters( "can_admin_email", get_bloginfo('admin_email') );
        $subject = __( 'New Seller Registered', 'circular-arts-network' );
        $message = (can_get_option('to_admin_on_seller_register') != '') ? can_get_option('to_admin_on_seller_register') : 'New seller is registered...' ;

        $message = str_replace("%first_name%", $new_agent['first_name'], $message);
        $message = str_replace("%last_name%", $new_agent['last_name'], $message);
        $message = str_replace("%username%", $new_agent['username'], $message);
        $message = str_replace("%seller_email%", $new_agent['useremail'], $message);
	
        $this->send_email($admin_email, $subject, $message);

        do_action('wpml_switch_language_for_email', $new_agent['useremail']);

        // Sending Email to Seller
        $subject_agent = __( 'Registration Successful', 'circular-arts-network' );

        $message_for_agent = (can_get_option('to_seller_registered') != '') ? can_get_option('to_seller_registered') : 'Please wait for approval' ;
        
        $message_for_agent = str_replace("%first_name%", $new_agent['first_name'], $message_for_agent);
        $message_for_agent = str_replace("%last_name%", $new_agent['last_name'], $message_for_agent);
        $message_for_agent = str_replace("%username%", $new_agent['username'], $message_for_agent);
        $message_for_agent = str_replace("%seller_email%", $new_agent['useremail'], $message_for_agent);

        $this->send_email($new_agent['useremail'], $subject_agent, $message_for_agent);

        do_action('wpml_restore_language_from_email');
    }

    function seller_approved($new_agent){

        do_action('wpml_switch_language_for_email', $new_agent['useremail']);
        
        $site_title = get_bloginfo();
        
        $subject = __( 'Approved ', 'circular-arts-network' ). $site_title;

        $message_for_agent = (can_get_option('to_seller_approved') != '') ? can_get_option('to_seller_approved') : 'You are Approved' ;
        
        $message_for_agent = str_replace("%username%", $new_agent['username'], $message_for_agent);
        $message_for_agent = str_replace("%first_name%", $new_agent['first_name'], $message_for_agent);
        $message_for_agent = str_replace("%last_name%", $new_agent['last_name'], $message_for_agent);
        $message_for_agent = str_replace("%seller_email%", $new_agent['useremail'], $message_for_agent);

        $this->send_email($new_agent['useremail'], $subject, $message_for_agent);
        
        do_action('wpml_restore_language_from_email');
    }

    function seller_rejected($new_agent){

        $site_title = get_bloginfo();

        $subject = __( 'Rejected ', 'circular-arts-network' ). $site_title;

        $message_for_agent = (can_get_option('to_seller_rejected') != '') ? can_get_option('to_seller_rejected') : 'You are Approved' ;
        
        $message_for_agent = str_replace("%username%", $new_agent['username'], $message_for_agent);
        $message_for_agent = str_replace("%first_name%", $new_agent['first_name'], $message_for_agent);
        $message_for_agent = str_replace("%last_name%", $new_agent['last_name'], $message_for_agent);
        $message_for_agent = str_replace("%seller_email%", $new_agent['useremail'], $message_for_agent);

        $this->send_email($new_agent['useremail'], $subject, $message_for_agent);
    }

    function new_listing_submitted($listing_id){

        $site_title = get_bloginfo();

        $current_user_data = wp_get_current_user();

        $approve_url = admin_url( 'edit.php?post_status=pending&post_type=can_property' );

        $admin_email = apply_filters( "can_admin_email", get_bloginfo('admin_email') );

        do_action('wpml_switch_language_for_email', $admin_email);
        
        $subject = __( 'New Listing Submitted ', 'circular-arts-network' ). $site_title;
        $message = (can_get_option('to_admin_submission') != '') ? can_get_option('to_admin_submission') : 'New Listing is created. Approve here '.$approve_url ;
        $message = str_replace("%username%", $current_user_data->user_login, $message);
        $message = str_replace("%seller_email%", $current_user_data->user_email, $message);
        $message = str_replace("%approve_url%", $approve_url, $message);

        $subject_agent = __( 'Listing Submitted ', 'circular-arts-network' ). $site_title;
        $this->send_email($admin_email, $subject, $message);

        do_action('wpml_restore_language_from_email');

        do_action('wpml_switch_language_for_email', $current_user_data->user_email);

        $message_agent = (can_get_option('to_seller_submission') != '') ? can_get_option('to_seller_submission') : 'New Listing is submitted. Please wait until admin approves.' ;
        $message_agent = str_replace("%listing_id%", $listing_id, $message_agent);
        $message_agent = str_replace("%listing_url%", get_permalink($listing_id), $message_agent);
        $message_agent = str_replace("%listing_title%", get_the_title($listing_id), $message_agent);        
        $this->send_email($current_user_data->user_email, $subject_agent, $message_agent);
        
        do_action('wpml_restore_language_from_email');
    }

    function new_listing_approved($listing_id){
        $seller_id = get_post_field( 'post_author', $listing_id );
        $seller_info = get_userdata($seller_id);
        $seller_email = $seller_info->user_email;
        
        do_action('wpml_switch_language_for_email', $seller_email);
        
        $site_title = get_bloginfo();
        $subject_agent = __( 'Listing Approved ', 'circular-arts-network' ). $site_title;

        $message_agent = (can_get_option('to_seller_submission_approved') != '') ? can_get_option('to_seller_submission_approved') : 'Your Listing is approved.' ;
        $message_agent = str_replace("%listing_id%", $listing_id, $message_agent);
        $message_agent = str_replace("%listing_url%", get_permalink($listing_id), $message_agent);
        $message_agent = str_replace("%listing_title%", get_the_title($listing_id), $message_agent);
        $this->send_email($seller_email, $subject_agent, $message_agent);

        do_action('wpml_restore_language_from_email');
    }

    function contact_seller(){
        if (isset($_REQUEST) && $_REQUEST != '') {

            if (isset($_REQUEST['g-recaptcha-response'])) {
                if (!$_REQUEST['g-recaptcha-response']) {
                    $resp = array('fail' => 'already', 'msg' => __( 'Please check the captcha form.', 'circular-arts-network' ));
                    echo json_encode($resp); exit;
                } else {
                    $captcha = $_REQUEST['g-recaptcha-response'];
                    $secretKey = can_get_option('captcha_secret_key', '6LcDhUQUAAAAAGKQ7gd1GsGAkEGooVISGEl3s7ZH');
                    $ip = $_SERVER['REMOTE_ADDR'];
                    $response = wp_remote_post("https://www.google.com/recaptcha/api/siteverify?secret=".$secretKey."&response=".$captcha."&remoteip=".$ip);
                    $responseKeys = json_decode($response['body'], true);
                    if(intval($responseKeys["success"]) !== 1) {
                        $resp = array('fail' => 'error', 'msg' => __( 'There was an error. Please try again after reloading page', 'circular-arts-network' ));
                        echo json_encode($resp); exit;
                    }
                }
            }

            // Gather the form data
            $client_name = (isset($_REQUEST['client_name'])) ? sanitize_text_field( $_REQUEST['client_name'] ) : '' ;
            $client_email = (isset($_REQUEST['client_email'])) ? sanitize_email( $_REQUEST['client_email'] ) : '' ;
            $client_phone = (isset($_REQUEST['client_phone'])) ? sanitize_text_field( $_REQUEST['client_phone'] ) : '' ;
            $client_msg = (isset($_REQUEST['client_msg'])) ? sanitize_text_field( $_REQUEST['client_msg'] ) : '' ;
            $seller_id = (isset($_REQUEST['seller_id'])) ? intval( $_REQUEST['seller_id'] ) : '' ;
            
            if($client_name && $client_email && $client_msg && $seller_id){
                
                $subject = can_get_option('email_subject', 'Listing Contact');
                $message = can_get_option('email_message', $client_msg);
                
                // if property id is available
                if (isset($_REQUEST['listing_id']) && $_REQUEST['listing_id'] != '') {
                    $listing_id = intval($_REQUEST['listing_id']);
                    $listing_title = esc_attr( get_the_title( $listing_id ) );
                    $subject = str_replace("%listing_title%", $listing_title, $subject);
                    $subject = str_replace("%listing_id%", $listing_id, $subject);

                    $message = str_replace("%listing_id%", $listing_id, $message);
                    $message = str_replace("%listing_title%", $listing_title, $message);
                    $message = str_replace("%listing_url%", get_permalink( $listing_id ), $message);
                }

                $message = str_replace("%client_message%", $client_msg, $message);
                $message = str_replace("%client_email%", $client_email, $message);
                $message = str_replace("%client_name%", $client_name, $message);
                $message = str_replace("%client_phone%", $client_phone, $message);
                
                if (can_get_option('email_br', 'enable') == 'enable') {
                   $message = nl2br(stripcslashes($message));
                }

                $message = apply_filters( 'seller_contact_email_message', $message, $_REQUEST );

                $seller_info = get_userdata($seller_id);
                $seller_email = $seller_info->user_email;

                $headers = array();
                $headers[] = "From: {$client_name} <{$client_email}>";
                $headers[] = "Content-Type: text/html";
                $headers[] = "MIME-Version: 1.0\r\n";

                $headers = apply_filters( 'can_email_headers', $headers );
                
                // Additional Emails
                $emails_meta = can_get_option('email_addresses', '');            
                if ($emails_meta != '') {
                    $emails = explode("\n", $emails_meta);
                    if (is_array($emails)) {
                        foreach ($emails as $e) {
                            $headers[] = "Cc: $e";
                        }
                    }
                }

                // Finally send the emails
                if (wp_mail( $seller_email, $subject, $message, $headers )) {
                    $resp = array('status' => 'sent', 'msg' => __( 'Email Sent Successfully', 'circular-arts-network' ) );
                } else {
                    $resp = array('status' => 'fail', 'msg' => __( 'There is some problem, please try later', 'circular-arts-network' ) );
                }

                echo json_encode($resp); die(0);

            } else {
                $resp = array('fail' => 'error', 'msg' => __( 'Please fill the required fields.', 'circular-arts-network' ));
                echo json_encode($resp); exit;
            }
        }
    }

}

new CAN_Email;
?>
