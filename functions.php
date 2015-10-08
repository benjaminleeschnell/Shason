<?php
/**
 * @package WordPress
 * @subpackage Reales
 */

require_once 'admin/settings.php';
require_once 'libs/widgets.php';
require_once 'libs/property_search.php';
require_once 'libs/search_my_properties.php';
require_once 'libs/search_fav_properties.php';
require_once 'libs/contact_agent.php';
require_once 'libs/contact_company.php';
require_once 'libs/manage_favourites.php';
require_once 'libs/post_views.php';
require_once 'libs/properties.php';
require_once 'libs/users.php';
require_once 'libs/ajax_upload.php';
require_once 'libs/plans_ajax_upload.php';
require_once 'libs/save_property.php';
require_once 'libs/class-tgm-plugin-activation.php';


/**
 * Register required plugins
 */
add_action( 'tgmpa_register', 'reales_register_required_plugins' );
if( !function_exists('reales_register_required_plugins') ): 
function reales_register_required_plugins() {
    $plugins = array(
        array(
            'name'               => 'Reales WP STPT', // The plugin name.
            'slug'               => 'short-tax-post', // The plugin slug (typically the folder name).
            'source'             => 'http://mariusn.com/plugins/reales-wp-stpt-1-0-3/short-tax-post.zip', // The plugin source.
            'required'           => true, // If false, the plugin is only 'recommended' instead of required.
            'version'            => '1.0.3',
            'external_url'       => '', // If set, overrides default API URL and points to an external URL.
        ),

    );

    $config = array(
        'default_path' => '',                      // Default absolute path to pre-packaged plugins.
        'menu'         => 'tgmpa-install-plugins', // Menu slug.
        'has_notices'  => true,                    // Show admin notices or not.
        'dismissable'  => false,                    // If false, a user cannot dismiss the nag message.
        'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
        'is_automatic' => true,                   // Automatically activate plugins after installation or not.
        'message'      => '',                      // Message to output right before the plugins table.
        'strings'      => array(
            'page_title'                      => __( 'Install Required Plugins', 'reales' ),
            'menu_title'                      => __( 'Install Plugins', 'reales' ),
            'installing'                      => __( 'Installing Plugin: %s', 'reales' ), // %s = plugin name.
            'oops'                            => __( 'Something went wrong with the plugin API.', 'reales' ),
            'notice_can_install_required'     => _n_noop( 'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.', 'reales' ), // %1$s = plugin name(s).
            'notice_can_install_recommended'  => _n_noop( 'This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.', 'reales' ), // %1$s = plugin name(s).
            'notice_cannot_install'           => _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.', 'reales' ), // %1$s = plugin name(s).
            'notice_can_activate_required'    => _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.', 'reales' ), // %1$s = plugin name(s).
            'notice_can_activate_recommended' => _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.', 'reales' ), // %1$s = plugin name(s).
            'notice_cannot_activate'          => _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.', 'reales' ), // %1$s = plugin name(s).
            'notice_ask_to_update'            => _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.', 'reales' ), // %1$s = plugin name(s).
            'notice_cannot_update'            => _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.', 'reales' ), // %1$s = plugin name(s).
            'install_link'                    => _n_noop( 'Begin installing plugin', 'Begin installing plugins', 'reales' ),
            'activate_link'                   => _n_noop( 'Begin activating plugin', 'Begin activating plugins', 'reales' ),
            'return'                          => __( 'Return to Required Plugins Installer', 'reales' ),
            'plugin_activated'                => __( 'Plugin activated successfully.', 'reales' ),
            'complete'                        => __( 'All plugins installed and activated successfully. %s', 'reales' ), // %s = dashboard link.
            'nag_type'                        => 'updated' // Determines admin notice type - can only be 'updated', 'update-nag' or 'error'.
        )
    );

    tgmpa( $plugins, $config );
}
endif;

/**
 * Reales setup
 */
