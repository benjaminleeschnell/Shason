<?php
/**
 * @package WordPress
 * @subpackage Reales
 */

/**
 * Sign Up notifications
 */
if( !function_exists('reales_signup_notifications') ): 
    function reales_signup_notifications($user, $user_pass = '') {
        $new_user = new WP_User($user);

        $user_login = stripslashes($new_user->user_login);
        $user_email = stripslashes($new_user->user_email);
        $user_first_name = stripslashes($new_user->first_name);

        $message = sprintf( __('New user Sign Up on %s:','reales'), get_option('blogname') ) . "\r\n\r\n";
        $message .= sprintf( __('Username: %s','reales'), esc_html($user_login) ) . "\r\n\r\n";
        $message .= sprintf( __('E-mail: %s','reales'), esc_html($user_email) ) . "\r\n";
        $headers = 'From: noreply  <noreply@'.$_SERVER['HTTP_HOST'].'>' . "\r\n" .
                'Reply-To: noreply@' . $_SERVER['HTTP_HOST'] . "\r\n" .
                'X-Mailer: PHP/' . phpversion();
        wp_mail(
            get_option('admin_email'),
            sprintf(__('[%s] New User Sign Up','reales'), get_option('blogname') ),
            $message,
            $headers
        );

        if(empty($user_pass)) return;

        $message  = sprintf( __('Welcome, %s!','reales'), esc_html($user_first_name) ) . "\r\n\r\n";
        $message .= __('Thank you for signing up with us. Your new account has been setup and you can now login using the details below.','reales') . "\r\n\r\n";
        $message .= sprintf( __('Username: %s','reales'), esc_html($user_login) ) . "\r\n";
        $message .= sprintf( __('Password: %s','reales'), esc_html($user_pass) ) . "\r\n\r\n";
        $message .= __('Thank you,','reales') . "\r\n";
        $message .= sprintf( __('%s Team','reales'), get_option('blogname') );
        $headers = 'From: noreply  <noreply@'.$_SERVER['HTTP_HOST'].'>' . "\r\n" .
                'Reply-To: noreply@' . $_SERVER['HTTP_HOST'] . "\r\n" .
                'X-Mailer: PHP/' . phpversion();
        wp_mail(
            esc_html($user_email),
            sprintf( __('[%s] Your username and password','reales'), get_option('blogname') ),
            $message,
            $headers
        );
    }
endif;

/**
 * User Sign Up Function
 */
if( !function_exists('reales_user_signup_form') ): 
    function reales_user_signup_form() {
        $signup_user = isset($_POST['signup_user']) ? sanitize_text_field( $_POST['signup_user'] ) : '';
        $signup_firstname = isset($_POST['signup_firstname']) ? sanitize_text_field( $_POST['signup_firstname'] ) : '';
        $signup_lastname = isset($_POST['signup_lastname']) ? sanitize_text_field( $_POST['signup_lastname'] ) : '';
        $signup_email = isset($_POST['signup_email']) ? sanitize_email( $_POST['signup_email'] ) : '';
        $signup_pass_1 = isset($_POST['signup_pass_1']) ? $_POST['signup_pass_1'] : '';
        $signup_pass_2 = isset($_POST['signup_pass_2']) ? $_POST['signup_pass_2'] : '';
        $register_as_agent = isset($_POST['register_as_agent']) ? sanitize_text_field( $_POST['register_as_agent'] ) : '';

        if(empty($signup_user) || empty($signup_firstname) || empty($signup_lastname) || empty($signup_email) || empty($signup_pass_1) || empty($signup_pass_2)) {
            echo json_encode(array('signedup'=>false, 'message'=>__('Required form fields are empty!','reales')));
            exit();
        }
        if(4 > strlen($signup_user)) {
            echo json_encode(array('signedup'=>false, 'message'=>__('Username too short. Please enter at least 4 characters!','reales')));
            exit();
        }
        if(username_exists($signup_user)) {
            echo json_encode(array('signedup'=>false, 'message'=>__('Username already exists!','reales')));
            exit();
        }
        if(!validate_username($signup_user)) {
            echo json_encode(array('signedup'=>false, 'message'=>__('Invalid Username!','reales')));
            exit();
        }
        if(!is_email($signup_email)) {
            echo json_encode(array('signedup'=>false, 'message'=>__('Invalid Email!','reales')));
            exit();
        }
        if(email_exists($signup_email)) {
            echo json_encode(array('signedup'=>false, 'message'=>__('Email already exists!','reales')));
            exit();
        }
        if(6 > strlen($signup_pass_1)) {
            echo json_encode(array('signedup'=>false, 'message'=>__('Password too short. Please enter at least 6 characters!','reales')));
            exit();
        }
        if($signup_pass_1 != $signup_pass_2) {
            echo json_encode(array('reset'=>false, 'message'=>__('The passwords do not match!','reales')));
            exit();
        }

        $user_data = array(
            'user_login' => sanitize_user($signup_user),
            'user_email' => sanitize_email($signup_email),
            'user_pass'  => esc_attr($signup_pass_1),
            'first_name' => sanitize_text_field($signup_firstname),
            'last_name'  => sanitize_text_field($signup_lastname)
        );

        $new_user = wp_insert_user($user_data);

        if(is_wp_error($new_user)) {
            echo json_encode(array('signedup'=>false, 'message'=>__('Something went wrong!','reales')));
            exit();
        } else {
            echo json_encode(array('signedup'=>true, 'message'=>__('Congratulations! You have successfully signed up.','reales')));
            reales_signup_notifications($new_user, $signup_pass_1);
            if($register_as_agent != '' && $register_as_agent == 'true') {
                reales_register_agent($new_user);
            }
        }

        die();
    }