if( !function_exists('reales_setup') ): 
    function reales_setup() {

        load_theme_textdomain('reales', get_template_directory() . '/languages');

        if ( function_exists( 'add_theme_support' ) ) {
            add_theme_support( 'automatic-feed-links' );
            add_theme_support( 'post-thumbnails' );
            set_post_thumbnail_size( 1920, 1080, true );
        }

        if ( ! isset( $content_width ) ) $content_width = 1140;

        if(is_admin()) {
            $page_check = get_page_by_title('Account Settings');
            if (!isset($page_check->ID)) {
                $my_post = array(
                    'post_title' => 'Account Settings',
                    'post_type' => 'page',
                    'post_status' => 'publish',
                );
                $new_id = wp_insert_post($my_post);
                update_post_meta($new_id, '_wp_page_template', 'user-account.php');
            }

            $page_check = get_page_by_title('Favourite Properties');
            if (!isset($page_check->ID)) {
                $my_post = array(
                    'post_title' => 'Favourite Properties',
                    'post_type' => 'page',
                    'post_status' => 'publish',
                );
                $new_id = wp_insert_post($my_post);
                update_post_meta($new_id, '_wp_page_template', 'favourite-properties.php');
            }

            $page_check = get_page_by_title('My Properties');
            if (!isset($page_check->ID)) {
                $my_post = array(
                    'post_title' => 'My Properties',
                    'post_type' => 'page',
                    'post_status' => 'publish',
                );
                $new_id = wp_insert_post($my_post);
                update_post_meta($new_id, '_wp_page_template', 'my-properties.php');
            }

            $page_check = get_page_by_title('Properties Search Results');
            if (!isset($page_check->ID)) {
                $my_post = array(
                    'post_title' => 'Properties Search Results',
                    'post_type' => 'page',
                    'post_status' => 'publish',
                );
                $new_id = wp_insert_post($my_post);
                update_post_meta($new_id, '_wp_page_template', 'property-search-results.php');
            }

            $page_check = get_page_by_title('Submit Property');
            if (!isset($page_check->ID)) {
                $my_post = array(
                    'post_title' => 'Submit Property',
                    'post_type' => 'page',
                    'post_status' => 'publish',
                );
                $new_id = wp_insert_post($my_post);
                update_post_meta($new_id, '_wp_page_template', 'submit-property.php');
            }
        }

        load_theme_textdomain('reales', get_template_directory() . '/languages/');

        register_nav_menus( array(
            'primary'   => __( 'Top primary menu', 'reales' ),
            'leftside'  => __( 'Left side secondary menu', 'reales')
        ) );

        // general settings default values
        $reales_general_settings = get_option('reales_general_settings');
        if (!isset($reales_general_settings['reales_currency_symbol_field']) || ( isset($reales_general_settings['reales_currency_symbol_field']) && $reales_general_settings['reales_currency_symbol_field'] == '') ) {
            $reales_general_settings['reales_currency_symbol_field'] = '$';
        }
        if (!isset($reales_general_settings['reales_currency_symbol_pos_field']) || ( isset($reales_general_settings['reales_currency_symbol_pos_field']) && $reales_general_settings['reales_currency_symbol_pos_field'] == '') ) {
            $reales_general_settings['reales_currency_symbol_pos_field'] = 'before';
        }
        if (!isset($reales_general_settings['reales_unit_field']) || ( isset($reales_general_settings['reales_unit_field']) && $reales_general_settings['reales_unit_field'] == '') ) {
            $reales_general_settings['reales_unit_field'] = 'sq ft';
        }
        if (!isset($reales_general_settings['reales_max_price_field']) || ( isset($reales_general_settings['reales_max_price_field']) && $reales_general_settings['reales_max_price_field'] == '') ) {
            $reales_general_settings['reales_max_price_field'] = '25000000';
        }
        if (!isset($reales_general_settings['reales_max_area_field']) || ( isset($reales_general_settings['reales_max_area_field']) && $reales_general_settings['reales_max_area_field'] == '') ) {
            $reales_general_settings['reales_max_area_field'] = '25000';
        }
        update_option('reales_general_settings', $reales_general_settings);

        // appearance settings default values
        $reales_appearance_settings = get_option('reales_appearance_settings');
        if (!isset($reales_appearance_settings['reales_home_header_field']) || ( isset($reales_appearance_settings['reales_home_header_field']) && $reales_appearance_settings['reales_home_header_field'] == '') ) {
            $reales_appearance_settings['reales_home_header_field'] = 'slideshow';
        }
        if (!isset($reales_appearance_settings['reales_sidebar_field']) || ( isset($reales_appearance_settings['reales_sidebar_field']) && $reales_appearance_settings['reales_sidebar_field'] == '') ) {
            $reales_appearance_settings['reales_sidebar_field'] = 'right';
        }
        if (!isset($reales_appearance_settings['reales_properties_per_page_field']) || ( isset($reales_appearance_settings['reales_properties_per_page_field']) && $reales_appearance_settings['reales_properties_per_page_field'] == '') ) {
            $reales_appearance_settings['reales_properties_per_page_field'] = '10';
        }
        update_option('reales_appearance_settings', $reales_appearance_settings);

        // colors settings default values
        $reales_colors_settings = get_option('reales_colors_settings');
        $default_colors = array(
            'reales_main_color_field' => '#0eaaa6',
            'reales_main_color_dark_field' => '#068b85',
            'reales_app_side_bg_field' => '#213837',
            'reales_app_side_item_active_bg_field' => '#067670',
            'reales_app_side_sub_bg_field' => '#132120',
            'reales_app_side_sub_item_active_bg_field' => '#05635e',
            'reales_app_side_text_color_field' => '#adc8c7',
            'reales_app_side_sub_text_color_field' => '#96adac',
            'reales_app_top_item_active_color_field' => '#c6e4e3',
            'reales_footer_bg_field' => '#333333',
            'reales_footer_header_color_field' => '#c6e4e3',
            'reales_prop_type_badge_bg_field' => '#eab134',
            'reales_fav_icon_color_field' => '#ea3d36',
            'reales_marker_color_field' => '#0eaaa6',
            'reales_prop_pending_label_bg_field' => '#ea3d36'
        );
        if ($reales_colors_settings['reales_main_color_field'] == '' && 
            $reales_colors_settings['reales_main_color_dark_field'] == '' && 
            $reales_colors_settings['reales_app_side_bg_field'] == '' && 
            $reales_colors_settings['reales_app_side_item_active_bg_field'] == '' && 
            $reales_colors_settings['reales_app_side_sub_bg_field'] == '' && 
            $reales_colors_settings['reales_app_side_sub_item_active_bg_field'] == '' && 
            $reales_colors_settings['reales_app_side_text_color_field'] == '' && 
            $reales_colors_settings['reales_app_side_sub_text_color_field'] == '' && 
            $reales_colors_settings['reales_app_top_item_active_color_field'] == '' && 
            $reales_colors_settings['reales_footer_bg_field'] == '' && 
            $reales_colors_settings['reales_footer_header_color_field'] == '' && 
            $reales_colors_settings['reales_prop_type_badge_bg_field'] == '' && 
            $reales_colors_settings['reales_fav_icon_color_field'] == '' && 
            $reales_colors_settings['reales_prop_pending_label_bg_field'] == '' && 
            $reales_colors_settings['reales_marker_color_field'] == '') {
                update_option('reales_colors_settings', $default_colors);
        }

    }
endif;
add_action( 'after_setup_theme', 'reales_setup' );

/**
 * Enqueue scripts and styles for the front end
 */