endif;
add_action('wp_ajax_nopriv_reales_user_signup_form', 'reales_user_signup_form');
add_action('wp_ajax_reales_user_signup_form', 'reales_user_signup_form');

/**
 * Register user as agent function
 */
if( !function_exists('reales_register_agent') ): 
    function reales_register_agent($user_id) {
        $user = get_user_by('id', $user_id);
        $user_fullname = $user->first_name . ' ' . $user->last_name;
        $agent = array(
            'post_title' => $user_fullname,
            'post_type' => 'agent',
            'post_author' => $user->ID,
            'post_status' => 'publish'
        );

        $agent_id = wp_insert_post($agent);
        update_post_meta($agent_id, 'agent_email', $user->user_email);
        update_post_meta($agent_id, 'agent_user', $user->ID);
    }
endif;

/**
 * Check if user is an agent function
 */
if( !function_exists('reales_check_user_agent') ): 
    function reales_check_user_agent($user_id) {
        $args = array(
            'post_type' => 'agent',
            'post_status' => 'publish',
            'meta_query' => array(
                array(
                    'key'     => 'agent_user',
                    'value'   => $user_id,
                )
            )
        );

        $query = new WP_Query($args);

        wp_reset_postdata();
        if ($query->have_posts()) {
            wp_reset_query();
            return true;
        } else {
            return false;
        }
    }
endif;

/**
 * Get agent by user id
 */
if( !function_exists('reales_get_agent_by_userid') ): 
    function reales_get_agent_by_userid($user_id) {
        $args = array(
            'post_type' => 'agent',
            'post_status' => 'publish',
            'meta_query' => array(
                array(
                    'key'     => 'agent_user',
                    'value'   => $user_id,
                )
            )
        );

        $query = new WP_Query($args);

        if ($query->have_posts()) {
            while($query->have_posts()) {
                $query->the_post();
                $agent_id = get_the_ID();
            }
            wp_reset_postdata();
            wp_reset_query();
            return $agent_id;
        } else {
            return false;
        }
    }
endif;

/**
 * User Sign In Function
 */