if( !function_exists('reales_scripts') ): 
    function reales_scripts() {
        global $paged;
        global $post;
        // Load stylesheets
        wp_enqueue_style('font_awesome',get_template_directory_uri().'/css/font-awesome.css', array(), '1.0', 'all');
        wp_enqueue_style('simple_line_icons',get_template_directory_uri().'/css/simple-line-icons.css', array(), '1.0', 'all');
        wp_enqueue_style('jquery_ui',get_template_directory_uri().'/css/jquery-ui.css', array(), '1.0', 'all');
        wp_enqueue_style('file_input',get_template_directory_uri().'/css/fileinput.min.css', array(), '1.0', 'all');
        wp_enqueue_style('bootstrap_style',get_template_directory_uri().'/css/bootstrap.css', array(), '1.0', 'all');
        wp_enqueue_style('datepicker',get_template_directory_uri().'/css/datepicker.css', array(), '1.0', 'all');
        wp_enqueue_style('fancybox',get_template_directory_uri().'/css/jquery.fancybox.css', array(), '1.0', 'all');
        wp_enqueue_style('fancybox_buttons',get_template_directory_uri().'/css/jquery.fancybox-buttons.css', array(), '1.0', 'all');
        wp_enqueue_style('reales_style',get_stylesheet_uri(), array(), '1.0', 'all');
        wp_enqueue_style('idx_style',get_template_directory_uri().'/css/idx.css', array(), '1.0', 'all');

        // Load scripts
        wp_enqueue_script('jquery-ui', get_template_directory_uri().'/js/jquery-ui.min.js',array('jquery'), '1.0', true);
        wp_enqueue_script('jquery.placeholder', get_template_directory_uri().'/js/jquery.placeholder.js',array('jquery'), '1.0', true);
        wp_enqueue_script('bootstrap', get_template_directory_uri().'/js/bootstrap.js',array(), '1.0', true);
        wp_enqueue_script('jquery.touchSwipe', get_template_directory_uri().'/js/jquery.touchSwipe.min.js',array('jquery'), '1.0', true);
        wp_enqueue_script('jquery-ui-touch', get_template_directory_uri().'/js/jquery-ui-touch-punch.js',array('jquery'), '1.0', true);
        wp_enqueue_script('jquery.slimscroll', get_template_directory_uri().'/js/jquery.slimscroll.min.js',array('jquery'), '1.0', true);
        wp_enqueue_script('markerclusterer', get_template_directory_uri().'/js/markerclusterer.js',array(), '1.0', true);
        wp_enqueue_script('bootstrap-datepicker', get_template_directory_uri().'/js/bootstrap-datepicker.js', array(), '1.0', true);

        $reales_gmaps_settings = get_option('reales_gmaps_settings','');
        $gmaps_key = isset($reales_gmaps_settings['reales_gmaps_key_field']) ? $reales_gmaps_settings['reales_gmaps_key_field'] : '';
        $gmaps_zoom = isset($reales_gmaps_settings['reales_gmaps_zoom_field']) ? $reales_gmaps_settings['reales_gmaps_zoom_field'] : '14';
        wp_enqueue_script('gmaps', 'https://maps.googleapis.com/maps/api/js?key='.$gmaps_key.'&amp;sensor=true&amp;libraries=geometry&amp;libraries=places',array('jquery'), '1.0', true);

        wp_enqueue_script('google', 'https://plus.google.com/js/client:platform.js',array(), '1.0', true);
        wp_enqueue_script('infobox', get_template_directory_uri().'/js/infobox.js',array(), '1.0', true);
        wp_enqueue_script('jquery.fileinput', get_template_directory_uri().'/js/fileinput.min.js',array(), '1.0', true);
        wp_enqueue_script('imagescale', get_template_directory_uri().'/js/image-scale.min.js',array(), '1.0', true);
        wp_enqueue_script('fancybox', get_template_directory_uri().'/js/jquery.fancybox.js',array('jquery'), '2.1.5', true);
        wp_enqueue_script('fancybox', get_template_directory_uri().'/js/jquery.fancybox-buttons.js',array('jquery'), '1.0', true);
        wp_enqueue_script('services', get_template_directory_uri().'/js/services.js',array(), '1.0', true);
        wp_enqueue_script('main', get_template_directory_uri().'/js/main.js',array(), '1.0', true);

        $reales_general_settings = get_option('reales_general_settings');
        $search_country = isset($_GET['search_country']) ? sanitize_text_field($_GET['search_country']) : '';
        $search_state = isset($_GET['search_state']) ? sanitize_text_field($_GET['search_state']) : '';
        $search_city = isset($_GET['search_city']) ? sanitize_text_field($_GET['search_city']) : '';
        $search_category = isset($_GET['search_category']) ? sanitize_text_field($_GET['search_category']) : '0';
        $search_type = isset($_GET['search_type']) ? sanitize_text_field($_GET['search_type']) : '0';
        $search_min_price = isset($_GET['search_min_price']) ? sanitize_text_field($_GET['search_min_price']) : '';
        $search_max_price = isset($_GET['search_max_price']) ? sanitize_text_field($_GET['search_max_price']) : '';
        $search_lat = isset($_GET['search_lat']) ? sanitize_text_field($_GET['search_lat']) : '';
        $search_lng = isset($_GET['search_lng']) ? sanitize_text_field($_GET['search_lng']) : '';
        $search_bedrooms = isset($_GET['search_bedrooms']) ? sanitize_text_field($_GET['search_bedrooms']) : '';
        $search_bathrooms = isset($_GET['search_bathrooms']) ? sanitize_text_field($_GET['search_bathrooms']) : '';
        $search_neighborhood = isset($_GET['search_neighborhood']) ? sanitize_text_field($_GET['search_neighborhood']) : '';
        $search_min_area = isset($_GET['search_min_area']) ? sanitize_text_field($_GET['search_min_area']) : '';
        $search_max_area = isset($_GET['search_max_area']) ? sanitize_text_field($_GET['search_max_area']) : '';
        $search_unit = isset($reales_general_settings['reales_unit_field']) ? $reales_general_settings['reales_unit_field'] : '';
        $sort = isset($_GET['sort']) ? sanitize_text_field($_GET['sort']) : 'newest';

        $reales_amenities_settings = get_option('reales_amenities_settings');
        $amenities_list = array();
        $amenities = isset($reales_amenities_settings['reales_amenities_field']) ? $reales_amenities_settings['reales_amenities_field'] : '';
        $amenities_list = explode(',', $amenities);
        $search_amenities = array();

        if($amenities != '') {
            foreach($amenities_list as $key => $value) {
                $post_var_name = str_replace(' ', '_', trim($value));

                $input_name = reales_substr45(sanitize_title($post_var_name));
                $input_name = sanitize_key($input_name);
                if (isset($_GET[$input_name]) && $_GET[$input_name] == 1) {
                    array_push($search_amenities, $input_name);
                }
            }
        }

        $user = wp_get_current_user();

        $reales_colors_settings = get_option('reales_colors_settings');
        if(isset($reales_colors_settings['reales_marker_color_field']) && $reales_colors_settings['reales_marker_color_field'] != '') {
            $marker_color = $reales_colors_settings['reales_marker_color_field'];
        } else {
            $marker_color = '#0eaaa6';
        }

        if($search_lat == '' && $search_lng == '') {
            $search_lat = isset($reales_gmaps_settings['reales_gmaps_lat_field']) ? $reales_gmaps_settings['reales_gmaps_lat_field'] : '';
            $search_lng = isset($reales_gmaps_settings['reales_gmaps_lng_field']) ? $reales_gmaps_settings['reales_gmaps_lng_field'] : '';
        }



        wp_localize_script('services', 'services_vars', 
            array(  'admin_url' => get_admin_url(),
                    'signin_redirect' => home_url(),
                    'theme_url' => get_template_directory_uri(),
                    'signup_loading' => __('Sending...','reales'),
                    'signup_text' => __('Sign Up','reales'),
                    'signin_loading' => __('Sending...','reales'),
                    'signin_text' => __('Sign In','reales'),
                    'forgot_loading' => __('Sending...','reales'),
                    'forgot_text' => __('Get New Password','reales'),
                    'reset_pass_loading' => __('Sending...','reales'),
                    'reset_pass_text' => __('Reset Password','reales'),
                    'fb_login_loading' => __('Sending...', 'reales'),
                    'fb_login_text' => __('Sign In with Facebook', 'reales'),
                    'fb_login_error' => __('Login cancelled or not fully authorized!', 'reales'),
                    'google_signin_loading' => __('Sending...', 'reales'),
                    'google_signin_text' => __('Sign In with Google', 'reales'),
                    'google_signin_error' => __('Signin cancelled or not fully authorized!', 'reales'),
                    'search_country' => $search_country,
                    'search_state' => $search_state,
                    'search_city' => $search_city,
                    'search_category' => $search_category,
                    'search_type' => $search_type,
                    'search_min_price' => $search_min_price,
                    'search_max_price' => $search_max_price,
                    'search_lat' => $search_lat,
                    'search_lng' => $search_lng,
                    'search_bedrooms' => $search_bedrooms,
                    'search_bathrooms' => $search_bathrooms,
                    'search_neighborhood' => $search_neighborhood,
                    'search_min_area' => $search_min_area,
                    'search_max_area' => $search_max_area,
                    'search_unit' => $search_unit,
                    'search_amenities' => $search_amenities,
                    'sort' => $sort,
                    'zoom' => $gmaps_zoom,
                    'infobox_close_btn' => __('Close', 'reales'),
                    'infobox_view_btn' => __('View', 'reales'),
                    'page' => $paged,
                    'post_id' => $post ? $post->ID : NULL,
                    'user_id' => $user->ID,
                    'update_property' => __('Update Property', 'reales'),
                    'marker_color' => $marker_color,
                    'saving_property' => __('Saving Property...', 'reales'),
                    'deleting_property' => __('Deleting Property...', 'reales'),
                    'home_redirect' => home_url(),
                    'send_message' => __('Send Message', 'reales'),
                    'sending_message' => __('Sending Message...', 'reales'),
                    'updating_profile' => __('Updating Profile...', 'reales')
            )
        );

        $mv_max_price = isset($reales_general_settings['reales_max_price_field']) ? $reales_general_settings['reales_max_price_field'] : '';
        $mv_max_area = isset($reales_general_settings['reales_max_area_field']) ? $reales_general_settings['reales_max_area_field'] : '';
        $mv_currency = isset($reales_general_settings['reales_currency_symbol_field']) ? $reales_general_settings['reales_currency_symbol_field'] : '';
        $mv_currency_pos = isset($reales_general_settings['reales_currency_symbol_pos_field']) ? $reales_general_settings['reales_currency_symbol_pos_field'] : '';
        $mv_unit = isset($reales_general_settings['reales_unit_field']) ? $reales_general_settings['reales_unit_field'] : '';

        if(current_user_can('manage_options')) {
            $top_admin_menu = true;
        } else {
            $top_admin_menu = false;
        }

        wp_localize_script('main', 'main_vars', 
            array(
                'no_city' => __('Please set location', 'reales'),
                'max_price' => $mv_max_price,
                'max_area' => $mv_max_area,
                'currency' => $mv_currency,
                'currency_pos' => $mv_currency_pos,
                'unit' => $mv_unit,
                'search_placeholder' => __('Search for...', 'reales'),
                'top_admin_menu' => $top_admin_menu,
                'idx_search_location' => __('Location', 'reales'),
                'idx_search_category' => __('Category', 'reales'),
                'idx_search_price_min' => __('Min price', 'reales'),
                'idx_search_price_max' => __('Max price', 'reales'),
                'idx_search_beds' => __('Bedrooms', 'reales'),
                'idx_search_baths' => __('Bathrooms', 'reales'),
                'idx_advanced_search' => __('Advanced Search', 'reales'),
                'idx_advanced_filter' => __('Show advanced search options', 'reales'),
                'idx_advanced_filter_hide' => __('Hide advanced search options', 'reales'),
            )
        );

        $max_file_size  = 100 * 1000 * 1000;
        wp_enqueue_script('ajax-upload', get_template_directory_uri().'/js/ajax-upload.js',array('jquery','plupload-handlers'), '1.0', true);
        wp_localize_script('ajax-upload', 'ajax_vars', 
            array(  'ajaxurl'           => admin_url('admin-ajax.php'),
                    'nonce'             => wp_create_nonce('reales_upload'),
                    'remove'            => wp_create_nonce('reales_remove'),
                    'number'            => 1,
                    'upload_enabled'    => true,
                    'confirmMsg'        => __('Are you sure you want to delete this?', 'reales'),
                    'plupload'          => array(
                                            'runtimes'          => 'html5,flash,html4',
                                            'browse_button'     => 'aaiu-uploader',
                                            'container'         => 'aaiu-upload-container',
                                            'file_data_name'    => 'aaiu_upload_file',
                                            'max_file_size'     => $max_file_size . 'b',
                                            'url'               => admin_url('admin-ajax.php') . '?action=reales_upload&nonce=' . wp_create_nonce('reales_allow'),
                                            'flash_swf_url'     => includes_url('js/plupload/plupload.flash.swf'),
                                            'filters'           => array(array('title' => __('Allowed Files', 'reales'), 'extensions' => "jpg,gif,png")),
                                            'multipart'         => true,
                                            'urlstream_upload'  => true
                                        )
                )
        );

        wp_enqueue_script('plans-ajax-upload', get_template_directory_uri().'/js/plans-ajax-upload.js',array('jquery','plupload-handlers'), '1.0', true);
        wp_localize_script('plans-ajax-upload', 'ajax_vars', 
            array(  'ajaxurl'           => admin_url('admin-ajax.php'),
                    'nonce'             => wp_create_nonce('reales_upload_plans'),
                    'remove'            => wp_create_nonce('reales_remove_plans'),
                    'number'            => 1,
                    'upload_enabled'    => true,
                    'confirmMsg'        => __('Are you sure you want to delete this?', 'reales'),
                    'plupload'          => array(
                                            'runtimes'          => 'html5,flash,html4',
                                            'browse_button'     => 'aaiu-uploader-plans',
                                            'container'         => 'aaiu-upload-container-plans',
                                            'file_data_name'    => 'aaiu_upload_file_plans',
                                            'max_file_size'     => $max_file_size . 'b',
                                            'url'               => admin_url('admin-ajax.php') . '?action=reales_upload_plans&nonce=' . wp_create_nonce('reales_allow'),
                                            'flash_swf_url'     => includes_url('js/plupload/plupload.flash.swf'),
                                            'filters'           => array(array('title' => __('Allowed Files', 'reales'), 'extensions' => "jpg,gif,png")),
                                            'multipart'         => true,
                                            'urlstream_upload'  => true
                                        )
                )
        );

        if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
            wp_enqueue_script( 'comment-reply' );
        }
    }