if( !function_exists('reales_user_signin_form') ): 
    function reales_user_signin_form() {
        if(is_user_logged_in()) { 
            echo json_encode(array('signedin'=>true, 'message'=>__('You are already signed in, redirecting...','reales')));
            exit();
        }
        check_ajax_referer('signin_ajax_nonce', 'security');
        $signin_user = isset($_POST['signin_user']) ? sanitize_text_field($_POST['signin_user']) : '';
        $signin_pass = isset($_POST['signin_pass']) ? $_POST['signin_pass'] : '';
        $remember = isset($_POST['remember']) ? sanitize_text_field($_POST['remember']) : '';

        if ($signin_user == '' || $signin_pass == '') {
            echo json_encode(array('signedin'=>false, 'message'=>__('Invalid username or password!','reales')));
            exit();
        }

        $vsessionid = session_id();
        if (empty($vsessionid)) {
            session_name('PHPSESSID');
            session_start();
        }

        wp_clear_auth_cookie();
        $data = array();
        $data['user_login'] = $signin_user;
        $data['user_password'] = $signin_pass;
        $data['remember'] = $remember;

        $user_signon = wp_signon($data, false);

        if(is_wp_error($user_signon)) {
            echo json_encode(array('signedin'=>false, 'message'=>__('Invalid username or password!','reales')));
        } else {
            wp_set_current_user($user_signon->ID);
            do_action('set_current_user');
            global $current_user;
            $current_user = wp_get_current_user();

            echo json_encode(array('signedin'=>true,'newuser'=>$user_signon->ID, 'message'=>__('Sign in successful, redirecting...','reales')));
        }

        die();
    }
endif;
add_action('wp_ajax_nopriv_reales_user_signin_form', 'reales_user_signin_form');
add_action('wp_ajax_reales_user_signin_form', 'reales_user_signin_form');

/**
 * Forgot Password Function
 */
if( !function_exists('reales_forgot_pass_form') ): 
    function reales_forgot_pass_form() {
        global $wpdb, $wp_hasher;

        $forgot_email = isset($_POST['forgot_email']) ? sanitize_email($_POST['forgot_email']) : '';

        if($forgot_email == '') {
            echo json_encode(array('sent'=>false, 'message'=>__('Email field is empty!','reales')));
            exit();
        }

        $user_input = trim($forgot_email);

        if(strpos($user_input, '@')) {
            $user_data = get_user_by('email', $user_input);
            if(empty($user_data)) {
                echo json_encode(array('sent'=>false, 'message'=>__('Invalid email address!','reales')));
                exit();
            }
        } else {
            $user_data = get_user_by('login', $user_input);
            if(empty($user_data)) {
                echo json_encode(array('sent'=>false, 'message'=>__('Invalid username!','reales')));
                exit();
            }
        }

        $user_login = $user_data->user_login;
        $user_email = $user_data->user_email;

        $key = wp_generate_password( 20, false );
        do_action( 'retrieve_password_key', $user_login, $key );

        if ( empty( $wp_hasher ) ) {
            require_once ABSPATH . WPINC . '/class-phpass.php';
            $wp_hasher = new PasswordHash( 8, true );
        }
        $hashed = $wp_hasher->HashPassword( $key );
        $wpdb->update( $wpdb->users, array( 'user_activation_key' => $hashed ), array( 'user_login' => $user_login ) );

        $headers = 'From: No Reply <noreply@'.$_SERVER['HTTP_HOST'].'>' . "\r\n";
        $message = __('Someone has asked to reset the password for the following site and username.', 'reales') . "\r\n\r\n";
        $message .= get_option('siteurl') . "\r\n\r\n";
        $message .= sprintf(__('Username: %s', 'reales'), $user_login) . "\r\n\r\n";
        $message .= __('To reset your password visit the following address, otherwise just ignore this email and nothing will happen.', 'reales') . "\r\n\r\n";
        $message .= network_site_url("?action=rp&key=$key&login=" . rawurlencode($user_login), 'login') . "\r\n";

        if($message && !wp_mail($user_email, __('Password Reset Request','reales'), $message,  $headers)) {
            echo json_encode(array('sent'=>false, 'message'=>__('Email failed to send for some unknown reason.','reales')));
            exit();
        } else {
            echo json_encode(array('sent'=>true, 'message'=>__('An email with password reset instructions was sent to you.','reales')));
        }

        die();
    }
endif;
add_action('wp_ajax_nopriv_reales_forgot_pass_form', 'reales_forgot_pass_form');
add_action('wp_ajax_reales_forgot_pass_form', 'reales_forgot_pass_form');

/**
 * Reset Password Function
 */