endif;
add_action( 'wp_enqueue_scripts', 'reales_scripts' );

/**
 * Disable Admin Bar for everyone but administrators
 */
if (!function_exists('reales_disable_admin_bar')):

    function reales_disable_admin_bar() {
        
        if (!current_user_can('manage_options')) {
        
            // for the admin page
            remove_action('admin_footer', 'wp_admin_bar_render', 1000);
            // for the front-end
            remove_action('wp_footer', 'wp_admin_bar_render', 1000);
            
            // css override for the admin page
            function reales_remove_admin_bar_style_backend() { 
                echo '<style>body.admin-bar #wpcontent, body.admin-bar #adminmenu { padding-top: 0px !important; }</style>';
            }     
            add_filter('admin_head', 'reales_remove_admin_bar_style_backend');
            
            // css override for the frontend
            function reales_remove_admin_bar_style_frontend() {
                echo '<style type="text/css" media="screen">
                html { margin-top: 0px !important; }
                * html body { margin-top: 0px !important; }
                </style>';
            }
            add_filter('wp_head', 'reales_remove_admin_bar_style_frontend', 99);
        } else {
            function reales_add_admin_bar_style_frontend() {
                echo '<style type="text/css" media="screen">
                #header { top: 32px; }
                #leftSide { top: 92px; }
                #carouselBlog + .home-header { top: 32px; }
                @media screen and (max-width: 782px) {
                    #header { top: 46px; }
                    #leftSide { top: 96px; }
                    #carouselBlog + .home-header { top: 46px; }
                }
                @media screen and (max-width: 767px) {
                    #leftSide { top: 96px; }
                    .modal-dialog { margin: 120px 20px 20px 20px; }
                }
                </style>';
            }
            add_filter('wp_head', 'reales_add_admin_bar_style_frontend', 99);
        }
    }