if( !function_exists('reales_reset_pass_form') ): 
    function reales_reset_pass_form() {
        $allowed_html = array();
        $pass_1 = isset($_POST['pass_1']) ? wp_kses($_POST['pass_1'], $allowed_html) : '';
        $pass_2 = isset($_POST['pass_2']) ? wp_kses($_POST['pass_2'], $allowed_html) : '';
        $key = isset($_POST['key']) ? wp_kses($_POST['key'], $allowed_html) : '';
        $login = isset($_POST['login']) ? wp_kses($_POST['login'], $allowed_html) : '';

        if($pass_1 == '' || $pass_2 == '') {
            echo json_encode(array('reset'=>false, 'message'=>__('Password field empty!','reales')));
            exit();
        }

        $user = check_password_reset_key($key, $login);

        if(is_wp_error($user)) {
            if($user->get_error_code() === 'expired_key') {
                echo json_encode(array('reset'=>false, 'message'=>__('Sorry, the link does not appear to be valid or is expired!','reales')));
                exit();
            } else {
                echo json_encode(array('reset'=>false, 'message'=>__('Sorry, the link does not appear to be valid or is expired!','reales')));
                exit();
            }
        }

        if(isset($pass_1) && $pass_1 != $pass_2 ) {
            echo json_encode(array('reset'=>false, 'message'=>__('The passwords do not match!','reales')));
            exit();
        } else {
            reset_password($user, $pass_1);
            echo json_encode(array('reset'=>true, 'message'=>__('Your password has been reset.','reales')));
        }

        die();
    }
endif;
add_action('wp_ajax_nopriv_reales_reset_pass_form', 'reales_reset_pass_form');
add_action('wp_ajax_reales_reset_pass_form', 'reales_reset_pass_form');

/**
 * Facebook Login Function
 */
if( !function_exists('reales_facebook_login') ): 
    function reales_facebook_login() {
        if(is_user_logged_in()) { 
            echo json_encode(array('signedin'=>true, 'message'=>__('You are already signed in, redirecting...','reales')));
            exit();
        }
        check_ajax_referer('signin_ajax_nonce', 'security');

        $reales_auth_settings = get_option('reales_auth_settings','');
        $fb_app_id = isset($reales_auth_settings['reales_fb_id_field']) ? $reales_auth_settings['reales_fb_id_field'] : '';
        $fb_app_secret = isset($reales_auth_settings['reales_fb_secret_field']) ? $reales_auth_settings['reales_fb_secret_field'] : '';

        $user_id = isset($_POST['userid']) ? sanitize_text_field($_POST['userid']) : '';
        $signin_user = isset($_POST['signin_user']) ? sanitize_text_field($_POST['signin_user']) : '';
        $first_name = isset($_POST['first_name']) ? sanitize_text_field($_POST['first_name']) : '';
        $last_name = isset($_POST['last_name']) ? sanitize_text_field($_POST['last_name']) : '';
        $email = isset($_POST['email']) ? sanitize_text_field($_POST['email']) : '';
        $avatar = isset($_POST['avatar']) ? sanitize_text_field($_POST['avatar']) : '';
        $signin_pass = $fb_app_secret.$user_id;

        reales_social_signup($email, $signin_user, $first_name, $last_name, $signin_pass);

        $vsessionid = session_id();
        if (empty($vsessionid)) {
            session_name('PHPSESSID');
            session_start();
        }

        wp_clear_auth_cookie();
        $data = array();
        $data['user_login'] = $signin_user;
        $data['user_password'] = $signin_pass;
        $data['remember'] = true;

        $user_signon = wp_signon($data, false);
        update_user_meta($user_signon->ID, 'avatar', $avatar);

        if(is_wp_error($user_signon)) {
            echo json_encode(array('signedin'=>false, 'message'=>__('Something went wrong!','reales')));
            exit();
        } else {
            wp_set_current_user($user_signon->ID);
            do_action('set_current_user');
            global $current_user;
            $current_user = wp_get_current_user();
            echo json_encode(array('signedin'=>true, 'message'=>__('Sign in successful, redirecting...','reales')));
        }

        die();
    }
endif;
add_action( 'wp_ajax_nopriv_reales_facebook_login', 'reales_facebook_login' );
add_action( 'wp_ajax_reales_facebook_login', 'reales_facebook_login' );

/**
 * Google Signin Function
 */
if( !function_exists('reales_google_signin') ): 
    function reales_google_signin() {
        if(is_user_logged_in()) { 
            echo json_encode(array('signedin'=>true, 'message'=>__('You are already signed in, redirecting...','reales')));
            exit();
        }
        check_ajax_referer('signin_ajax_nonce', 'security');

        $reales_auth_settings = get_option('reales_auth_settings','');
        $google_client_id = isset($reales_auth_settings['reales_google_id_field']) ? $reales_auth_settings['reales_google_id_field'] : '';
        $google_client_secret = isset($reales_auth_settings['reales_google_secret_field']) ? $reales_auth_settings['reales_google_secret_field'] : '';

        $user_id = isset($_POST['userid']) ? sanitize_text_field($_POST['userid']) : '';
        $signin_user = isset($_POST['signin_user']) ? sanitize_text_field($_POST['signin_user']) : '';
        $first_name = isset($_POST['first_name']) ? sanitize_text_field($_POST['first_name']) : '';
        $last_name = isset($_POST['last_name']) ? sanitize_text_field($_POST['last_name']) : '';
        $email = isset($_POST['email']) ? sanitize_text_field($_POST['email']) : '';
        $avatar = isset($_POST['avatar']) ? sanitize_text_field($_POST['avatar']) : '';
        $signin_pass = $google_client_secret.$user_id;

        reales_social_signup($email, $signin_user, $first_name, $last_name, $signin_pass);

        $vsessionid = session_id();
        if (empty($vsessionid)) {
            session_name('PHPSESSID');
            session_start();
        }

        wp_clear_auth_cookie();
        $data = array();
        $data['user_login'] = $signin_user;
        $data['user_password'] = $signin_pass;
        $data['remember'] = true;

        $user_signon = wp_signon($data, false);
        update_user_meta($user_signon->ID, 'avatar', $avatar);

        if(is_wp_error($user_signon)) {
            echo json_encode(array('signedin'=>false, 'message'=>__('Something went wrong!','reales')));
            exit();
        } else {
            wp_set_current_user($user_signon->ID);
            do_action('set_current_user');
            global $current_user;
            $current_user = wp_get_current_user();
            echo json_encode(array('signedin'=>true, 'message'=>__('Sign in successful, redirecting...','reales')));
        }

        die();
    }
endif;
add_action( 'wp_ajax_nopriv_reales_google_signin', 'reales_google_signin' );
add_action( 'wp_ajax_reales_google_signin', 'reales_google_signin' );

/**
 * Social Sign Up Function
 */
if( !function_exists('reales_social_signup') ): 
    function reales_social_signup($email, $signin_user, $first_name, $last_name, $pass) {
        $user_data = array(
            'user_login' => $signin_user,
            'user_email' => $email,
            'user_pass'  => $pass,
            'first_name' => $first_name,
            'last_name'  => $last_name
        );

        if(email_exists($email)) {
            if(username_exists($signin_user)) {
                return;
            } else {
                $user_data['user_email'] = ' ';
                $new_user  = wp_insert_user($user_data);
                if(is_wp_error($new_user)) {
                    // social user signup failed
                }
            }
        } else {
            if(username_exists($signin_user)) {
                return;
            } else {
                $new_user = wp_insert_user($user_data);
                if(is_wp_error($new_user)) {
                    // social user signup failed
                }
            }
        }
    }
endif;

/**
 * Update user profile
 */