endif;
add_action('init', 'reales_disable_admin_bar');

/**
 * Custom colors
 */
if( !function_exists('reales_add_custom_colors') ): 
    function reales_add_custom_colors() {
        echo "<style type='text/css'>" ;
        require_once ('libs/colors.php');
        echo "</style>";
    }
endif;
add_action('wp_head', 'reales_add_custom_colors');

/**
 * Add custom field to media library items
 */
if( !function_exists('reales_image_add_custom_fields') ): 
    function reales_image_add_custom_fields($form_fields, $post) {
        $value = get_post_meta($post->ID, "show-in-slideshow", true);
        if($value) {
            $checked = "checked";
        } else {
            $checked = "";
        }


        $form_fields["show-in-slideshow"] = array(
            "label" => __("Show in Slideshow", "reales"),
            "input" => "html",
            "html" => "<input type='checkbox' name='attachments[{$post->ID}][show-in-slideshow]' id='attachments[{$post->ID}][show-in-slideshow]' $checked />"
        );
        return $form_fields;
    }
endif;
add_filter("attachment_fields_to_edit", "reales_image_add_custom_fields", null, 2);

/**
 * Save custom field value
 */
if( !function_exists('reales_image_save_custom_fields') ): 
    function reales_image_save_custom_fields($post, $attachment) {
        if(isset($attachment['show-in-slideshow'])) {
            update_post_meta($post['ID'], 'show-in-slideshow', $attachment['show-in-slideshow']);
        } else {
            delete_post_meta($post['ID'], 'show-in-slideshow');
        }
        return $post;
    }
endif;
add_filter("attachment_fields_to_save", "reales_image_save_custom_fields", null , 2);

/**
 * Add Show in Slideshow column in media library
 */
if( !function_exists('reales_image_attachment_columns') ): 
    function reales_image_attachment_columns($columns) {
        $columns['show-in-slideshow'] = __("Show in Slideshow", "reales");
        return $columns;
    }
endif;
add_filter("manage_media_columns", "reales_image_attachment_columns", null, 2);

/**
 * Add Show in Slideshow column data in media library
 */
if( !function_exists('reales_image_attachment_show_column') ): 
    function reales_image_attachment_show_column($name) {
        global $post;
        switch ($name) {
            case 'show-in-slideshow':
                $value = get_post_meta($post->ID, "show-in-slideshow", true);
                if ($value) {
                    esc_html_e("yes", "reales");
                } else {
                    esc_html_e("no", "reales");
                }
                break;
        }
    }
endif;
add_action('manage_media_custom_column', 'reales_image_attachment_show_column', null, 2);

/**
 * Get slideshow images
 */
if( !function_exists('reales_get_slideshow_images') ): 
    function reales_get_slideshow_images() {
        $media_query = new WP_Query(
            array(
                'post_type' => 'attachment',
                'post_status' => 'inherit',
                'posts_per_page' => -1,
            )
        );
        $list = array();
        foreach ($media_query->posts as $post) {
            if (get_post_meta($post->ID, "show-in-slideshow", true)) {
                $list[] = wp_get_attachment_url($post->ID);
            }
        }
        return $list;
    }
endif;
add_action( 'wp_loaded', 'reales_get_slideshow_images' );

/**
 * Add custom profile fields
 */
if( !function_exists('reales_add_custom_profile_fields') ): 
    function reales_add_custom_profile_fields($profile_fields) {
        $profile_fields['avatar'] = 'Avatar URL';

        return $profile_fields;
    }
endif;
add_filter('user_contactmethods', 'reales_add_custom_profile_fields');

/**
 * Register sidebars
 */
if( !function_exists('reales_widgets_init') ): 
    function reales_widgets_init() {
        register_sidebar(array(
            'name' => __('Main Widget Area', 'reales'),
            'id' => 'main-widget-area',
            'description' => __('The main widget area', 'reales'),
            'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
            'after_widget' => '</li>',
            'before_title' => '<h3 class="osLight sidebar-header">',
            'after_title' => '</h3>'
        ));

        register_sidebar(array(
            'name' => __('IDX Homepage Search Widget Area', 'reales'),
            'id' => 'idx-homepage-search-widget-area',
            'description' => __('IDX homepage search form widget area', 'reales'),
            'before_widget' => '',
            'after_widget' => '',
            'before_title' => '<h3>',
            'after_title' => '</h3>'
        ));

        register_sidebar(array(
            'name' => __('IDX Properties Page Search Widget Area', 'reales'),
            'id' => 'idx-properties-search-widget-area',
            'description' => __('IDX properties page search form widget area', 'reales'),
            'before_widget' => '<div class="idx-filter">',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="osLight sidebar-header">',
            'after_title' => '</h3>'
        ));

        register_sidebar(array(
            'name' => __('1st Footer Widget Area', 'reales'),
            'id' => 'first-footer-widget-area',
            'description' => __('The first footer widget area', 'reales'),
            'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
            'after_widget' => '</li>',
            'before_title' => '<h3 class="osLight footer-header">',
            'after_title' => '</h3>'
        ));

        register_sidebar(array(
            'name' => __('2nd Footer Widget Area', 'reales'),
            'id' => 'second-footer-widget-area',
            'description' => __('The second footer widget area', 'reales'),
            'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
            'after_widget' => '</li>',
            'before_title' => '<h3 class="osLight footer-header">',
            'after_title' => '</h3>'
        ));

        register_sidebar(array(
            'name' => __('3rd Footer Widget Area', 'reales'),
            'id' => 'third-footer-widget-area',
            'description' => __('The third footer widget area', 'reales'),
            'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
            'after_widget' => '</li>',
            'before_title' => '<h3 class="osLight footer-header">',
            'after_title' => '</h3>'
        ));

        register_sidebar(array(
            'name' => __('4th Footer Widget Area', 'reales'),
            'id' => 'fourth-footer-widget-area',
            'description' => __('The fourth footer widget area', 'reales'),
            'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
            'after_widget' => '</li>',
            'before_title' => '<h3 class="osLight footer-header">',
            'after_title' => '</h3>',
        ));
    }
endif;
add_action( 'widgets_init', 'reales_widgets_init' );

/**
 * Custom metaboxes in posts
 */
if( !function_exists('reales_add_post_metaboxes') ): 
    function reales_add_post_metaboxes() {
        add_meta_box('post-featured-section', __('Featured', 'reales'), 'post_featured_render', 'post', 'side', 'default');
    }
endif;
add_action('add_meta_boxes', 'reales_add_post_metaboxes');

if( !function_exists('post_featured_render') ): 
    function post_featured_render($post) {
        wp_nonce_field(plugin_basename(__FILE__), 'post_noncename');

        if(isset($_GET['post'])) {
            $post_id = sanitize_text_field($_GET['post']);
        } else if(isset($_POST['post_ID'])) {
            $post_id = sanitize_text_field($_POST['post_ID']);
        }

        print '
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="100%" valign="top" align="left">
                        <p class="meta-options">
                            <input type="hidden" name="post_featured" value="">
                            <input type="checkbox" name="post_featured" value="1" ';
                            if (esc_html(get_post_meta($post_id, 'post_featured', true)) == 1) {
                                print ' checked ';
                            }
                            print ' />
                            <label for="post_featured">' . __('Set as Featured', 'reales') . '</label>
                        </p>
                    </td>
                </tr>
            </table>';
    }
endif;

if( !function_exists('reales_post_meta_save') ): 
    function reales_post_meta_save($post_id) {
        $is_autosave = wp_is_post_autosave($post_id);
        $is_revision = wp_is_post_revision($post_id);
        $is_valid_nonce = (isset($_POST['post_noncename']) && wp_verify_nonce($_POST['post_noncename'], basename(__FILE__))) ? 'true' : 'false';

        if ($is_autosave || $is_revision || !$is_valid_nonce) {
            return;
        }

        if(isset($_POST['post_featured'])) {
            update_post_meta($post_id, 'post_featured', sanitize_text_field($_POST['post_featured']));
        }
    }
endif;
add_action('save_post', 'reales_post_meta_save');

/**
 * Custom comments
 */