if( !function_exists('reales_update_user_profile') ): 
    function reales_update_user_profile() {
        check_ajax_referer('user_profile_ajax_nonce', 'security');

        $allowed_html = array();
        $user_id = isset($_POST['user_id']) ? sanitize_text_field($_POST['user_id']) : '';
        $first_name = isset($_POST['first_name']) ? sanitize_text_field($_POST['first_name']) : '';
        $last_name = isset($_POST['last_name']) ? sanitize_text_field($_POST['last_name']) : '';
        $nickname = isset($_POST['nickname']) ? sanitize_text_field($_POST['nickname']) : '';
        $email = isset($_POST['email']) ? sanitize_text_field($_POST['email']) : '';
        $password = isset($_POST['password']) ? sanitize_text_field($_POST['password']) : '';
        $re_password = isset($_POST['re_password']) ? sanitize_text_field($_POST['re_password']) : '';
        $avatar = isset($_POST['avatar']) ? sanitize_text_field($_POST['avatar']) : '';

        $agent_id = isset($_POST['agent_id']) ? sanitize_text_field($_POST['agent_id']) : '';
        $agent_about = isset($_POST['agent_about']) ? sanitize_text_field($_POST['agent_about']) : '';
        $agent_specs = isset($_POST['agent_specs']) ? sanitize_text_field($_POST['agent_specs']) : '';
        $agent_phone = isset($_POST['agent_phone']) ? sanitize_text_field($_POST['agent_phone']) : '';
        $agent_mobile = isset($_POST['agent_mobile']) ? sanitize_text_field($_POST['agent_mobile']) : '';
        $agent_skype = isset($_POST['agent_skype']) ? sanitize_text_field($_POST['agent_skype']) : '';
        $agent_facebook = isset($_POST['agent_facebook']) ? sanitize_text_field($_POST['agent_facebook']) : '';
        $agent_twitter = isset($_POST['agent_twitter']) ? sanitize_text_field($_POST['agent_twitter']) : '';
        $agent_google = isset($_POST['agent_google']) ? sanitize_text_field($_POST['agent_google']) : '';
        $agent_linkedin = isset($_POST['agent_linkedin']) ? sanitize_text_field($_POST['agent_linkedin']) : '';

        if($first_name == '') {
            echo json_encode(array('save'=>false, 'message'=>__('First Name field is mandatory.', 'reales')));
            exit();
        }
        if($last_name == '') {
            echo json_encode(array('save'=>false, 'message'=>__('Last Name field is mandatory.', 'reales')));
            exit();
        }
        if($email == '') {
            echo json_encode(array('save'=>false, 'message'=>__('E-mail field is mandatory.', 'reales')));
            exit();
        }
        if($nickname == '') {
            echo json_encode(array('save'=>false, 'message'=>__('Nickname field is mandatory.', 'reales')));
            exit();
        }
        if($password != '' && 6 > strlen($password)) {
            echo json_encode(array('save'=>false, 'message'=>__('Password too short. Please enter at least 6 characters!','reales')));
            exit();
        }
        if($password != '' && $password != $re_password) {
            echo json_encode(array('reset'=>false, 'message'=>__('The passwords do not match!','reales')));
            exit();
        }
        if ($avatar != '') {
            $images = explode("~~~", $avatar);
        }

        update_user_meta($user_id, 'first_name', $first_name);
        update_user_meta($user_id, 'last_name', $last_name);
        update_user_meta($user_id, 'nickname', $nickname);
        update_user_meta($user_id, 'avatar', $images[1]);
        if ($password != '') {
            wp_update_user(array( 'ID' => $user_id, 'user_email' => $email, 'user_pass' => $password ));
        } else {
            wp_update_user(array( 'ID' => $user_id, 'user_email' => $email ));
        }

        $agent_name = $first_name . ' ' . $last_name;
        $agent = array(
            'ID' => $agent_id,
            'post_title' => $agent_name,
            'post_type' => 'agent',
            'post_status' => 'publish'
        );
        $agent_id = wp_insert_post($agent);

        update_post_meta($agent_id, 'agent_about', $agent_about);
        update_post_meta($agent_id, 'agent_specs', $agent_specs);
        update_post_meta($agent_id, 'agent_phone', $agent_phone);
        update_post_meta($agent_id, 'agent_mobile', $agent_mobile);
        update_post_meta($agent_id, 'agent_skype', $agent_skype);
        update_post_meta($agent_id, 'agent_facebook', $agent_facebook);
        update_post_meta($agent_id, 'agent_twitter', $agent_twitter);
        update_post_meta($agent_id, 'agent_google', $agent_google);
        update_post_meta($agent_id, 'agent_linkedin', $agent_linkedin);
        update_post_meta($agent_id, 'agent_email', $email);
        update_post_meta($agent_id, 'agent_avatar', $images[1]);

        echo json_encode(array('save'=>true, 'message'=>__('Your profile was successfully updated.', 'reales')));
        exit();

        die();
    }
endif;
add_action( 'wp_ajax_nopriv_reales_update_user_profile', 'reales_update_user_profile' );
add_action( 'wp_ajax_reales_update_user_profile', 'reales_update_user_profile' );

?>