if( !function_exists('reales_comment') ): 
    function reales_comment($comment, $args, $depth) {
        $GLOBALS['comment'] = $comment;
        extract($args, EXTR_SKIP);

        if ( 'div' == $args['style'] ) {
            $tag = 'div';
            $add_below = 'comment';
        } else {
            $tag = 'li';
            $add_below = 'div-comment';
        } ?>

        <<?php echo esc_html($tag); ?> <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ) ?> id="comment-<?php comment_ID() ?>">


        <div class="comment-author vcard commentAvatar">
            <?php if ( $args['avatar_size'] != 0 ) echo get_avatar( $comment, $args['avatar_size'] ); ?>
            <div class="commentArrow bg-w"><span class="fa fa-caret-left"></span></div>
        </div>

        <?php if ( 'div' != $args['style'] ) : ?>
        <div id="div-comment-<?php comment_ID() ?>" class="comment-body commentContent bg-w">
        <?php endif; ?>

            <div class="commentName"><?php printf( __( '<cite class="fn">%s</cite>' ), get_comment_author_link() ); ?></div>

            <?php if ( $comment->comment_approved == '0' ) : ?>
                <em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'reales' ); ?></em>
                <br />
            <?php endif; ?>

            <div class="commentBody">
                <?php comment_text(); ?>
            </div>

            <div class="commentActions">
                <div class="commentTime">
                    <div class="comment-meta commentmetadata">
                        <a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>">
                        <span class="icon-clock"></span> <?php printf( __('%1$s at %2$s', 'reales'), get_comment_date(),  get_comment_time() ); ?></a>
                    </div>
                </div>
                <ul>
                    <li>
                        <div class="reply">
                            <?php comment_reply_link( array_merge( $args, array( 'add_below' => $add_below, 'before' => '<span class="icon-action-undo"></span> ', 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
                        </div>
                    </li>
                    <li>
                        <?php edit_comment_link( __( 'Edit', 'reales' ), '<span class="icon-pencil"></span> ', '' ); ?>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>

        <?php if ( 'div' != $args['style'] ) : ?>
        </div>
        <?php endif; ?>

        <div class="clearfix"></div>
    <?php
    }
endif;

/**
 * Custom excerpt lenght
 */
if( !function_exists('reales_custom_excerpt_length') ): 
    function reales_custom_excerpt_length( $length ) {
        return 30;
    }
endif;
add_filter( 'excerpt_length', 'reales_custom_excerpt_length', 999 );

if( !function_exists('reales_get_excerpt_by_id') ): 
    function reales_get_excerpt_by_id($post_id) {
        $the_post = get_post($post_id);
        $the_excerpt = $the_post->post_content;
        $excerpt_length = 30;
        $the_excerpt = strip_tags(strip_shortcodes($the_excerpt));
        $words = explode(' ', $the_excerpt, $excerpt_length + 1);

        if(count($words) > $excerpt_length) :
            array_pop($words);
            array_push($words, '...');
            $the_excerpt = implode(' ', $words);
        endif;

        wp_reset_postdata();
        wp_reset_query();

        return $the_excerpt;
    }
endif;

/**
 * Add post views column in WP-Admin
 */
if( !function_exists('reales_posts_column_views') ): 
    function reales_posts_column_views($defaults) {
        $defaults['post_views'] = __('Views', 'reales');
        return $defaults;
    }
endif;
add_filter('manage_posts_columns', 'reales_posts_column_views');

if( !function_exists('reales_posts_custom_column_views') ): 
    function reales_posts_custom_column_views($column_name, $id) {
        if($column_name === 'post_views'){
            echo reales_get_post_views(get_the_ID(), '');
        }
    }
endif;
add_action('manage_posts_custom_column', 'reales_posts_custom_column_views', 5, 2);

/**
 * Add pagination
 */
if( !function_exists('reales_pagination') ): 
    function reales_pagination($pages = '', $range = 2) {
        $showitems = ($range * 2)+1;

        global $paged;
        if(empty($paged)) $paged = 1;

        if($pages == '') {
            global $wp_query;
            $pages = $wp_query->max_num_pages;
            if(!$pages)
            {
                $pages = 1;
            }
        }

        if(1 != $pages) {
            echo '<ul class="pagination">';
            if($paged > 2 && $paged > $range+1 && $showitems < $pages) echo '<li><a href="' . esc_url(get_pagenum_link(1)) . '"><span class="fa fa-angle-double-left"></span></a></li>';
            if($paged > 1 && $showitems < $pages) echo '<li><a href="' . esc_url(get_pagenum_link($paged - 1)) . '"><span class="fa fa-angle-left"></span></a></li>';

            for ($i = 1; $i <= $pages; $i++) {
                if (1 != $pages &&( !($i >= $paged + $range + 1 || $i <= $paged - $range - 1) || $pages <= $showitems )) {
                    echo ($paged == $i)? '<li class="active"><a href="#">' . esc_html($i) . '</a></li>' : '<li><a href="' . esc_url(get_pagenum_link($i)) . '">' . esc_html($i) . '</a></li>';
                }
            }

            if ($paged < $pages && $showitems < $pages) echo '<li><a href="' . esc_url(get_pagenum_link($paged + 1)) . '"><span class="fa fa-angle-right"></span></a></li>';
            if ($paged < $pages - 1 &&  $paged + $range - 1 < $pages && $showitems < $pages) echo '<li><a href="' . esc_url(get_pagenum_link($pages)) . '"><span class="fa fa-angle-double-right"></span></a></li>';
            echo '</ul>';
        }
    }
endif;

if( !function_exists('reales_new_country_list') ): 
    function reales_new_country_list($selected) {
        $countries = array("Afghanistan", "Albania", "Algeria", "American Samoa", "Andorra", "Angola", "Anguilla", "Antarctica", "Antigua and Barbuda", "Argentina", "Armenia", "Aruba", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bermuda", "Bhutan", "Bolivia", "Bosnia and Herzegowina", "Botswana", "Bouvet Island", "Brazil", "British Indian Ocean Territory", "Brunei Darussalam", "Bulgaria", "Burkina Faso", "Burundi", "Cambodia", "Cameroon", "Canada", "Cape Verde", "Cayman Islands", "Central African Republic", "Chad", "Chile", "China", "Christmas Island", "Cocos (Keeling) Islands", "Colombia", "Comoros", "Congo", "Congo, the Democratic Republic of the", "Cook Islands", "Costa Rica", "Cote d'Ivoire", "Croatia (Hrvatska)", "Cuba", "Cyprus", "Czech Republic", "Denmark", "Djibouti", "Dominica", "Dominican Republic", "East Timor", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Ethiopia", "Falkland Islands (Malvinas)", "Faroe Islands", "Fiji", "Finland", "France", "France Metropolitan", "French Guiana", "French Polynesia", "French Southern Territories", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Gibraltar", "Greece", "Greenland", "Grenada", "Guadeloupe", "Guam", "Guatemala", "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Heard and Mc Donald Islands", "Holy See (Vatican City State)", "Honduras", "Hong Kong", "Hungary", "Iceland", "India", "Indonesia", "Iran (Islamic Republic of)", "Iraq", "Ireland", "Israel", "Italy", "Jamaica", "Japan", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Korea, Democratic People's Republic of", "Korea, Republic of", "Kuwait", "Kyrgyzstan", "Lao, People's Democratic Republic", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libyan Arab Jamahiriya", "Liechtenstein", "Lithuania", "Luxembourg", "Macau", "Macedonia, The Former Yugoslav Republic of", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Martinique", "Mauritania", "Mauritius", "Mayotte", "Mexico", "Micronesia, Federated States of", "Moldova, Republic of", "Monaco", "Mongolia", "Montserrat", "Morocco", "Mozambique", "Myanmar", "Namibia", "Nauru", "Nepal", "Netherlands", "Netherlands Antilles", "New Caledonia", "New Zealand", "Nicaragua", "Niger", "Nigeria", "Niue", "Norfolk Island", "Northern Mariana Islands", "Norway", "Oman", "Pakistan", "Palau", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Pitcairn", "Poland", "Portugal", "Puerto Rico", "Qatar", "Reunion", "Romania", "Russian Federation", "Rwanda", "Saint Kitts and Nevis", "Saint Lucia", "Saint Vincent and the Grenadines", "Samoa", "San Marino", "Sao Tome and Principe", "Saudi Arabia", "Senegal", "Seychelles", "Sierra Leone", "Singapore", "Slovakia (Slovak Republic)", "Slovenia", "Solomon Islands", "Somalia", "South Africa", "South Georgia and the South Sandwich Islands", "Spain", "Sri Lanka", "St. Helena", "St. Pierre and Miquelon", "Sudan", "Suriname", "Svalbard and Jan Mayen Islands", "Swaziland", "Sweden", "Switzerland", "Syrian Arab Republic", "Taiwan, Province of China", "Tajikistan", "Tanzania, United Republic of", "Thailand", "Togo", "Tokelau", "Tonga", "Trinidad and Tobago", "Tunisia", "Turkey", "Turkmenistan", "Turks and Caicos Islands", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States", "United States Minor Outlying Islands", "Uruguay", "Uzbekistan", "Vanuatu", "Venezuela", "Vietnam", "Virgin Islands (British)", "Virgin Islands (U.S.)", "Wallis and Futuna Islands", "Western Sahara", "Yemen", "Yugoslavia", "Zambia", "Zimbabwe");
        $country_select = '<select id="new_country" name="new_country" class="form-control">';

        if ($selected == '') {
            $reales_general_settings = get_option('reales_general_settings');
            $selected = isset($reales_general_settings['reales_country_field']) ? $reales_general_settings['reales_country_field'] : '';
        }

        foreach ($countries as $country) {
            $country_select .= '<option value="' . esc_attr($country) . '"';
            if ($selected == $country) {
                $country_select .= 'selected="selected"';
            }
            $country_select .= '>' . esc_html($country) . '</option>';
        }
        $country_select.='</select>';

        return $country_select;
    }
endif;

if( !function_exists('reales_search_country_list') ): 
    function reales_search_country_list($selected) {
        $countries = array("Afghanistan", "Albania", "Algeria", "American Samoa", "Andorra", "Angola", "Anguilla", "Antarctica", "Antigua and Barbuda", "Argentina", "Armenia", "Aruba", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bermuda", "Bhutan", "Bolivia", "Bosnia and Herzegowina", "Botswana", "Bouvet Island", "Brazil", "British Indian Ocean Territory", "Brunei Darussalam", "Bulgaria", "Burkina Faso", "Burundi", "Cambodia", "Cameroon", "Canada", "Cape Verde", "Cayman Islands", "Central African Republic", "Chad", "Chile", "China", "Christmas Island", "Cocos (Keeling) Islands", "Colombia", "Comoros", "Congo", "Congo, the Democratic Republic of the", "Cook Islands", "Costa Rica", "Cote d'Ivoire", "Croatia (Hrvatska)", "Cuba", "Cyprus", "Czech Republic", "Denmark", "Djibouti", "Dominica", "Dominican Republic", "East Timor", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Ethiopia", "Falkland Islands (Malvinas)", "Faroe Islands", "Fiji", "Finland", "France", "France Metropolitan", "French Guiana", "French Polynesia", "French Southern Territories", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Gibraltar", "Greece", "Greenland", "Grenada", "Guadeloupe", "Guam", "Guatemala", "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Heard and Mc Donald Islands", "Holy See (Vatican City State)", "Honduras", "Hong Kong", "Hungary", "Iceland", "India", "Indonesia", "Iran (Islamic Republic of)", "Iraq", "Ireland", "Israel", "Italy", "Jamaica", "Japan", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Korea, Democratic People's Republic of", "Korea, Republic of", "Kuwait", "Kyrgyzstan", "Lao, People's Democratic Republic", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libyan Arab Jamahiriya", "Liechtenstein", "Lithuania", "Luxembourg", "Macau", "Macedonia, The Former Yugoslav Republic of", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Martinique", "Mauritania", "Mauritius", "Mayotte", "Mexico", "Micronesia, Federated States of", "Moldova, Republic of", "Monaco", "Mongolia", "Montserrat", "Morocco", "Mozambique", "Myanmar", "Namibia", "Nauru", "Nepal", "Netherlands", "Netherlands Antilles", "New Caledonia", "New Zealand", "Nicaragua", "Niger", "Nigeria", "Niue", "Norfolk Island", "Northern Mariana Islands", "Norway", "Oman", "Pakistan", "Palau", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Pitcairn", "Poland", "Portugal", "Puerto Rico", "Qatar", "Reunion", "Romania", "Russian Federation", "Rwanda", "Saint Kitts and Nevis", "Saint Lucia", "Saint Vincent and the Grenadines", "Samoa", "San Marino", "Sao Tome and Principe", "Saudi Arabia", "Senegal", "Seychelles", "Sierra Leone", "Singapore", "Slovakia (Slovak Republic)", "Slovenia", "Solomon Islands", "Somalia", "South Africa", "South Georgia and the South Sandwich Islands", "Spain", "Sri Lanka", "St. Helena", "St. Pierre and Miquelon", "Sudan", "Suriname", "Svalbard and Jan Mayen Islands", "Swaziland", "Sweden", "Switzerland", "Syrian Arab Republic", "Taiwan, Province of China", "Tajikistan", "Tanzania, United Republic of", "Thailand", "Togo", "Tokelau", "Tonga", "Trinidad and Tobago", "Tunisia", "Turkey", "Turkmenistan", "Turks and Caicos Islands", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States", "United States Minor Outlying Islands", "Uruguay", "Uzbekistan", "Vanuatu", "Venezuela", "Vietnam", "Virgin Islands (British)", "Virgin Islands (U.S.)", "Wallis and Futuna Islands", "Western Sahara", "Yemen", "Yugoslavia", "Zambia", "Zimbabwe");
        $country_select = '<select id="search_country" name="search_country" class="form-control">';

        if ($selected == '') {
            $reales_general_settings = get_option('reales_general_settings');
            $selected = isset($reales_general_settings['reales_country_field']) ? $reales_general_settings['reales_country_field'] : '';
        }

        foreach ($countries as $country) {
            $country_select .= '<option value="' . esc_attr($country) . '"';
            if ($selected == $country) {
                $country_select .= 'selected="selected"';
            }
            $country_select .= '>' . esc_html($country) . '</option>';
        }
        $country_select.='</select>';

        return $country_select;
    }
endif;

if (!function_exists('reales_entry_meta')) :
    function reales_entry_meta() {
        if ( is_sticky() && is_home() && ! is_paged() )
            echo '<span class="featured-post">' . __( 'Sticky', 'reales' ) . '</span>';

        if ( ! has_post_format( 'link' ) && 'post' == get_post_type() )
            reales_entry_date();

        $categories_list = get_the_category_list( __( ', ', 'reales' ) );
        if ( $categories_list ) {
            echo '<span class="categories-links">' . esc_html($categories_list) . '</span>';
        }

        $tag_list = get_the_tag_list( '', __( ', ', 'reales' ) );
        if ( $tag_list ) {
            echo '<span class="tags-links">' . esc_html($tag_list) . '</span>';
        }

        if ( 'post' == get_post_type() ) {
            printf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s" rel="author">%3$s</a></span>',
                esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
                esc_attr( sprintf( __( 'View all posts by %s', 'reales' ), get_the_author() ) ),
                get_the_author()
            );
        }
    }
endif;

if (!function_exists('reales_entry_date')) :
    function reales_entry_date( $echo = true ) {
        if ( has_post_format( array( 'chat', 'status' ) ) )
            $format_prefix = _x( '%1$s on %2$s', '1: post format name. 2: date', 'reales' );
        else
            $format_prefix = '%2$s';

        $date = sprintf( '<span class="date"><a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s">%4$s</time></a></span>',
            esc_url( get_permalink() ),
            esc_attr( sprintf( __( 'Permalink to %s', 'reales' ), the_title_attribute( 'echo=0' ) ) ),
            esc_attr( get_the_date( 'c' ) ),
            esc_html( sprintf( $format_prefix, get_post_format_string( get_post_format() ), get_the_date() ) )
        );

        if ( $echo )
            echo $date;

        return $date;
    }
endif;

if (!function_exists('reales_wp_title')) :
    function reales_wp_title( $title, $sep ) {

        global $page, $paged;

        $title .= get_bloginfo( 'name', 'display' );

        $site_description = get_bloginfo( 'description', 'display' );
        if ( $site_description && ( is_home() || is_front_page() || is_archive() || is_search() ) ) {
            $title .= " $sep $site_description";
        }

        return $title;
    }
endif;
add_filter( 'wp_title', 'reales_wp_title', 10, 2 );

if (!function_exists('reales_sanitize_item')) :
    function reales_sanitize_item($item) {
        return sanitize_text_field($item);
    }
endif;

if (!function_exists('reales_sanitize_multi_array')) :
    function reales_sanitize_multi_array(&$item, $key) {
        $item = sanitize_text_field($item);
    }
endif;

if (!function_exists('reales_breadcrumbs')) :
    function reales_breadcrumbs() {
        global $post;
        if (!is_front_page()) {
            echo '<div class="page_bc">';
            echo '<a href="';
            echo esc_url( home_url() );
            echo '">';
            echo '<span class="icon-home"></span>&nbsp;';
            echo esc_html(__('Home', 'reales'));
            echo '</a>';
            if (is_category() || is_single()) {
                if (is_single()) {
                    echo '&nbsp;&nbsp;<span class="fa fa-angle-right"></span>&nbsp;&nbsp;';
                    the_title();
                }
            } elseif (is_page()) {
                echo '&nbsp;&nbsp;<span class="fa fa-angle-right"></span>&nbsp;&nbsp;';
                echo the_title();
            }
            echo '</div>';
        }
    }
endif;

if (!function_exists('money_format')) :
    function money_format($format, $number) {
        while (true) { 
            $replaced = preg_replace('/(-?\d+)(\d\d\d)/', '$1,$2', $number); 
            if ($replaced != $number) { 
                $number = $replaced; 
            } else { 
                break; 
            } 
        } 
        return $number; 
    }
endif;

?>