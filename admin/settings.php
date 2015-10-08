<?php
/**
 * @package WordPress
 * @subpackage Reales
 */

add_action( 'admin_menu', 'reales_add_admin_menu' );
add_action( 'admin_init', 'reales_settings_init' );

if( !function_exists('reales_add_admin_menu') ): 
    function reales_add_admin_menu() { 
        add_theme_page('Reales WP Settings', 'Reales WP Settings', 'administrator', 'admin/settings.php', 'reales_settings_page');
    }
endif;

if( !function_exists('reales_settings_init') ): 
    function reales_settings_init() {
        wp_enqueue_style('font_awesome', get_template_directory_uri().'/css/font-awesome.css', false, '1.0', 'all');
        wp_enqueue_style('simple_line_icons', get_template_directory_uri().'/css/simple-line-icons.css', false, '1.0', 'all');
        wp_enqueue_style('tagsinput_style', get_template_directory_uri().'/css/jquery.tagsinput.css', false, '1.0', 'all');
        wp_enqueue_style('reales_settings_style', get_template_directory_uri().'/admin/css/style.css', false, '1.0', 'all');
        wp_enqueue_style('reales_icons_style', get_template_directory_uri().'/css/icons.css', false, '1.0', 'all');
        wp_enqueue_script('media-upload');
        wp_enqueue_style('thickbox');
        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script('thickbox');
        wp_enqueue_script('my-upload');
        wp_enqueue_script('tagsinput', get_template_directory_uri().'/js/jquery.tagsinput.min.js', false, '1.0', true);
        wp_enqueue_script('settings', get_template_directory_uri().'/admin/js/admin.js', array('wp-color-picker'), '1.0', true);

        wp_localize_script('settings', 'settings_vars', 
            array(  
                'amenities_placeholder' => __('Add new', 'reales'),
                'admin_url' => get_admin_url(),
                'text' => __('Text', 'reales'),
                'numeric' => __('Numeric', 'reales'),
                'date' => __('Date', 'reales'),
                'no' => __('No', 'reales'),
                'yes' => __('Yes', 'reales'),
                'delete' => __('Delete', 'reales')
            )
        );

        register_setting( 'reales_general_settings', 'reales_general_settings' );
        register_setting( 'reales_contact_settings', 'reales_contact_settings' );
        register_setting( 'reales_appearance_settings', 'reales_appearance_settings' );
        register_setting( 'reales_gmaps_settings', 'reales_gmaps_settings' );
        register_setting( 'reales_colors_settings', 'reales_colors_settings' );
        register_setting( 'reales_amenities_settings', 'reales_amenities_settings' );
        register_setting( 'reales_prop_fields_settings', 'reales_prop_fields_settings' );
        register_setting( 'reales_fields_settings', 'reales_fields_settings' );
        register_setting( 'reales_search_settings', 'reales_search_settings' );
        register_setting( 'reales_filter_settings', 'reales_filter_settings' );
        register_setting( 'reales_auth_settings', 'reales_auth_settings' );
    }
endif;

if( !function_exists('reales_admin_general_settings') ): 
    function reales_admin_general_settings() {
        add_settings_section( 'reales_generalSettings_section', __( 'General Settings', 'reales' ), 'reales_general_settings_section_callback', 'reales_general_settings' );
        add_settings_field( 'reales_logo_field', __( 'Logo', 'reales' ), 'reales_logo_field_render', 'reales_general_settings', 'reales_generalSettings_section' );
        add_settings_field( 'reales_app_logo_field', __( 'App Logo (32x32px)', 'reales' ), 'reales_app_logo_field_render', 'reales_general_settings', 'reales_generalSettings_section' );
        add_settings_field( 'reales_country_field', __( 'Country', 'reales' ), 'reales_country_field_render', 'reales_general_settings', 'reales_generalSettings_section' );
        add_settings_field( 'reales_currency_symbol_field', __( 'Currency Symbol', 'reales' ), 'reales_currency_symbol_field_render', 'reales_general_settings', 'reales_generalSettings_section' );
        add_settings_field( 'reales_currency_symbol_pos_field', __( 'Currency Symbol Position', 'reales' ), 'reales_currency_symbol_pos_field_render', 'reales_general_settings', 'reales_generalSettings_section' );
        add_settings_field( 'reales_max_price_field', __( 'Max price for properties filter', 'reales' ), 'reales_max_price_field_render', 'reales_general_settings', 'reales_generalSettings_section' );
        add_settings_field( 'reales_unit_field', __( 'Measurement Unit', 'reales' ), 'reales_unit_field_render', 'reales_general_settings', 'reales_generalSettings_section' );
        add_settings_field( 'reales_max_area_field', __( 'Max area value for properties filter', 'reales' ), 'reales_max_area_field_render', 'reales_general_settings', 'reales_generalSettings_section' );
        add_settings_field( 'reales_review_field', __( 'Front-end property publish without admin approval', 'reales' ), 'reales_review_field_render', 'reales_general_settings', 'reales_generalSettings_section' );
    }
endif;

if( !function_exists('reales_admin_contact') ): 
    function reales_admin_contact() {
        add_settings_section( 'reales_contact_section', __( 'Contact', 'reales' ), 'reales_contact_section_callback', 'reales_contact_settings' );
        add_settings_field( 'reales_company_name_field',  __( 'Company Name', 'reales' ), 'reales_company_name_field_render', 'reales_contact_settings', 'reales_contact_section' );
        add_settings_field( 'reales_company_email_field',  __( 'E-mail', 'reales' ), 'reales_company_email_field_render', 'reales_contact_settings', 'reales_contact_section' );
        add_settings_field( 'reales_company_phone_field',  __( 'Phone', 'reales' ), 'reales_company_phone_field_render', 'reales_contact_settings', 'reales_contact_section' );
        add_settings_field( 'reales_company_mobile_field',  __( 'Mobile', 'reales' ), 'reales_company_mobile_field_render', 'reales_contact_settings', 'reales_contact_section' );
        add_settings_field( 'reales_company_skype_field',  __( 'Skype', 'reales' ), 'reales_company_skype_field_render', 'reales_contact_settings', 'reales_contact_section' );
        add_settings_field( 'reales_company_address_field',  __( 'Address', 'reales' ), 'reales_company_address_field_render', 'reales_contact_settings', 'reales_contact_section' );
        add_settings_field( 'reales_company_facebook_field',  __( 'Facebook Link', 'reales' ), 'reales_company_facebook_field_render', 'reales_contact_settings', 'reales_contact_section' );
        add_settings_field( 'reales_company_twitter_field',  __( 'Twitter Link', 'reales' ), 'reales_company_twitter_field_render', 'reales_contact_settings', 'reales_contact_section' );
        add_settings_field( 'reales_company_google_field',  __( 'Google+ Link', 'reales' ), 'reales_company_google_field_render', 'reales_contact_settings', 'reales_contact_section' );
        add_settings_field( 'reales_company_linkedin_field',  __( 'LinkedIn Link', 'reales' ), 'reales_company_linkedin_field_render', 'reales_contact_settings', 'reales_contact_section' );
    }
endif;

if( !function_exists('reales_admin_appearance') ): 
    function reales_admin_appearance() {
        add_settings_section( 'reales_appearance_section', __( 'Appearance', 'reales' ), 'reales_appearance_section_callback', 'reales_appearance_settings' );
        add_settings_field( 'reales_user_menu_field', __( 'Show user menu in header', 'reales' ), 'reales_user_menu_field_render', 'reales_appearance_settings', 'reales_appearance_section' );
        add_settings_field( 'reales_home_header_field', __( 'Homepage header type', 'reales' ), 'reales_home_header_field_render', 'reales_appearance_settings', 'reales_appearance_section' );
        add_settings_field( 'reales_home_header_video_field', __( 'Homepage header video', 'reales' ), 'reales_home_header_video_field_render', 'reales_appearance_settings', 'reales_appearance_section' );
        add_settings_field( 'reales_shadow_opacity_field', __( 'Header image shadow opacity', 'reales' ), 'reales_shadow_opacity_field_render', 'reales_appearance_settings', 'reales_appearance_section' );
        add_settings_field( 'reales_home_caption_field', __( 'Show homepage caption', 'reales' ), 'reales_home_caption_field_render', 'reales_appearance_settings', 'reales_appearance_section' );
        add_settings_field( 'reales_home_caption_title_field', __( 'Homepage caption title', 'reales' ), 'reales_home_caption_title_field_render', 'reales_appearance_settings', 'reales_appearance_section' );
        add_settings_field( 'reales_home_caption_subtitle_field', __( 'Homepage caption subtitle', 'reales' ), 'reales_home_caption_subtitle_field_render', 'reales_appearance_settings', 'reales_appearance_section' );
        add_settings_field( 'reales_home_caption_cta_field', __( 'Show homepage caption cta button', 'reales' ), 'reales_home_caption_cta_field_render', 'reales_appearance_settings', 'reales_appearance_section' );
        add_settings_field( 'reales_home_caption_cta_text_field', __( 'Homepage caption cta button text', 'reales' ), 'reales_home_caption_cta_text_field_render', 'reales_appearance_settings', 'reales_appearance_section' );
        add_settings_field( 'reales_home_caption_cta_link_field', __( 'Homepage caption cta button link', 'reales' ), 'reales_home_caption_cta_link_field_render', 'reales_appearance_settings', 'reales_appearance_section' );
        add_settings_field( 'reales_home_spotlight_field', __( 'Show homepage spotlight section', 'reales' ), 'reales_home_spotlight_field_render', 'reales_appearance_settings', 'reales_appearance_section' );
        add_settings_field( 'reales_home_spotlight_title_field', __( 'Homepage spotlight section title', 'reales' ), 'reales_home_spotlight_title_field_render', 'reales_appearance_settings', 'reales_appearance_section' );
        add_settings_field( 'reales_home_spotlight_text_field', __( 'Homepage spotlight section text', 'reales' ), 'reales_home_spotlight_text_field_render', 'reales_appearance_settings', 'reales_appearance_section' );
        add_settings_field( 'reales_sidebar_field', __( 'Sidebar position', 'reales' ), 'reales_sidebar_field_render', 'reales_appearance_settings', 'reales_appearance_section' );
        add_settings_field( 'reales_related_field', __( 'Show related articles on blog post', 'reales' ), 'reales_related_field_render', 'reales_appearance_settings', 'reales_appearance_section' );
        add_settings_field( 'reales_leftside_menu_field', __( 'Show left side menu in app view', 'reales' ), 'reales_leftside_menu_field_render', 'reales_appearance_settings', 'reales_appearance_section' );
        add_settings_field( 'reales_properties_per_page_field', __( 'Number of properties per page', 'reales' ), 'reales_properties_per_page_field_render', 'reales_appearance_settings', 'reales_appearance_section' );
        add_settings_field( 'reales_similar_field', __( 'Show similar properties on property page', 'reales' ), 'reales_similar_field_render', 'reales_appearance_settings', 'reales_appearance_section' );
        add_settings_field( 'reales_breadcrumbs_field', __( 'Show breadcrumbs on pages', 'reales' ), 'reales_breadcrumbs_field_render', 'reales_appearance_settings', 'reales_appearance_section' );
        add_settings_field( 'reales_copyright_field', __( 'Copyright text', 'reales' ), 'reales_copyright_field_render', 'reales_appearance_settings', 'reales_appearance_section' );
    }
endif;

if( !function_exists('reales_admin_gmaps') ): 
    function reales_admin_gmaps() {
        add_settings_section( 'reales_gmaps_section', __( 'Google Maps', 'reales' ), 'reales_gmaps_section_callback', 'reales_gmaps_settings' );
        add_settings_field( 'reales_gmaps_key_field', __( 'Google maps API key', 'reales' ), 'reales_gmaps_key_field_render', 'reales_gmaps_settings', 'reales_gmaps_section' );
        add_settings_field( 'reales_gmaps_lat_field', __( 'Google maps default latitude', 'reales' ), 'reales_gmaps_lat_field_render', 'reales_gmaps_settings', 'reales_gmaps_section' );
        add_settings_field( 'reales_gmaps_lng_field', __( 'Google maps default longitude', 'reales' ), 'reales_gmaps_lng_field_render', 'reales_gmaps_settings', 'reales_gmaps_section' );
        add_settings_field( 'reales_gmaps_zoom_field', __( 'Google maps default zoom level', 'reales' ), 'reales_gmaps_zoom_field_render', 'reales_gmaps_settings', 'reales_gmaps_section' );
        add_settings_field( 'reales_gmaps_location_field', __( 'Homepage map coordinates', 'reales' ), 'reales_gmaps_location_field_render', 'reales_gmaps_settings', 'reales_gmaps_section' );
    }
endif;

if( !function_exists('reales_admin_colors') ): 
    function reales_admin_colors() {
        add_settings_section( 'reales_colors_section', __( 'Colors', 'reales' ), 'reales_colors_section_callback', 'reales_colors_settings' );
        add_settings_field( 'reales_main_color_field', __( 'Main color', 'reales' ), 'reales_main_color_field_render', 'reales_colors_settings', 'reales_colors_section' );
        add_settings_field( 'reales_main_color_dark_field', __( 'Main color dark', 'reales' ), 'reales_main_color_dark_field_render', 'reales_colors_settings', 'reales_colors_section' );
        add_settings_field( 'reales_app_side_bg_field', __( 'App sidebar background color', 'reales' ), 'reales_app_side_bg_field_render', 'reales_colors_settings', 'reales_colors_section' );
        add_settings_field( 'reales_app_side_item_active_bg_field', __( 'App sidebar menu item active background color', 'reales' ), 'reales_app_side_item_active_bg_field_render', 'reales_colors_settings', 'reales_colors_section' );
        add_settings_field( 'reales_app_side_sub_bg_field', __( 'App sidebar submenu background color', 'reales' ), 'reales_app_side_sub_bg_field_render', 'reales_colors_settings', 'reales_colors_section' );
        add_settings_field( 'reales_app_side_sub_item_active_bg_field', __( 'App sidebar submenu item active background color', 'reales' ), 'reales_app_side_sub_item_active_bg_field_render', 'reales_colors_settings', 'reales_colors_section' );
        add_settings_field( 'reales_app_side_text_color_field', __( 'App sidebar menu text color', 'reales' ), 'reales_app_side_text_color_field_render', 'reales_colors_settings', 'reales_colors_section' );
        add_settings_field( 'reales_app_side_sub_text_color_field', __( 'App sidebar submenu text color', 'reales' ), 'reales_app_side_sub_text_color_field_render', 'reales_colors_settings', 'reales_colors_section' );
        add_settings_field( 'reales_app_top_item_active_color_field', __( 'Mobile view header icons active color', 'reales' ), 'reales_app_top_item_active_color_field_render', 'reales_colors_settings', 'reales_colors_section' );
        add_settings_field( 'reales_footer_bg_field', __( 'Footer background color', 'reales' ), 'reales_footer_bg_field_render', 'reales_colors_settings', 'reales_colors_section' );
        add_settings_field( 'reales_footer_header_color_field', __( 'Footer headers text color', 'reales' ), 'reales_footer_header_color_field_render', 'reales_colors_settings', 'reales_colors_section' );
        add_settings_field( 'reales_prop_type_badge_bg_field', __( 'Property type badge background color', 'reales' ), 'reales_prop_type_badge_bg_field_render', 'reales_colors_settings', 'reales_colors_section' );
        add_settings_field( 'reales_fav_icon_color_field', __( 'Favourite icon color', 'reales' ), 'reales_fav_icon_color_field_render', 'reales_colors_settings', 'reales_colors_section' );
        add_settings_field( 'reales_marker_color_field', __( 'Map markers color', 'reales' ), 'reales_marker_color_field_render', 'reales_colors_settings', 'reales_colors_section' );
        add_settings_field( 'reales_prop_pending_label_bg_field', __( 'Property pending badge background color', 'reales' ), 'reales_prop_pending_label_bg_field_render', 'reales_colors_settings', 'reales_colors_section' );
    }
endif;

if( !function_exists('reales_admin_amenities') ): 
    function reales_admin_amenities() {
        add_settings_section( 'reales_amenities_section', __( 'Amenities', 'reales' ), 'reales_amenities_section_callback', 'reales_amenities_settings' );
        add_settings_field( 'reales_amenities_field', __( 'Amenities List', 'reales' ), 'reales_amenities_field_render', 'reales_amenities_settings', 'reales_amenities_section' );
    }
endif;

if( !function_exists('reales_admin_prop_fields') ): 
    function reales_admin_prop_fields() {
        add_settings_section( 'reales_prop_fields_section', __( 'Property Fileds', 'reales' ), 'reales_prop_fields_section_callback', 'reales_prop_fields_settings' );
        add_settings_field( 'reales_p_description_field', __( 'Description', 'reales' ), 'reales_p_description_field_render', 'reales_prop_fields_settings', 'reales_prop_fields_section' );
        add_settings_field( 'reales_p_category_field', __( 'Category', 'reales' ), 'reales_p_category_field_render', 'reales_prop_fields_settings', 'reales_prop_fields_section' );
        add_settings_field( 'reales_p_type_field', __( 'Type', 'reales' ), 'reales_p_type_field_render', 'reales_prop_fields_settings', 'reales_prop_fields_section' );
        add_settings_field( 'reales_p_city_field', __( 'City', 'reales' ), 'reales_p_city_field_render', 'reales_prop_fields_settings', 'reales_prop_fields_section' );
        add_settings_field( 'reales_p_coordinates_field', __( 'Coordinates', 'reales' ), 'reales_p_coordinates_field_render', 'reales_prop_fields_settings', 'reales_prop_fields_section' );
        add_settings_field( 'reales_p_address_field', __( 'Address', 'reales' ), 'reales_p_address_field_render', 'reales_prop_fields_settings', 'reales_prop_fields_section' );
        add_settings_field( 'reales_p_neighborhood_field', __( 'Neighborhood', 'reales' ), 'reales_p_neighborhood_field_render', 'reales_prop_fields_settings', 'reales_prop_fields_section' );
        add_settings_field( 'reales_p_zip_field', __( 'Zip Code', 'reales' ), 'reales_p_zip_field_render', 'reales_prop_fields_settings', 'reales_prop_fields_section' );
        add_settings_field( 'reales_p_state_field', __( 'County/State', 'reales' ), 'reales_p_state_field_render', 'reales_prop_fields_settings', 'reales_prop_fields_section' );
        add_settings_field( 'reales_p_country_field', __( 'Country', 'reales' ), 'reales_p_country_field_render', 'reales_prop_fields_settings', 'reales_prop_fields_section' );
        add_settings_field( 'reales_p_area_field', __( 'Area', 'reales' ), 'reales_p_area_field_render', 'reales_prop_fields_settings', 'reales_prop_fields_section' );
        add_settings_field( 'reales_p_bedrooms_field', __( 'Bedrooms', 'reales' ), 'reales_p_bedrooms_field_render', 'reales_prop_fields_settings', 'reales_prop_fields_section' );
        add_settings_field( 'reales_p_bathrooms_field', __( 'Bathrooms', 'reales' ), 'reales_p_bathrooms_field_render', 'reales_prop_fields_settings', 'reales_prop_fields_section' );
        add_settings_field( 'reales_p_plans_field', __( 'Floor Plans', 'reales' ), 'reales_p_plans_field_render', 'reales_prop_fields_settings', 'reales_prop_fields_section' );
        add_settings_field( 'reales_p_video_field', __( 'Video', 'reales' ), 'reales_p_video_field_render', 'reales_prop_fields_settings', 'reales_prop_fields_section' );
    }
endif;

if( !function_exists('reales_admin_fields') ): 
    function reales_admin_fields() {
        add_settings_section( 'reales_fields_section', __( 'Property Custom Fileds', 'reales' ), 'reales_fields_section_callback', 'reales_fields_settings' );
    }
endif;

if( !function_exists('reales_admin_search') ): 
    function reales_admin_search() {
        add_settings_section( 'reales_search_section', __( 'Search Area Fields', 'reales' ), 'reales_search_section_callback', 'reales_search_settings' );
        add_settings_field( 'reales_s_country_field', __( 'Country', 'reales' ), 'reales_s_country_field_render', 'reales_search_settings', 'reales_search_section' );
        add_settings_field( 'reales_s_state_field', __( 'County/State', 'reales' ), 'reales_s_state_field_render', 'reales_search_settings', 'reales_search_section' );
        add_settings_field( 'reales_s_city_field', __( 'City', 'reales' ), 'reales_s_city_field_render', 'reales_search_settings', 'reales_search_section' );
        add_settings_field( 'reales_s_neighborhood_field', __( 'Neighborhood', 'reales' ), 'reales_s_neighborhood_field_render', 'reales_search_settings', 'reales_search_section' );
        add_settings_field( 'reales_s_category_field', __( 'Category', 'reales' ), 'reales_s_category_field_render', 'reales_search_settings', 'reales_search_section' );
        add_settings_field( 'reales_s_type_field', __( 'Type', 'reales' ), 'reales_s_type_field_render', 'reales_search_settings', 'reales_search_section' );
        add_settings_field( 'reales_s_price_field', __( 'Price', 'reales' ), 'reales_s_price_field_render', 'reales_search_settings', 'reales_search_section' );
        add_settings_field( 'reales_s_area_field', __( 'Area', 'reales' ), 'reales_s_area_field_render', 'reales_search_settings', 'reales_search_section' );
        add_settings_field( 'reales_s_bedrooms_field', __( 'Bedrooms', 'reales' ), 'reales_s_bedrooms_field_render', 'reales_search_settings', 'reales_search_section' );
        add_settings_field( 'reales_s_bathrooms_field', __( 'Bathrooms', 'reales' ), 'reales_s_bathrooms_field_render', 'reales_search_settings', 'reales_search_section' );
    }
endif;

if( !function_exists('reales_admin_filter') ): 
    function reales_admin_filter() {
        add_settings_section( 'reales_filter_section', __( 'Filter Area Fields', 'reales' ), 'reales_filter_section_callback', 'reales_filter_settings' );
        add_settings_field( 'reales_f_country_field', __( 'Country', 'reales' ), 'reales_f_country_field_render', 'reales_filter_settings', 'reales_filter_section' );
        add_settings_field( 'reales_f_state_field', __( 'County/State', 'reales' ), 'reales_f_state_field_render', 'reales_filter_settings', 'reales_filter_section' );
        add_settings_field( 'reales_f_city_field', __( 'City', 'reales' ), 'reales_f_city_field_render', 'reales_filter_settings', 'reales_filter_section' );
        add_settings_field( 'reales_f_neighborhood_field', __( 'Neighborhood', 'reales' ), 'reales_f_neighborhood_field_render', 'reales_filter_settings', 'reales_filter_section' );
        add_settings_field( 'reales_f_category_field', __( 'Category', 'reales' ), 'reales_f_category_field_render', 'reales_filter_settings', 'reales_filter_section' );
        add_settings_field( 'reales_f_type_field', __( 'Type', 'reales' ), 'reales_f_type_field_render', 'reales_filter_settings', 'reales_filter_section' );
        add_settings_field( 'reales_f_price_field', __( 'Price', 'reales' ), 'reales_f_price_field_render', 'reales_filter_settings', 'reales_filter_section' );
        add_settings_field( 'reales_f_area_field', __( 'Area', 'reales' ), 'reales_f_area_field_render', 'reales_filter_settings', 'reales_filter_section' );
        add_settings_field( 'reales_f_bedrooms_field', __( 'Bedrooms', 'reales' ), 'reales_f_bedrooms_field_render', 'reales_filter_settings', 'reales_filter_section' );
        add_settings_field( 'reales_f_bathrooms_field', __( 'Bathrooms', 'reales' ), 'reales_f_bathrooms_field_render', 'reales_filter_settings', 'reales_filter_section' );
        add_settings_field( 'reales_f_amenities_field', __( 'Amenities', 'reales' ), 'reales_f_amenities_field_render', 'reales_filter_settings', 'reales_filter_section' );
    }
endif;

if( !function_exists('reales_admin_auth') ): 
    function reales_admin_auth() {
        add_settings_section( 'reales_auth_section', __( 'Authentication', 'reales' ), 'reales_auth_section_callback', 'reales_auth_settings' );
        add_settings_field( 'reales_fb_login_field', __( 'Allow Facebook Login', 'reales' ), 'reales_fb_login_field_render', 'reales_auth_settings', 'reales_auth_section' );
        add_settings_field( 'reales_fb_id_field', __( 'Facebook App ID', 'reales' ), 'reales_fb_id_field_render', 'reales_auth_settings', 'reales_auth_section' );
        add_settings_field( 'reales_fb_secret_field', __( 'Facebook App Secret', 'reales' ), 'reales_fb_secret_field_render', 'reales_auth_settings', 'reales_auth_section' );
        add_settings_field( 'reales_google_login_field', __( 'Allow Google Signin', 'reales' ), 'reales_google_login_field_render', 'reales_auth_settings', 'reales_auth_section' );
        add_settings_field( 'reales_google_id_field', __( 'Google Client ID', 'reales' ), 'reales_google_id_field_render', 'reales_auth_settings', 'reales_auth_section' );
        add_settings_field( 'reales_google_secret_field', __( 'Google Client Secret', 'reales' ), 'reales_google_secret_field_render', 'reales_auth_settings', 'reales_auth_section' );
        add_settings_field( 'reales_register_agent_field', __( 'Allow users to register as agents', 'reales' ), 'reales_register_agent_field_render', 'reales_auth_settings', 'reales_auth_section' );
    }
endif;

if( !function_exists('reales_logo_field_render') ): 
    function reales_logo_field_render() { 
        $options = get_option( 'reales_general_settings' );
        ?>
        <input id="logoImage" type="text" size="40" name="reales_general_settings[reales_logo_field]" value="<?php if(isset($options['reales_logo_field'])) { echo esc_attr($options['reales_logo_field']); } ?>" />
        <input id="logoImageBtn" type="button"  class="button" value="<?php esc_html_e('Browse...','reales') ?>" />
        <?php
    }
endif;

if( !function_exists('reales_app_logo_field_render') ): 
    function reales_app_logo_field_render() { 
        $options = get_option( 'reales_general_settings' );
        ?>
        <input id="appLogoImage" type="text" size="40" name="reales_general_settings[reales_app_logo_field]" value="<?php if(isset($options['reales_app_logo_field'])) { echo esc_attr($options['reales_app_logo_field']); } ?>" />
        <input id="appLogoImageBtn" type="button"  class="button" value="<?php esc_html_e('Browse...','reales') ?>" />
        <?php
    }
endif;

if( !function_exists('reales_country_field_render') ): 
    function reales_country_field_render() { 
        $options = get_option( 'reales_general_settings' );

        $countries = array("Afghanistan", "Albania", "Algeria", "American Samoa", "Andorra", "Angola", "Anguilla", "Antarctica", "Antigua and Barbuda", "Argentina", "Armenia", "Aruba", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bermuda", "Bhutan", "Bolivia", "Bosnia and Herzegowina", "Botswana", "Bouvet Island", "Brazil", "British Indian Ocean Territory", "Brunei Darussalam", "Bulgaria", "Burkina Faso", "Burundi", "Cambodia", "Cameroon", "Canada", "Cape Verde", "Cayman Islands", "Central African Republic", "Chad", "Chile", "China", "Christmas Island", "Cocos (Keeling) Islands", "Colombia", "Comoros", "Congo", "Congo, the Democratic Republic of the", "Cook Islands", "Costa Rica", "Cote d'Ivoire", "Croatia (Hrvatska)", "Cuba", "Cyprus", "Czech Republic", "Denmark", "Djibouti", "Dominica", "Dominican Republic", "East Timor", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Ethiopia", "Falkland Islands (Malvinas)", "Faroe Islands", "Fiji", "Finland", "France", "France Metropolitan", "French Guiana", "French Polynesia", "French Southern Territories", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Gibraltar", "Greece", "Greenland", "Grenada", "Guadeloupe", "Guam", "Guatemala", "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Heard and Mc Donald Islands", "Holy See (Vatican City State)", "Honduras", "Hong Kong", "Hungary", "Iceland", "India", "Indonesia", "Iran (Islamic Republic of)", "Iraq", "Ireland", "Israel", "Italy", "Jamaica", "Japan", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Korea, Democratic People's Republic of", "Korea, Republic of", "Kuwait", "Kyrgyzstan", "Lao, People's Democratic Republic", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libyan Arab Jamahiriya", "Liechtenstein", "Lithuania", "Luxembourg", "Macau", "Macedonia, The Former Yugoslav Republic of", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Martinique", "Mauritania", "Mauritius", "Mayotte", "Mexico", "Micronesia, Federated States of", "Moldova, Republic of", "Monaco", "Mongolia", "Montserrat", "Morocco", "Mozambique", "Myanmar", "Namibia", "Nauru", "Nepal", "Netherlands", "Netherlands Antilles", "New Caledonia", "New Zealand", "Nicaragua", "Niger", "Nigeria", "Niue", "Norfolk Island", "Northern Mariana Islands", "Norway", "Oman", "Pakistan", "Palau", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Pitcairn", "Poland", "Portugal", "Puerto Rico", "Qatar", "Reunion", "Romania", "Russian Federation", "Rwanda", "Saint Kitts and Nevis", "Saint Lucia", "Saint Vincent and the Grenadines", "Samoa", "San Marino", "Sao Tome and Principe", "Saudi Arabia", "Senegal", "Seychelles", "Sierra Leone", "Singapore", "Slovakia (Slovak Republic)", "Slovenia", "Solomon Islands", "Somalia", "South Africa", "South Georgia and the South Sandwich Islands", "Spain", "Sri Lanka", "St. Helena", "St. Pierre and Miquelon", "Sudan", "Suriname", "Svalbard and Jan Mayen Islands", "Swaziland", "Sweden", "Switzerland", "Syrian Arab Republic", "Taiwan, Province of China", "Tajikistan", "Tanzania, United Republic of", "Thailand", "Togo", "Tokelau", "Tonga", "Trinidad and Tobago", "Tunisia", "Turkey", "Turkmenistan", "Turks and Caicos Islands", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States", "United States Minor Outlying Islands", "Uruguay", "Uzbekistan", "Vanuatu", "Venezuela", "Vietnam", "Virgin Islands (British)", "Virgin Islands (U.S.)", "Wallis and Futuna Islands", "Western Sahara", "Yemen", "Yugoslavia", "Zambia", "Zimbabwe");
        $country_select = '<select id="reales_general_settings[reales_country_field]" name="reales_general_settings[reales_country_field]">';

        foreach($countries as $country) {
            $country_select .= '<option value="' . esc_attr($country) . '"';
            if(isset($options['reales_country_field']) && $options['reales_country_field'] == $country) {
                $country_select .= 'selected="selected"';
            }
            $country_select .= '>' . esc_html($country) . '</option>';
        }

        $country_select .= '</select>';

        print $country_select;
    }
endif;

if( !function_exists('reales_currency_symbol_field_render') ): 
    function reales_currency_symbol_field_render() {
        $options = get_option( 'reales_general_settings' );
        ?>
        <input id="reales_general_settings[reales_currency_symbol_field]" type="text" size="10" name="reales_general_settings[reales_currency_symbol_field]" value="<?php if(isset($options['reales_currency_symbol_field'])) { echo esc_attr($options['reales_currency_symbol_field']); } ?>" />
        <?php
    }
endif;

if( !function_exists('reales_currency_symbol_pos_field_render') ): 
    function reales_currency_symbol_pos_field_render() {
        $options = get_option( 'reales_general_settings' );

        $positions = array("before", "after");
        $position_select = '<select id="reales_general_settings[reales_currency_symbol_pos_field]" name="reales_general_settings[reales_currency_symbol_pos_field]">';

        foreach($positions as $position) {
            $position_select .= '<option value="' . esc_attr($position) . '"';
            if(isset($options['reales_currency_symbol_pos_field']) && $options['reales_currency_symbol_pos_field'] == $position) {
                $position_select .= 'selected="selected"';
            }
            $position_select .= '>' . esc_html($position) . '</option>';
        }

        $position_select .= '</select>';

        print $position_select;
    }
endif;

if( !function_exists('reales_max_price_field_render') ): 
    function reales_max_price_field_render() {
        $options = get_option( 'reales_general_settings' );
        ?>
        <input id="reales_general_settings[reales_max_price_field]" type="text" size="10" name="reales_general_settings[reales_max_price_field]" value="<?php if(isset($options['reales_max_price_field'])) { echo esc_attr($options['reales_max_price_field']); } ?>" />
        <?php
    }
endif;

if( !function_exists('reales_unit_field_render') ): 
    function reales_unit_field_render() {
        $options = get_option( 'reales_general_settings' );

        $units = array(__('sq ft', 'reales'), __('sq m', 'reales'));
        $unit_select = '<select id="reales_general_settings[reales_unit_field]" name="reales_general_settings[reales_unit_field]">';

        foreach($units as $unit) {
            $unit_select .= '<option value="' . esc_attr($unit) . '"';
            if(isset($options['reales_unit_field']) && $options['reales_unit_field'] == $unit) {
                $unit_select .= 'selected="selected"';
            }
            $unit_select .= '>' . esc_html($unit) . '</option>';
        }

        $unit_select .= '</select>';

        print $unit_select;
    }
endif;

if( !function_exists('reales_max_area_field_render') ): 
    function reales_max_area_field_render() {
        $options = get_option( 'reales_general_settings' );
        ?>
        <input id="reales_general_settings[reales_max_area_field]" type="text" size="10" name="reales_general_settings[reales_max_area_field]" value="<?php if(isset($options['reales_max_area_field'])) { echo esc_attr($options['reales_max_area_field']); } ?>" /> <?php if(isset($options['reales_unit_field'])) { echo esc_html($options['reales_unit_field']); } ?>
        <?php
    }
endif;

if( !function_exists('reales_review_field_render') ): 
    function reales_review_field_render() {
        $options = get_option( 'reales_general_settings' );
        ?>
        <input type="checkbox" name="reales_general_settings[reales_review_field]" <?php if(isset($options['reales_review_field'])) { checked( $options['reales_review_field'], 1 ); } ?> value="1">
        <?php
    }
endif;

if( !function_exists('reales_company_name_field_render') ): 
    function reales_company_name_field_render() {
        $options = get_option( 'reales_contact_settings' );
        ?>
        <input id="reales_contact_settings[reales_company_name_field]" type="text" size="40" name="reales_contact_settings[reales_company_name_field]" value="<?php if(isset($options['reales_company_name_field'])) { echo esc_attr($options['reales_company_name_field']); } ?>" />
        <?php
    }
endif;

if( !function_exists('reales_company_email_field_render') ): 
    function reales_company_email_field_render() {
        $options = get_option( 'reales_contact_settings' );
        ?>
        <input id="reales_contact_settings[reales_company_email_field]" type="text" size="40" name="reales_contact_settings[reales_company_email_field]" value="<?php if(isset($options['reales_company_email_field'])) { echo esc_attr($options['reales_company_email_field']); } ?>" />
        <?php
    }
endif;

if( !function_exists('reales_company_phone_field_render') ): 
    function reales_company_phone_field_render() {
        $options = get_option( 'reales_contact_settings' );
        ?>
        <input id="reales_contact_settings[reales_company_phone_field]" type="text" size="40" name="reales_contact_settings[reales_company_phone_field]" value="<?php if(isset($options['reales_company_phone_field'])) { echo esc_attr($options['reales_company_phone_field']); } ?>" />
        <?php
    }
endif;

if( !function_exists('reales_company_mobile_field_render') ): 
    function reales_company_mobile_field_render() {
        $options = get_option( 'reales_contact_settings' );
        ?>
        <input id="reales_contact_settings[reales_company_mobile_field]" type="text" size="40" name="reales_contact_settings[reales_company_mobile_field]" value="<?php if(isset($options['reales_company_mobile_field'])) { echo esc_attr($options['reales_company_mobile_field']); } ?>" />
        <?php
    }
endif;

if( !function_exists('reales_company_skype_field_render') ): 
    function reales_company_skype_field_render() {
        $options = get_option( 'reales_contact_settings' );
        ?>
        <input id="reales_contact_settings[reales_company_skype_field]" type="text" size="40" name="reales_contact_settings[reales_company_skype_field]" value="<?php if(isset($options['reales_company_skype_field'])) { echo esc_attr($options['reales_company_skype_field']); } ?>" />
        <?php
    }
endif;

if( !function_exists('reales_company_address_field_render') ): 
    function reales_company_address_field_render() {
        $options = get_option( 'reales_contact_settings' );
        ?>
        <textarea cols='40' rows='5' name="reales_contact_settings[reales_company_address_field]"><?php if(isset($options['reales_company_address_field'])) { echo esc_html($options['reales_company_address_field']); } ?></textarea>
        <?php
    }
endif;

if( !function_exists('reales_company_facebook_field_render') ): 
    function reales_company_facebook_field_render() {
        $options = get_option( 'reales_contact_settings' );
        ?>
        <input id="reales_contact_settings[reales_company_facebook_field]" type="text" size="40" name="reales_contact_settings[reales_company_facebook_field]" value="<?php if(isset($options['reales_company_facebook_field'])) { echo esc_attr($options['reales_company_facebook_field']); } ?>" />
        <?php
    }
endif;

if( !function_exists('reales_company_twitter_field_render') ): 
    function reales_company_twitter_field_render() {
        $options = get_option( 'reales_contact_settings' );
        ?>
        <input id="reales_contact_settings[reales_company_twitter_field]" type="text" size="40" name="reales_contact_settings[reales_company_twitter_field]" value="<?php if(isset($options['reales_company_twitter_field'])) { echo esc_attr($options['reales_company_twitter_field']); } ?>" />
        <?php
    }
endif;

if( !function_exists('reales_company_google_field_render') ): 
    function reales_company_google_field_render() {
        $options = get_option( 'reales_contact_settings' );
        ?>
        <input id="reales_contact_settings[reales_company_google_field]" type="text" size="40" name="reales_contact_settings[reales_company_google_field]" value="<?php if(isset($options['reales_company_google_field'])) { echo esc_attr($options['reales_company_google_field']); } ?>" />
        <?php
    }
endif;

if( !function_exists('reales_company_linkedin_field_render') ): 
    function reales_company_linkedin_field_render() {
        $options = get_option( 'reales_contact_settings' );
        ?>
        <input id="reales_contact_settings[reales_company_linkedin_field]" type="text" size="40" name="reales_contact_settings[reales_company_linkedin_field]" value="<?php if(isset($options['reales_company_linkedin_field'])) { echo esc_attr($options['reales_company_linkedin_field']); } ?>" />
        <?php
    }
endif;

if( !function_exists('reales_user_menu_field_render') ): 
    function reales_user_menu_field_render() { 
        $options = get_option( 'reales_appearance_settings' );
        ?>
        <input type="checkbox" name="reales_appearance_settings[reales_user_menu_field]" <?php if(isset($options['reales_user_menu_field'])) { checked( $options['reales_user_menu_field'], 1 ); } ?> value="1">
        <?php
    }
endif;

if( !function_exists('reales_home_header_field_render') ): 
    function reales_home_header_field_render() { 
        $options = get_option( 'reales_appearance_settings' );
        $headers = array("slideshow", "video", "google map");
        $header_select = '<select id="reales_appearance_settings[reales_home_header_field]" name="reales_appearance_settings[reales_home_header_field]">';

        foreach($headers as $header) {
            $header_select .= '<option value="' . esc_attr($header) . '"';
            if(isset($options['reales_home_header_field']) && $options['reales_home_header_field'] == $header) {
                $header_select .= 'selected="selected"';
            }
            $header_select .= '>' . esc_html($header) . '</option>';
        }

        $header_select .= '</select>';

        print $header_select;
    }
endif;

if( !function_exists('reales_home_header_video_field_render') ): 
    function reales_home_header_video_field_render() { 
        $options = get_option( 'reales_appearance_settings' );
        ?>
        <input id="homeVideo" type="text" size="40" name="reales_appearance_settings[reales_home_header_video_field]" value="<?php if(isset($options['reales_home_header_video_field'])) { echo esc_attr($options['reales_home_header_video_field']); } ?>" />
        <input id="homeVideoBtn" type="button"  class="button" value="<?php esc_html_e('Browse...','reales') ?>" />
        <?php
    }
endif;

if( !function_exists('reales_shadow_opacity_field_render') ): 
    function reales_shadow_opacity_field_render() { 
        $options = get_option( 'reales_appearance_settings' );
        $opacities = array("0", "10", "20", "30", "40", "50", "60", "70", "80", "90");
        $opacity_select = '<select id="reales_appearance_settings[reales_shadow_opacity_field]" name="reales_appearance_settings[reales_shadow_opacity_field]">';

        foreach($opacities as $opacity) {
            $opacity_select .= '<option value="' . esc_attr($opacity) . '"';
            if(isset($options['reales_shadow_opacity_field']) && $options['reales_shadow_opacity_field'] == $opacity) {
                $opacity_select .= 'selected="selected"';
            }
            $opacity_select .= '>' . esc_html($opacity) . '</option>';
        }

        $opacity_select .= '</select> %';

        print $opacity_select;
    }
endif;

if( !function_exists('reales_home_caption_field_render') ): 
    function reales_home_caption_field_render() { 
        $options = get_option( 'reales_appearance_settings' );
        ?>
        <input type="checkbox" name="reales_appearance_settings[reales_home_caption_field]" <?php if(isset($options['reales_home_caption_field'])) { checked( $options['reales_home_caption_field'], 1 ); } ?> value="1">
        <?php
    }
endif;

if( !function_exists('reales_home_caption_title_field_render') ): 
    function reales_home_caption_title_field_render() { 
        $options = get_option( 'reales_appearance_settings' );
        ?>
        <input type="text" size="40" name="reales_appearance_settings[reales_home_caption_title_field]" value="<?php if(isset($options['reales_home_caption_title_field'])) { echo esc_attr($options['reales_home_caption_title_field']); } ?>" />
        <?php
    }
endif;

if( !function_exists('reales_home_caption_subtitle_field_render') ): 
    function reales_home_caption_subtitle_field_render() { 
        $options = get_option( 'reales_appearance_settings' );
        ?>
        <input type="text" size="40" name="reales_appearance_settings[reales_home_caption_subtitle_field]" value="<?php if(isset($options['reales_home_caption_subtitle_field'])) { echo esc_attr($options['reales_home_caption_subtitle_field']); } ?>" />
        <?php
    }
endif;

if( !function_exists('reales_home_caption_cta_field_render') ): 
    function reales_home_caption_cta_field_render() { 
        $options = get_option( 'reales_appearance_settings' );
        ?>
        <input type="checkbox" name="reales_appearance_settings[reales_home_caption_cta_field]" <?php if(isset($options['reales_home_caption_cta_field'])) { checked( $options['reales_home_caption_cta_field'], 1 ); } ?> value="1">
        <?php
    }
endif;

if( !function_exists('reales_home_caption_cta_text_field_render') ): 
    function reales_home_caption_cta_text_field_render() { 
        $options = get_option( 'reales_appearance_settings' );
        ?>
        <input type="text" size="40" name="reales_appearance_settings[reales_home_caption_cta_text_field]" value="<?php if(isset($options['reales_home_caption_cta_text_field'])) { echo esc_attr($options['reales_home_caption_cta_text_field']); } ?>" />
        <?php
    }
endif;

if( !function_exists('reales_home_caption_cta_link_field_render') ): 
    function reales_home_caption_cta_link_field_render() { 
        $options = get_option( 'reales_appearance_settings' );
        ?>
        <input type="text" size="40" name="reales_appearance_settings[reales_home_caption_cta_link_field]" value="<?php if(isset($options['reales_home_caption_cta_link_field'])) { echo esc_attr($options['reales_home_caption_cta_link_field']); } ?>" />
        <?php
    }
endif;

if( !function_exists('reales_home_spotlight_field_render') ): 
    function reales_home_spotlight_field_render() { 
        $options = get_option( 'reales_appearance_settings' );
        ?>
        <input type="checkbox" name="reales_appearance_settings[reales_home_spotlight_field]" <?php if(isset($options['reales_home_spotlight_field'])) { checked( $options['reales_home_spotlight_field'], 1 ); } ?> value="1">
        <?php
    }
endif;

if( !function_exists('reales_home_spotlight_title_field_render') ): 
    function reales_home_spotlight_title_field_render() { 
        $options = get_option( 'reales_appearance_settings' );
        ?>
        <input type="text" size="40" name="reales_appearance_settings[reales_home_spotlight_title_field]" value="<?php if(isset($options['reales_home_spotlight_title_field'])) { echo esc_attr($options['reales_home_spotlight_title_field']); } ?>" />
        <?php
    }
endif;

if( !function_exists('reales_home_spotlight_text_field_render') ): 
    function reales_home_spotlight_text_field_render() { 
        $options = get_option( 'reales_appearance_settings' );
        ?>
        <textarea cols='40' rows='5' name='reales_appearance_settings[reales_home_spotlight_text_field]'><?php if(isset($options['reales_home_spotlight_text_field'])) { echo esc_html($options['reales_home_spotlight_text_field']); } ?></textarea>
        <?php
    }
endif;

if( !function_exists('reales_sidebar_field_render') ): 
    function reales_sidebar_field_render() { 
        $options = get_option( 'reales_appearance_settings' );
        $sidebars = array("left", "right");
        $sidebar_select = '<select id="reales_appearance_settings[reales_sidebar_field]" name="reales_appearance_settings[reales_sidebar_field]">';

        foreach($sidebars as $sidebar) {
            $sidebar_select .= '<option value="' . esc_attr($sidebar) . '"';
            if(isset($options['reales_sidebar_field']) && $options['reales_sidebar_field'] == $sidebar) {
                $sidebar_select .= 'selected="selected"';
            }
            $sidebar_select .= '>' . esc_html($sidebar) . '</option>';
        }

        $sidebar_select .= '</select>';

        print $sidebar_select;
    }
endif;

if( !function_exists('reales_related_field_render') ): 
    function reales_related_field_render() { 
        $options = get_option( 'reales_appearance_settings' );
        ?>
        <input type="checkbox" name="reales_appearance_settings[reales_related_field]" <?php if(isset($options['reales_related_field'])) { checked( $options['reales_related_field'], 1 ); } ?> value="1">
        <?php
    }
endif;

if( !function_exists('reales_leftside_menu_field_render') ): 
    function reales_leftside_menu_field_render() { 
        $options = get_option( 'reales_appearance_settings' );
        ?>
        <input type="checkbox" name="reales_appearance_settings[reales_leftside_menu_field]" <?php if(isset($options['reales_leftside_menu_field'])) { checked( $options['reales_leftside_menu_field'], 1 ); } ?> value="1">
        <?php
    }
endif;

if( !function_exists('reales_properties_per_page_field_render') ): 
    function reales_properties_per_page_field_render() { 
        $options = get_option( 'reales_appearance_settings' );
        ?>
        <input type="text" size="5" name="reales_appearance_settings[reales_properties_per_page_field]" value="<?php if(isset($options['reales_properties_per_page_field'])) { echo esc_attr($options['reales_properties_per_page_field']); } ?>" />
        <?php
    }
endif;

if( !function_exists('reales_similar_field_render') ): 
    function reales_similar_field_render() { 
        $options = get_option( 'reales_appearance_settings' );
        ?>
        <input type="checkbox" name="reales_appearance_settings[reales_similar_field]" <?php if(isset($options['reales_similar_field'])) { checked( $options['reales_similar_field'], 1 ); } ?> value="1">
        <?php
    }
endif;

if( !function_exists('reales_breadcrumbs_field_render') ): 
    function reales_breadcrumbs_field_render() { 
        $options = get_option( 'reales_appearance_settings' );
        ?>
        <input type="checkbox" name="reales_appearance_settings[reales_breadcrumbs_field]" <?php if(isset($options['reales_breadcrumbs_field'])) { checked( $options['reales_breadcrumbs_field'], 1 ); } ?> value="1">
        <?php
    }
endif;

if( !function_exists('reales_copyright_field_render') ): 
    function reales_copyright_field_render() { 
        $options = get_option( 'reales_appearance_settings' );
        ?>
        <textarea cols='40' rows='5' name='reales_appearance_settings[reales_copyright_field]'><?php if(isset($options['reales_copyright_field'])) { echo esc_html($options['reales_copyright_field']); } ?></textarea>
        <?php
    }
endif;

if( !function_exists('reales_fb_login_field_render') ): 
    function reales_fb_login_field_render() { 
        $options = get_option( 'reales_auth_settings' );
        ?>
        <input type="checkbox" name="reales_auth_settings[reales_fb_login_field]" <?php if(isset($options['reales_fb_login_field'])) { checked( $options['reales_fb_login_field'], 1 ); } ?> value="1">
        <?php
    }
endif;

if( !function_exists('reales_fb_id_field_render') ): 
    function reales_fb_id_field_render() { 
        $options = get_option( 'reales_auth_settings' );
        ?>
        <input type="text" size="40" name="reales_auth_settings[reales_fb_id_field]" value="<?php if(isset($options['reales_fb_id_field'])) { echo esc_attr($options['reales_fb_id_field']); } ?>" />
        <?php
    }
endif;

if( !function_exists('reales_fb_secret_field_render') ): 
    function reales_fb_secret_field_render() { 
        $options = get_option( 'reales_auth_settings' );
        ?>
        <input type="text" size="40" name="reales_auth_settings[reales_fb_secret_field]" value="<?php if(isset($options['reales_fb_secret_field'])) { echo esc_attr($options['reales_fb_secret_field']); } ?>" />
        <?php
    }
endif;

if( !function_exists('reales_google_login_field_render') ): 
    function reales_google_login_field_render() { 
        $options = get_option( 'reales_auth_settings' );
        ?>
        <input type="checkbox" name="reales_auth_settings[reales_google_login_field]" <?php if(isset($options['reales_google_login_field'])) { checked( $options['reales_google_login_field'], 1 ); } ?> value="1">
        <?php
    }
endif;

if( !function_exists('reales_google_id_field_render') ): 
    function reales_google_id_field_render() { 
        $options = get_option( 'reales_auth_settings' );
        ?>
        <input type="text" size="40" name="reales_auth_settings[reales_google_id_field]" value="<?php if(isset($options['reales_google_id_field'])) { echo esc_attr($options['reales_google_id_field']); } ?>" />
        <?php
    }
endif;

if( !function_exists('reales_google_secret_field_render') ): 
    function reales_google_secret_field_render() { 
        $options = get_option( 'reales_auth_settings' );
        ?>
        <input type="text" size="40" name="reales_auth_settings[reales_google_secret_field]" value="<?php if(isset($options['reales_google_secret_field'])) { echo esc_attr($options['reales_google_secret_field']); } ?>" />
        <?php
    }
endif;

if( !function_exists('reales_register_agent_field_render') ): 
    function reales_register_agent_field_render() { 
        $options = get_option( 'reales_auth_settings' );
        ?>
        <input type="checkbox" name="reales_auth_settings[reales_register_agent_field]" <?php if(isset($options['reales_register_agent_field'])) { checked( $options['reales_register_agent_field'], 1 ); } ?> value="1">
        <?php
    }
endif;

if( !function_exists('reales_gmaps_key_field_render') ): 
    function reales_gmaps_key_field_render() {
        $options = get_option( 'reales_gmaps_settings' );
        ?>
        <input type="text" size="40" name="reales_gmaps_settings[reales_gmaps_key_field]" value="<?php if(isset($options['reales_gmaps_key_field'])) { echo esc_attr($options['reales_gmaps_key_field']); } ?>" />
        <p class="help">The Google Maps JavaScript API v3 does not require an API key to function correctly. However, we strongly encourage you to load the Maps API using an APIs Console key. You can get it from <a href="https://developers.google.com/maps/documentation/javascript/tutorial#api_key" target="_blank">here</a>.</p>
        <?php
    }
endif;

if( !function_exists('reales_gmaps_lat_field_render') ): 
    function reales_gmaps_lat_field_render() {
        $options = get_option( 'reales_gmaps_settings' );
        ?>
        <input type="text" size="40" name="reales_gmaps_settings[reales_gmaps_lat_field]" value="<?php if(isset($options['reales_gmaps_lat_field'])) { echo esc_attr($options['reales_gmaps_lat_field']); } ?>" />
        <?php
    }
endif;

if( !function_exists('reales_gmaps_lng_field_render') ): 
    function reales_gmaps_lng_field_render() {
        $options = get_option( 'reales_gmaps_settings' );
        ?>
        <input type="text" size="40" name="reales_gmaps_settings[reales_gmaps_lng_field]" value="<?php if(isset($options['reales_gmaps_lat_field'])) { echo esc_attr($options['reales_gmaps_lng_field']); } ?>" />
        <?php
    }
endif;

if( !function_exists('reales_gmaps_zoom_field_render') ): 
    function reales_gmaps_zoom_field_render() {
        $options = get_option( 'reales_gmaps_settings' );
        $values = array(3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21);
        $value_select = '<select id="reales_gmaps_settings[reales_gmaps_zoom_field]" name="reales_gmaps_settings[reales_gmaps_zoom_field]">';

        foreach($values as $value) {
            $value_select .= '<option value="' . esc_attr($value) . '"';
            if(isset($options['reales_gmaps_zoom_field']) && $options['reales_gmaps_zoom_field'] == $value) {
                $value_select .= 'selected="selected"';
            }
            $value_select .= '>' . esc_html($value) . '</option>';
        }

        $value_select .= '</select>';

        print $value_select;
    }
endif;

if( !function_exists('reales_gmaps_location_field_render') ): 
    function reales_gmaps_location_field_render() {
        $options = get_option( 'reales_gmaps_settings' );
        $values = array("user location", "default coordinates");
        $value_select = '<select id="reales_gmaps_settings[reales_gmaps_location_field]" name="reales_gmaps_settings[reales_gmaps_location_field]">';

        foreach($values as $value) {
            $value_select .= '<option value="' . esc_attr($value) . '"';
            if(isset($options['reales_gmaps_location_field']) && $options['reales_gmaps_location_field'] == $value) {
                $value_select .= 'selected="selected"';
            }
            $value_select .= '>' . esc_html($value) . '</option>';
        }

        $value_select .= '</select>';

        print $value_select;
    }
endif;

if( !function_exists('reales_main_color_field_render') ): 
    function reales_main_color_field_render() { 
        $options = get_option( 'reales_colors_settings' );
        ?>
        <input type="text" class="color-field" name="reales_colors_settings[reales_main_color_field]" value="<?php if(isset($options['reales_main_color_field'])) { echo esc_attr($options['reales_main_color_field']); } ?>">
        <?php
    }
endif;

if( !function_exists('reales_main_color_dark_field_render') ): 
    function reales_main_color_dark_field_render() { 
        $options = get_option( 'reales_colors_settings' );
        ?>
        <input type="text" class="color-field" name="reales_colors_settings[reales_main_color_dark_field]" value="<?php if(isset($options['reales_main_color_dark_field'])) echo esc_attr($options['reales_main_color_dark_field']); ?>">
        <?php
    }
endif;
if( !function_exists('reales_app_side_bg_field_render') ): 
    function reales_app_side_bg_field_render() { 
        $options = get_option( 'reales_colors_settings' );
        ?>
        <input type="text" class="color-field" name="reales_colors_settings[reales_app_side_bg_field]" value="<?php if(isset($options['reales_app_side_bg_field'])) echo esc_attr($options['reales_app_side_bg_field']); ?>">
        <?php
    }
endif;
if( !function_exists('reales_app_side_item_active_bg_field_render') ): 
    function reales_app_side_item_active_bg_field_render() { 
        $options = get_option( 'reales_colors_settings' );
        ?>
        <input type="text" class="color-field" name="reales_colors_settings[reales_app_side_item_active_bg_field]" value="<?php if(isset($options['reales_app_side_item_active_bg_field'])) echo esc_attr($options['reales_app_side_item_active_bg_field']); ?>">
        <?php
    }
endif;
if( !function_exists('reales_app_side_sub_bg_field_render') ): 
    function reales_app_side_sub_bg_field_render() { 
        $options = get_option( 'reales_colors_settings' );
        ?>
        <input type="text" class="color-field" name="reales_colors_settings[reales_app_side_sub_bg_field]" value="<?php if(isset($options['reales_app_side_sub_bg_field'])) echo esc_attr($options['reales_app_side_sub_bg_field']); ?>">
        <?php
    }
endif;
if( !function_exists('reales_app_side_sub_item_active_bg_field_render') ): 
    function reales_app_side_sub_item_active_bg_field_render() { 
        $options = get_option( 'reales_colors_settings' );
        ?>
        <input type="text" class="color-field" name="reales_colors_settings[reales_app_side_sub_item_active_bg_field]" value="<?php if(isset($options['reales_app_side_sub_item_active_bg_field'])) echo esc_attr($options['reales_app_side_sub_item_active_bg_field']); ?>">
        <?php
    }
endif;
if( !function_exists('reales_app_side_text_color_field_render') ): 
    function reales_app_side_text_color_field_render() { 
        $options = get_option( 'reales_colors_settings' );
        ?>
        <input type="text" class="color-field" name="reales_colors_settings[reales_app_side_text_color_field]" value="<?php if(isset($options['reales_app_side_text_color_field'])) echo esc_attr($options['reales_app_side_text_color_field']); ?>">
        <?php
    }
endif;
if( !function_exists('reales_app_side_sub_text_color_field_render') ): 
    function reales_app_side_sub_text_color_field_render() { 
        $options = get_option( 'reales_colors_settings' );
        ?>
        <input type="text" class="color-field" name="reales_colors_settings[reales_app_side_sub_text_color_field]" value="<?php if(isset($options['reales_app_side_sub_text_color_field'])) echo esc_attr($options['reales_app_side_sub_text_color_field']); ?>">
        <?php
    }
endif;
if( !function_exists('reales_app_top_item_active_color_field_render') ): 
    function reales_app_top_item_active_color_field_render() { 
        $options = get_option( 'reales_colors_settings' );
        ?>
        <input type="text" class="color-field" name="reales_colors_settings[reales_app_top_item_active_color_field]" value="<?php if(isset($options['reales_app_top_item_active_color_field'])) echo esc_attr($options['reales_app_top_item_active_color_field']); ?>">
        <?php
    }
endif;
if( !function_exists('reales_footer_bg_field_render') ): 
    function reales_footer_bg_field_render() { 
        $options = get_option( 'reales_colors_settings' );
        ?>
        <input type="text" class="color-field" name="reales_colors_settings[reales_footer_bg_field]" value="<?php if(isset($options['reales_footer_bg_field'])) echo esc_attr($options['reales_footer_bg_field']); ?>">
        <?php
    }
endif;
if( !function_exists('reales_footer_header_color_field_render') ): 
    function reales_footer_header_color_field_render() { 
        $options = get_option( 'reales_colors_settings' );
        ?>
        <input type="text" class="color-field" name="reales_colors_settings[reales_footer_header_color_field]" value="<?php if(isset($options['reales_footer_header_color_field'])) echo esc_attr($options['reales_footer_header_color_field']); ?>">
        <?php
    }
endif;
if( !function_exists('reales_prop_type_badge_bg_field_render') ): 
    function reales_prop_type_badge_bg_field_render() { 
        $options = get_option( 'reales_colors_settings' );
        ?>
        <input type="text" class="color-field" name="reales_colors_settings[reales_prop_type_badge_bg_field]" value="<?php if(isset($options['reales_prop_type_badge_bg_field'])) echo esc_attr($options['reales_prop_type_badge_bg_field']); ?>">
        <?php
    }
endif;
if( !function_exists('reales_fav_icon_color_field_render') ): 
    function reales_fav_icon_color_field_render() { 
        $options = get_option( 'reales_colors_settings' );
        ?>
        <input type="text" class="color-field" name="reales_colors_settings[reales_fav_icon_color_field]" value="<?php if(isset($options['reales_fav_icon_color_field'])) echo esc_attr($options['reales_fav_icon_color_field']); ?>">
        <?php
    }
endif;
if( !function_exists('reales_marker_color_field_render') ): 
    function reales_marker_color_field_render() { 
        $options = get_option( 'reales_colors_settings' );
        ?>
        <input type="text" class="color-field" name="reales_colors_settings[reales_marker_color_field]" value="<?php if(isset($options['reales_marker_color_field'])) echo esc_attr($options['reales_marker_color_field']); ?>">
        <?php
    }
endif;
if( !function_exists('reales_prop_pending_label_bg_field_render') ): 
    function reales_prop_pending_label_bg_field_render() { 
        $options = get_option( 'reales_colors_settings' );
        ?>
        <input type="text" class="color-field" name="reales_colors_settings[reales_prop_pending_label_bg_field]" value="<?php if(isset($options['reales_prop_pending_label_bg_field'])) echo esc_attr($options['reales_prop_pending_label_bg_field']); ?>">
        <?php
    }
endif;

if( !function_exists('reales_amenities_field_render') ): 
    function reales_amenities_field_render() { 
        $options = get_option( 'reales_amenities_settings' );
        ?>
        <input id="amenities_field" type="text" name="reales_amenities_settings[reales_amenities_field]" value="<?php if(isset($options['reales_amenities_field'])) echo esc_attr($options['reales_amenities_field']); ?>" />
        <?php
    }
endif;

if( !function_exists('reales_p_description_field_render') ): 
    function reales_p_description_field_render() { 
        $options = get_option( 'reales_prop_fields_settings' );
        $values = array(__('disabled', 'reales'), __('enabled', 'reales'));
        $value_select = '<select id="reales_prop_fields_settings[reales_p_description_field]" name="reales_prop_fields_settings[reales_p_description_field]">';

        foreach($values as $value) {
            $value_select .= '<option value="' . esc_attr($value) . '"';
            if(isset($options['reales_p_description_field']) && $options['reales_p_description_field'] == $value) {
                $value_select .= 'selected="selected"';
            }
            $value_select .= '>' . esc_html($value) . '</option>';
        }

        $value_select .= '</select>';

        print $value_select;

        $r_values = array(__('not required', 'reales'), __('required', 'reales'));
        $r_value_select = '<select id="reales_prop_fields_settings[reales_p_description_r_field]" name="reales_prop_fields_settings[reales_p_description_r_field]">';

        foreach($r_values as $r_value) {
            $r_value_select .= '<option value="' . esc_attr($r_value) . '"';
            if(isset($options['reales_p_description_r_field']) && $options['reales_p_description_r_field'] == $r_value) {
                $r_value_select .= 'selected="selected"';
            }
            $r_value_select .= '>' . esc_html($r_value) . '</option>';
        }

        $r_value_select .= '</select>';

        print $r_value_select;
    }
endif;

if( !function_exists('reales_p_category_field_render') ): 
    function reales_p_category_field_render() { 
        $options = get_option( 'reales_prop_fields_settings' );
        $values = array(__('disabled', 'reales'), __('enabled', 'reales'));
        $value_select = '<select id="reales_prop_fields_settings[reales_p_category_field]" name="reales_prop_fields_settings[reales_p_category_field]">';

        foreach($values as $value) {
            $value_select .= '<option value="' . esc_attr($value) . '"';
            if(isset($options['reales_p_category_field']) && $options['reales_p_category_field'] == $value) {
                $value_select .= 'selected="selected"';
            }
            $value_select .= '>' . esc_html($value) . '</option>';
        }

        $value_select .= '</select>';

        print $value_select;

        $r_values = array(__('not required', 'reales'), __('required', 'reales'));
        $r_value_select = '<select id="reales_prop_fields_settings[reales_p_category_r_field]" name="reales_prop_fields_settings[reales_p_category_r_field]">';

        foreach($r_values as $r_value) {
            $r_value_select .= '<option value="' . esc_attr($r_value) . '"';
            if(isset($options['reales_p_category_r_field']) && $options['reales_p_category_r_field'] == $r_value) {
                $r_value_select .= 'selected="selected"';
            }
            $r_value_select .= '>' . esc_html($r_value) . '</option>';
        }

        $r_value_select .= '</select>';

        print $r_value_select;
    }
endif;

if( !function_exists('reales_p_type_field_render') ): 
    function reales_p_type_field_render() { 
        $options = get_option( 'reales_prop_fields_settings' );
        $values = array(__('disabled', 'reales'), __('enabled', 'reales'));
        $value_select = '<select id="reales_prop_fields_settings[reales_p_type_field]" name="reales_prop_fields_settings[reales_p_type_field]">';

        foreach($values as $value) {
            $value_select .= '<option value="' . esc_attr($value) . '"';
            if(isset($options['reales_p_type_field']) && $options['reales_p_type_field'] == $value) {
                $value_select .= 'selected="selected"';
            }
            $value_select .= '>' . esc_html($value) . '</option>';
        }

        $value_select .= '</select>';

        print $value_select;

        $r_values = array(__('not required', 'reales'), __('required', 'reales'));
        $r_value_select = '<select id="reales_prop_fields_settings[reales_p_type_r_field]" name="reales_prop_fields_settings[reales_p_type_r_field]">';

        foreach($r_values as $r_value) {
            $r_value_select .= '<option value="' . esc_attr($r_value) . '"';
            if(isset($options['reales_p_type_r_field']) && $options['reales_p_type_r_field'] == $r_value) {
                $r_value_select .= 'selected="selected"';
            }
            $r_value_select .= '>' . esc_html($r_value) . '</option>';
        }

        $r_value_select .= '</select>';

        print $r_value_select;
    }
endif;

if( !function_exists('reales_p_city_field_render') ): 
    function reales_p_city_field_render() { 
        $options = get_option( 'reales_prop_fields_settings' );
        $values = array(__('disabled', 'reales'), __('enabled', 'reales'));
        $value_select = '<select id="reales_prop_fields_settings[reales_p_city_field]" name="reales_prop_fields_settings[reales_p_city_field]">';

        foreach($values as $value) {
            $value_select .= '<option value="' . esc_attr($value) . '"';
            if(isset($options['reales_p_city_field']) && $options['reales_p_city_field'] == $value) {
                $value_select .= 'selected="selected"';
            }
            $value_select .= '>' . esc_html($value) . '</option>';
        }

        $value_select .= '</select>';

        print $value_select;

        $r_values = array(__('not required', 'reales'), __('required', 'reales'));
        $r_value_select = '<select id="reales_prop_fields_settings[reales_p_city_r_field]" name="reales_prop_fields_settings[reales_p_city_r_field]">';

        foreach($r_values as $r_value) {
            $r_value_select .= '<option value="' . esc_attr($r_value) . '"';
            if(isset($options['reales_p_city_r_field']) && $options['reales_p_city_r_field'] == $r_value) {
                $r_value_select .= 'selected="selected"';
            }
            $r_value_select .= '>' . esc_html($r_value) . '</option>';
        }

        $r_value_select .= '</select>';

        print $r_value_select;
    }
endif;

if( !function_exists('reales_p_coordinates_field_render') ): 
    function reales_p_coordinates_field_render() { 
        $options = get_option( 'reales_prop_fields_settings' );
        $values = array(__('disabled', 'reales'), __('enabled', 'reales'));
        $value_select = '<select id="reales_prop_fields_settings[reales_p_coordinates_field]" name="reales_prop_fields_settings[reales_p_coordinates_field]">';

        foreach($values as $value) {
            $value_select .= '<option value="' . esc_attr($value) . '"';
            if(isset($options['reales_p_coordinates_field']) && $options['reales_p_coordinates_field'] == $value) {
                $value_select .= 'selected="selected"';
            }
            $value_select .= '>' . esc_html($value) . '</option>';
        }

        $value_select .= '</select>';

        print $value_select;

        $r_values = array(__('not required', 'reales'), __('required', 'reales'));
        $r_value_select = '<select id="reales_prop_fields_settings[reales_p_coordinates_r_field]" name="reales_prop_fields_settings[reales_p_coordinates_r_field]">';

        foreach($r_values as $r_value) {
            $r_value_select .= '<option value="' . esc_attr($r_value) . '"';
            if(isset($options['reales_p_coordinates_r_field']) && $options['reales_p_coordinates_r_field'] == $r_value) {
                $r_value_select .= 'selected="selected"';
            }
            $r_value_select .= '>' . esc_html($r_value) . '</option>';
        }

        $r_value_select .= '</select>';

        print $r_value_select;
    }
endif;

if( !function_exists('reales_p_address_field_render') ): 
    function reales_p_address_field_render() { 
        $options = get_option( 'reales_prop_fields_settings' );
        $values = array(__('disabled', 'reales'), __('enabled', 'reales'));
        $value_select = '<select id="reales_prop_fields_settings[reales_p_address_field]" name="reales_prop_fields_settings[reales_p_address_field]">';

        foreach($values as $value) {
            $value_select .= '<option value="' . esc_attr($value) . '"';
            if(isset($options['reales_p_address_field']) && $options['reales_p_address_field'] == $value) {
                $value_select .= 'selected="selected"';
            }
            $value_select .= '>' . esc_html($value) . '</option>';
        }

        $value_select .= '</select>';

        print $value_select;

        $r_values = array(__('not required', 'reales'), __('required', 'reales'));
        $r_value_select = '<select id="reales_prop_fields_settings[reales_p_address_r_field]" name="reales_prop_fields_settings[reales_p_address_r_field]">';

        foreach($r_values as $r_value) {
            $r_value_select .= '<option value="' . esc_attr($r_value) . '"';
            if(isset($options['reales_p_address_r_field']) && $options['reales_p_address_r_field'] == $r_value) {
                $r_value_select .= 'selected="selected"';
            }
            $r_value_select .= '>' . esc_html($r_value) . '</option>';
        }

        $r_value_select .= '</select>';

        print $r_value_select;
    }
endif;

if( !function_exists('reales_p_neighborhood_field_render') ): 
    function reales_p_neighborhood_field_render() { 
        $options = get_option( 'reales_prop_fields_settings' );
        $values = array(__('disabled', 'reales'), __('enabled', 'reales'));
        $value_select = '<select id="reales_prop_fields_settings[reales_p_neighborhood_field]" name="reales_prop_fields_settings[reales_p_neighborhood_field]">';

        foreach($values as $value) {
            $value_select .= '<option value="' . esc_attr($value) . '"';
            if(isset($options['reales_p_neighborhood_field']) && $options['reales_p_neighborhood_field'] == $value) {
                $value_select .= 'selected="selected"';
            }
            $value_select .= '>' . esc_html($value) . '</option>';
        }

        $value_select .= '</select>';

        print $value_select;

        $r_values = array(__('not required', 'reales'), __('required', 'reales'));
        $r_value_select = '<select id="reales_prop_fields_settings[reales_p_neighborhood_r_field]" name="reales_prop_fields_settings[reales_p_neighborhood_r_field]">';

        foreach($r_values as $r_value) {
            $r_value_select .= '<option value="' . esc_attr($r_value) . '"';
            if(isset($options['reales_p_neighborhood_r_field']) && $options['reales_p_neighborhood_r_field'] == $r_value) {
                $r_value_select .= 'selected="selected"';
            }
            $r_value_select .= '>' . esc_html($r_value) . '</option>';
        }

        $r_value_select .= '</select>';

        print $r_value_select;
    }
endif;

if( !function_exists('reales_p_zip_field_render') ): 
    function reales_p_zip_field_render() { 
        $options = get_option( 'reales_prop_fields_settings' );
        $values = array(__('disabled', 'reales'), __('enabled', 'reales'));
        $value_select = '<select id="reales_prop_fields_settings[reales_p_zip_field]" name="reales_prop_fields_settings[reales_p_zip_field]">';

        foreach($values as $value) {
            $value_select .= '<option value="' . esc_attr($value) . '"';
            if(isset($options['reales_p_zip_field']) && $options['reales_p_zip_field'] == $value) {
                $value_select .= 'selected="selected"';
            }
            $value_select .= '>' . esc_html($value) . '</option>';
        }

        $value_select .= '</select>';

        print $value_select;

        $r_values = array(__('not required', 'reales'), __('required', 'reales'));
        $r_value_select = '<select id="reales_prop_fields_settings[reales_p_zip_r_field]" name="reales_prop_fields_settings[reales_p_zip_r_field]">';

        foreach($r_values as $r_value) {
            $r_value_select .= '<option value="' . esc_attr($r_value) . '"';
            if(isset($options['reales_p_zip_r_field']) && $options['reales_p_zip_r_field'] == $r_value) {
                $r_value_select .= 'selected="selected"';
            }
            $r_value_select .= '>' . esc_html($r_value) . '</option>';
        }

        $r_value_select .= '</select>';

        print $r_value_select;
    }
endif;

if( !function_exists('reales_p_state_field_render') ): 
    function reales_p_state_field_render() { 
        $options = get_option( 'reales_prop_fields_settings' );
        $values = array(__('disabled', 'reales'), __('enabled', 'reales'));
        $value_select = '<select id="reales_prop_fields_settings[reales_p_state_field]" name="reales_prop_fields_settings[reales_p_state_field]">';

        foreach($values as $value) {
            $value_select .= '<option value="' . esc_attr($value) . '"';
            if(isset($options['reales_p_state_field']) && $options['reales_p_state_field'] == $value) {
                $value_select .= 'selected="selected"';
            }
            $value_select .= '>' . esc_html($value) . '</option>';
        }

        $value_select .= '</select>';

        print $value_select;

        $r_values = array(__('not required', 'reales'), __('required', 'reales'));
        $r_value_select = '<select id="reales_prop_fields_settings[reales_p_state_r_field]" name="reales_prop_fields_settings[reales_p_state_r_field]">';

        foreach($r_values as $r_value) {
            $r_value_select .= '<option value="' . esc_attr($r_value) . '"';
            if(isset($options['reales_p_state_r_field']) && $options['reales_p_state_r_field'] == $r_value) {
                $r_value_select .= 'selected="selected"';
            }
            $r_value_select .= '>' . esc_html($r_value) . '</option>';
        }

        $r_value_select .= '</select>';

        print $r_value_select;
    }
endif;

if( !function_exists('reales_p_country_field_render') ): 
    function reales_p_country_field_render() { 
        $options = get_option( 'reales_prop_fields_settings' );
        $values = array(__('disabled', 'reales'), __('enabled', 'reales'));
        $value_select = '<select id="reales_prop_fields_settings[reales_p_country_field]" name="reales_prop_fields_settings[reales_p_country_field]">';

        foreach($values as $value) {
            $value_select .= '<option value="' . esc_attr($value) . '"';
            if(isset($options['reales_p_country_field']) && $options['reales_p_country_field'] == $value) {
                $value_select .= 'selected="selected"';
            }
            $value_select .= '>' . esc_html($value) . '</option>';
        }

        $value_select .= '</select>';

        print $value_select;

        $r_values = array(__('not required', 'reales'), __('required', 'reales'));
        $r_value_select = '<select id="reales_prop_fields_settings[reales_p_country_r_field]" name="reales_prop_fields_settings[reales_p_country_r_field]">';

        foreach($r_values as $r_value) {
            $r_value_select .= '<option value="' . esc_attr($r_value) . '"';
            if(isset($options['reales_p_country_r_field']) && $options['reales_p_country_r_field'] == $r_value) {
                $r_value_select .= 'selected="selected"';
            }
            $r_value_select .= '>' . esc_html($r_value) . '</option>';
        }

        $r_value_select .= '</select>';

        print $r_value_select;
    }
endif;

if( !function_exists('reales_p_area_field_render') ): 
    function reales_p_area_field_render() { 
        $options = get_option( 'reales_prop_fields_settings' );
        $values = array(__('disabled', 'reales'), __('enabled', 'reales'));
        $value_select = '<select id="reales_prop_fields_settings[reales_p_area_field]" name="reales_prop_fields_settings[reales_p_area_field]">';

        foreach($values as $value) {
            $value_select .= '<option value="' . esc_attr($value) . '"';
            if(isset($options['reales_p_area_field']) && $options['reales_p_area_field'] == $value) {
                $value_select .= 'selected="selected"';
            }
            $value_select .= '>' . esc_html($value) . '</option>';
        }

        $value_select .= '</select>';

        print $value_select;

        $r_values = array(__('not required', 'reales'), __('required', 'reales'));
        $r_value_select = '<select id="reales_prop_fields_settings[reales_p_area_r_field]" name="reales_prop_fields_settings[reales_p_area_r_field]">';

        foreach($r_values as $r_value) {
            $r_value_select .= '<option value="' . esc_attr($r_value) . '"';
            if(isset($options['reales_p_area_r_field']) && $options['reales_p_area_r_field'] == $r_value) {
                $r_value_select .= 'selected="selected"';
            }
            $r_value_select .= '>' . esc_html($r_value) . '</option>';
        }

        $r_value_select .= '</select>';

        print $r_value_select;
    }
endif;

if( !function_exists('reales_p_bedrooms_field_render') ): 
    function reales_p_bedrooms_field_render() { 
        $options = get_option( 'reales_prop_fields_settings' );
        $values = array(__('disabled', 'reales'), __('enabled', 'reales'));
        $value_select = '<select id="reales_prop_fields_settings[reales_p_bedrooms_field]" name="reales_prop_fields_settings[reales_p_bedrooms_field]">';

        foreach($values as $value) {
            $value_select .= '<option value="' . esc_attr($value) . '"';
            if(isset($options['reales_p_bedrooms_field']) && $options['reales_p_bedrooms_field'] == $value) {
                $value_select .= 'selected="selected"';
            }
            $value_select .= '>' . esc_html($value) . '</option>';
        }

        $value_select .= '</select>';

        print $value_select;
    }
endif;

if( !function_exists('reales_p_bathrooms_field_render') ): 
    function reales_p_bathrooms_field_render() { 
        $options = get_option( 'reales_prop_fields_settings' );
        $values = array(__('disabled', 'reales'), __('enabled', 'reales'));
        $value_select = '<select id="reales_prop_fields_settings[reales_p_bathrooms_field]" name="reales_prop_fields_settings[reales_p_bathrooms_field]">';

        foreach($values as $value) {
            $value_select .= '<option value="' . esc_attr($value) . '"';
            if(isset($options['reales_p_bathrooms_field']) && $options['reales_p_bathrooms_field'] == $value) {
                $value_select .= 'selected="selected"';
            }
            $value_select .= '>' . esc_html($value) . '</option>';
        }

        $value_select .= '</select>';

        print $value_select;
    }
endif;

if( !function_exists('reales_p_plans_field_render') ): 
    function reales_p_plans_field_render() { 
        $options = get_option( 'reales_prop_fields_settings' );
        $values = array(__('disabled', 'reales'), __('enabled', 'reales'));
        $value_select = '<select id="reales_prop_fields_settings[reales_p_plans_field]" name="reales_prop_fields_settings[reales_p_plans_field]">';

        foreach($values as $value) {
            $value_select .= '<option value="' . esc_attr($value) . '"';
            if(isset($options['reales_p_plans_field']) && $options['reales_p_plans_field'] == $value) {
                $value_select .= 'selected="selected"';
            }
            $value_select .= '>' . esc_html($value) . '</option>';
        }

        $value_select .= '</select>';

        print $value_select;
    }
endif;

if( !function_exists('reales_p_video_field_render') ): 
    function reales_p_video_field_render() { 
        $options = get_option( 'reales_prop_fields_settings' );
        $values = array(__('disabled', 'reales'), __('enabled', 'reales'));
        $value_select = '<select id="reales_prop_fields_settings[reales_p_video_field]" name="reales_prop_fields_settings[reales_p_video_field]">';

        foreach($values as $value) {
            $value_select .= '<option value="' . esc_attr($value) . '"';
            if(isset($options['reales_p_video_field']) && $options['reales_p_video_field'] == $value) {
                $value_select .= 'selected="selected"';
            }
            $value_select .= '>' . esc_html($value) . '</option>';
        }

        $value_select .= '</select>';

        print $value_select;
    }
endif;

if( !function_exists('reales_s_country_field_render') ): 
    function reales_s_country_field_render() { 
        $options = get_option( 'reales_search_settings' );
        $values = array(__('disabled', 'reales'), __('enabled', 'reales'));
        $value_select = '<select id="reales_search_settings[reales_s_country_field]" name="reales_search_settings[reales_s_country_field]">';

        foreach($values as $value) {
            $value_select .= '<option value="' . esc_attr($value) . '"';
            if(isset($options['reales_s_country_field']) && $options['reales_s_country_field'] == $value) {
                $value_select .= 'selected="selected"';
            }
            $value_select .= '>' . esc_html($value) . '</option>';
        }

        $value_select .= '</select>';

        print $value_select;
    }
endif;

if( !function_exists('reales_s_state_field_render') ): 
    function reales_s_state_field_render() { 
        $options = get_option( 'reales_search_settings' );
        $values = array(__('disabled', 'reales'), __('enabled', 'reales'));
        $value_select = '<select id="reales_search_settings[reales_s_state_field]" name="reales_search_settings[reales_s_state_field]">';

        foreach($values as $value) {
            $value_select .= '<option value="' . esc_attr($value) . '"';
            if(isset($options['reales_s_state_field']) && $options['reales_s_state_field'] == $value) {
                $value_select .= 'selected="selected"';
            }
            $value_select .= '>' . esc_html($value) . '</option>';
        }

        $value_select .= '</select>';

        print $value_select;
    }
endif;

if( !function_exists('reales_s_city_field_render') ): 
    function reales_s_city_field_render() { 
        $options = get_option( 'reales_search_settings' );
        $values = array(__('disabled', 'reales'), __('enabled', 'reales'));
        $value_select = '<select id="reales_search_settings[reales_s_city_field]" name="reales_search_settings[reales_s_city_field]">';

        foreach($values as $value) {
            $value_select .= '<option value="' . esc_attr($value) . '"';
            if(isset($options['reales_s_city_field']) && $options['reales_s_city_field'] == $value) {
                $value_select .= 'selected="selected"';
            }
            $value_select .= '>' . esc_html($value) . '</option>';
        }

        $value_select .= '</select>';

        print $value_select;
    }
endif;

if( !function_exists('reales_s_neighborhood_field_render') ): 
    function reales_s_neighborhood_field_render() { 
        $options = get_option( 'reales_search_settings' );
        $values = array(__('disabled', 'reales'), __('enabled', 'reales'));
        $value_select = '<select id="reales_search_settings[reales_s_neighborhood_field]" name="reales_search_settings[reales_s_neighborhood_field]">';

        foreach($values as $value) {
            $value_select .= '<option value="' . esc_attr($value) . '"';
            if(isset($options['reales_s_neighborhood_field']) && $options['reales_s_neighborhood_field'] == $value) {
                $value_select .= 'selected="selected"';
            }
            $value_select .= '>' . esc_html($value) . '</option>';
        }

        $value_select .= '</select>';

        print $value_select;
    }
endif;

if( !function_exists('reales_s_category_field_render') ): 
    function reales_s_category_field_render() { 
        $options = get_option( 'reales_search_settings' );
        $values = array(__('disabled', 'reales'), __('enabled', 'reales'));
        $value_select = '<select id="reales_search_settings[reales_s_category_field]" name="reales_search_settings[reales_s_category_field]">';

        foreach($values as $value) {
            $value_select .= '<option value="' . esc_attr($value) . '"';
            if(isset($options['reales_s_category_field']) && $options['reales_s_category_field'] == $value) {
                $value_select .= 'selected="selected"';
            }
            $value_select .= '>' . esc_html($value) . '</option>';
        }

        $value_select .= '</select>';

        print $value_select;
    }
endif;

if( !function_exists('reales_s_type_field_render') ): 
    function reales_s_type_field_render() { 
        $options = get_option( 'reales_search_settings' );
        $values = array(__('disabled', 'reales'), __('enabled', 'reales'));
        $value_select = '<select id="reales_search_settings[reales_s_type_field]" name="reales_search_settings[reales_s_type_field]">';

        foreach($values as $value) {
            $value_select .= '<option value="' . esc_attr($value) . '"';
            if(isset($options['reales_s_type_field']) && $options['reales_s_type_field'] == $value) {
                $value_select .= 'selected="selected"';
            }
            $value_select .= '>' . esc_html($value) . '</option>';
        }

        $value_select .= '</select>';

        print $value_select;
    }
endif;

if( !function_exists('reales_s_price_field_render') ): 
    function reales_s_price_field_render() { 
        $options = get_option( 'reales_search_settings' );
        $values = array(__('disabled', 'reales'), __('enabled', 'reales'));
        $value_select = '<select id="reales_search_settings[reales_s_price_field]" name="reales_search_settings[reales_s_price_field]">';

        foreach($values as $value) {
            $value_select .= '<option value="' . esc_attr($value) . '"';
            if(isset($options['reales_s_price_field']) && $options['reales_s_price_field'] == $value) {
                $value_select .= 'selected="selected"';
            }
            $value_select .= '>' . esc_html($value) . '</option>';
        }

        $value_select .= '</select>';

        print $value_select;
    }
endif;

if( !function_exists('reales_s_area_field_render') ): 
    function reales_s_area_field_render() { 
        $options = get_option( 'reales_search_settings' );
        $values = array(__('disabled', 'reales'), __('enabled', 'reales'));
        $value_select = '<select id="reales_search_settings[reales_s_area_field]" name="reales_search_settings[reales_s_area_field]">';

        foreach($values as $value) {
            $value_select .= '<option value="' . esc_attr($value) . '"';
            if(isset($options['reales_s_area_field']) && $options['reales_s_area_field'] == $value) {
                $value_select .= 'selected="selected"';
            }
            $value_select .= '>' . esc_html($value) . '</option>';
        }

        $value_select .= '</select>';

        print $value_select;
    }
endif;

if( !function_exists('reales_s_bedrooms_field_render') ): 
    function reales_s_bedrooms_field_render() { 
        $options = get_option( 'reales_search_settings' );
        $values = array(__('disabled', 'reales'), __('enabled', 'reales'));
        $value_select = '<select id="reales_search_settings[reales_s_bedrooms_field]" name="reales_search_settings[reales_s_bedrooms_field]">';

        foreach($values as $value) {
            $value_select .= '<option value="' . esc_attr($value) . '"';
            if(isset($options['reales_s_bedrooms_field']) && $options['reales_s_bedrooms_field'] == $value) {
                $value_select .= 'selected="selected"';
            }
            $value_select .= '>' . esc_html($value) . '</option>';
        }

        $value_select .= '</select>';

        print $value_select;
    }
endif;

if( !function_exists('reales_s_bathrooms_field_render') ): 
    function reales_s_bathrooms_field_render() { 
        $options = get_option( 'reales_search_settings' );
        $values = array(__('disabled', 'reales'), __('enabled', 'reales'));
        $value_select = '<select id="reales_search_settings[reales_s_bathrooms_field]" name="reales_search_settings[reales_s_bathrooms_field]">';

        foreach($values as $value) {
            $value_select .= '<option value="' . esc_attr($value) . '"';
            if(isset($options['reales_s_bathrooms_field']) && $options['reales_s_bathrooms_field'] == $value) {
                $value_select .= 'selected="selected"';
            }
            $value_select .= '>' . esc_html($value) . '</option>';
        }

        $value_select .= '</select>';

        print $value_select;
    }
endif;

if( !function_exists('reales_f_country_field_render') ): 
    function reales_f_country_field_render() { 
        $options = get_option( 'reales_filter_settings' );
        $values = array(__('disabled', 'reales'), __('enabled', 'reales'));
        $value_select = '<select id="reales_filter_settings[reales_f_country_field]" name="reales_filter_settings[reales_f_country_field]">';

        foreach($values as $value) {
            $value_select .= '<option value="' . esc_attr($value) . '"';
            if(isset($options['reales_f_country_field']) && $options['reales_f_country_field'] == $value) {
                $value_select .= 'selected="selected"';
            }
            $value_select .= '>' . esc_html($value) . '</option>';
        }

        $value_select .= '</select>';

        print $value_select;
    }
endif;

if( !function_exists('reales_f_state_field_render') ): 
    function reales_f_state_field_render() { 
        $options = get_option( 'reales_filter_settings' );
        $values = array(__('disabled', 'reales'), __('enabled', 'reales'));
        $value_select = '<select id="reales_filter_settings[reales_f_state_field]" name="reales_filter_settings[reales_f_state_field]">';

        foreach($values as $value) {
            $value_select .= '<option value="' . esc_attr($value) . '"';
            if(isset($options['reales_f_state_field']) && $options['reales_f_state_field'] == $value) {
                $value_select .= 'selected="selected"';
            }
            $value_select .= '>' . esc_html($value) . '</option>';
        }

        $value_select .= '</select>';

        print $value_select;
    }
endif;

if( !function_exists('reales_f_city_field_render') ): 
    function reales_f_city_field_render() { 
        $options = get_option( 'reales_filter_settings' );
        $values = array(__('disabled', 'reales'), __('enabled', 'reales'));
        $value_select = '<select id="reales_filter_settings[reales_f_city_field]" name="reales_filter_settings[reales_f_city_field]">';

        foreach($values as $value) {
            $value_select .= '<option value="' . esc_attr($value) . '"';
            if(isset($options['reales_f_city_field']) && $options['reales_f_city_field'] == $value) {
                $value_select .= 'selected="selected"';
            }
            $value_select .= '>' . esc_html($value) . '</option>';
        }

        $value_select .= '</select>';

        print $value_select;
    }
endif;

if( !function_exists('reales_f_neighborhood_field_render') ): 
    function reales_f_neighborhood_field_render() { 
        $options = get_option( 'reales_filter_settings' );
        $values = array(__('disabled', 'reales'), __('enabled', 'reales'));
        $value_select = '<select id="reales_filter_settings[reales_f_neighborhood_field]" name="reales_filter_settings[reales_f_neighborhood_field]">';

        foreach($values as $value) {
            $value_select .= '<option value="' . esc_attr($value) . '"';
            if(isset($options['reales_f_neighborhood_field']) && $options['reales_f_neighborhood_field'] == $value) {
                $value_select .= 'selected="selected"';
            }
            $value_select .= '>' . esc_html($value) . '</option>';
        }

        $value_select .= '</select>';

        print $value_select;
    }
endif;

if( !function_exists('reales_f_category_field_render') ): 
    function reales_f_category_field_render() { 
        $options = get_option( 'reales_filter_settings' );
        $values = array(__('disabled', 'reales'), __('enabled', 'reales'));
        $value_select = '<select id="reales_filte_settings[reales_f_category_field]" name="reales_filter_settings[reales_f_category_field]">';

        foreach($values as $value) {
            $value_select .= '<option value="' . esc_attr($value) . '"';
            if(isset($options['reales_f_category_field']) && $options['reales_f_category_field'] == $value) {
                $value_select .= 'selected="selected"';
            }
            $value_select .= '>' . esc_html($value) . '</option>';
        }

        $value_select .= '</select>';

        print $value_select;
    }
endif;

if( !function_exists('reales_f_type_field_render') ): 
    function reales_f_type_field_render() { 
        $options = get_option( 'reales_filter_settings' );
        $values = array(__('disabled', 'reales'), __('enabled', 'reales'));
        $value_select = '<select id="reales_filter_settings[reales_f_type_field]" name="reales_filter_settings[reales_f_type_field]">';

        foreach($values as $value) {
            $value_select .= '<option value="' . esc_attr($value) . '"';
            if(isset($options['reales_f_type_field']) && $options['reales_f_type_field'] == $value) {
                $value_select .= 'selected="selected"';
            }
            $value_select .= '>' . esc_html($value) . '</option>';
        }

        $value_select .= '</select>';

        print $value_select;
    }
endif;

if( !function_exists('reales_f_price_field_render') ): 
    function reales_f_price_field_render() { 
        $options = get_option( 'reales_filter_settings' );
        $values = array(__('disabled', 'reales'), __('enabled', 'reales'));
        $value_select = '<select id="reales_filter_settings[reales_f_price_field]" name="reales_filter_settings[reales_f_price_field]">';

        foreach($values as $value) {
            $value_select .= '<option value="' . esc_attr($value) . '"';
            if(isset($options['reales_f_price_field']) && $options['reales_f_price_field'] == $value) {
                $value_select .= 'selected="selected"';
            }
            $value_select .= '>' . esc_html($value) . '</option>';
        }

        $value_select .= '</select>';

        print $value_select;
    }
endif;

if( !function_exists('reales_f_area_field_render') ): 
    function reales_f_area_field_render() { 
        $options = get_option( 'reales_filter_settings' );
        $values = array(__('disabled', 'reales'), __('enabled', 'reales'));
        $value_select = '<select id="reales_filter_settings[reales_f_area_field]" name="reales_filter_settings[reales_f_area_field]">';

        foreach($values as $value) {
            $value_select .= '<option value="' . esc_attr($value) . '"';
            if(isset($options['reales_f_area_field']) && $options['reales_f_area_field'] == $value) {
                $value_select .= 'selected="selected"';
            }
            $value_select .= '>' . esc_html($value) . '</option>';
        }

        $value_select .= '</select>';

        print $value_select;
    }
endif;

if( !function_exists('reales_f_bedrooms_field_render') ): 
    function reales_f_bedrooms_field_render() { 
        $options = get_option( 'reales_filter_settings' );
        $values = array(__('disabled', 'reales'), __('enabled', 'reales'));
        $value_select = '<select id="reales_filter_settings[reales_f_bedrooms_field]" name="reales_filter_settings[reales_f_bedrooms_field]">';

        foreach($values as $value) {
            $value_select .= '<option value="' . esc_attr($value) . '"';
            if(isset($options['reales_f_bedrooms_field']) && $options['reales_f_bedrooms_field'] == $value) {
                $value_select .= 'selected="selected"';
            }
            $value_select .= '>' . esc_html($value) . '</option>';
        }

        $value_select .= '</select>';

        print $value_select;
    }
endif;

if( !function_exists('reales_f_bathrooms_field_render') ): 
    function reales_f_bathrooms_field_render() { 
        $options = get_option( 'reales_filter_settings' );
        $values = array(__('disabled', 'reales'), __('enabled', 'reales'));
        $value_select = '<select id="reales_filter_settings[reales_f_bathrooms_field]" name="reales_filter_settings[reales_f_bathrooms_field]">';

        foreach($values as $value) {
            $value_select .= '<option value="' . esc_attr($value) . '"';
            if(isset($options['reales_f_bathrooms_field']) && $options['reales_f_bathrooms_field'] == $value) {
                $value_select .= 'selected="selected"';
            }
            $value_select .= '>' . esc_html($value) . '</option>';
        }

        $value_select .= '</select>';

        print $value_select;
    }
endif;

if( !function_exists('reales_f_amenities_field_render') ): 
    function reales_f_amenities_field_render() { 
        $options = get_option( 'reales_filter_settings' );
        $values = array(__('disabled', 'reales'), __('enabled', 'reales'));
        $value_select = '<select id="reales_filter_settings[reales_f_amenities_field]" name="reales_filter_settings[reales_f_amenities_field]">';

        foreach($values as $value) {
            $value_select .= '<option value="' . esc_attr($value) . '"';
            if(isset($options['reales_f_amenities_field']) && $options['reales_f_amenities_field'] == $value) {
                $value_select .= 'selected="selected"';
            }
            $value_select .= '>' . esc_html($value) . '</option>';
        }

        $value_select .= '</select>';

        print $value_select;
    }
endif;

if( !function_exists('reales_general_settings_section_callback') ): 
    function reales_general_settings_section_callback() { 
        echo '';
    }
endif;

if( !function_exists('reales_contact_section_callback') ): 
    function reales_contact_section_callback() { 
        echo '';
    }
endif;

if( !function_exists('reales_appearance_section_callback') ): 
    function reales_appearance_section_callback() { 
        echo '';
    }
endif;

if( !function_exists('reales_gmaps_section_callback') ): 
    function reales_gmaps_section_callback() { 
        echo '';
    }
endif;

if( !function_exists('reales_colors_section_callback') ): 
    function reales_colors_section_callback() { 
        echo '';
    }
endif;

if( !function_exists('reales_amenities_section_callback') ): 
    function reales_amenities_section_callback() { 
        echo '';
    }
endif;

if( !function_exists('reales_fields_section_callback') ): 
    function reales_fields_section_callback() { 
        wp_nonce_field('add_custom_fields_ajax_nonce', 'securityAddCustomFields', true);

        print '<h4>' . __('Add New Custom Filed', 'reales') . '</h4>';
        print '<table class="form-table">
            <tbody>
                <tr>
                    <th scope="row">' . __('Field name', 'reales') . '</th>
                    <td>
                        <input type="text" size="40" name="custom_field_name" id="custom_field_name">
                    </td>
                </tr>
                <tr>
                    <th scope="row">' . __('Field label', 'reales') . '</th>
                    <td>
                        <input type="text" size="40" name="custom_field_label" id="custom_field_label">
                    </td>
                </tr>
                <tr>
                    <th scope="row">' . __('Field type', 'reales') . '</th>
                    <td>
                        <select name="custom_field_type" id="custom_field_type">
                            <option value="text_field">' . __('Text', 'reales') . '</option>
                            <option value="numeric_field">' . __('Numeric', 'reales') . '</option>
                            <option value="date_field">' . __('Date', 'reales') . '</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row">' . __('Mandatory', 'reales') . '</th>
                    <td>
                        <select name="custom_field_mandatory" id="custom_field_mandatory">
                            <option value="no">' . __('No', 'reales') . '</option>
                            <option value="yes">' . __('Yes', 'reales') . '</option>
                        </select>
                    </td>
                </tr>
            </tbody>
        </table>';
        print '<p class="submit"><input type="button" name="add_fields_btn" id="add_fields_btn" class="button button-secondary" value="' . __('Add Field', 'reales') . '"></p>';

        print '<h4>' . __('Cutsom Fields List', 'reales') . '</h4>';
        print '<table class="table table-hover" id="customFieldsTable">
            <thead>
                <tr>
                    <th>' . __('Field name', 'reales') . '</th>
                    <th>' . __('Field label', 'reales') . '</th>
                    <th>' . __('Field type', 'reales') . '</th>
                    <th>' . __('Mandatory', 'reales') . '</th>
                    <th>&nbsp;</th>
                </tr>
            </thead>
            <tbody>';

        $options = get_option( 'reales_fields_settings' );
        if(is_array($options)) {

            foreach ($options as $key => $value) {
                print '<tr>
                    <td><input type="text" name="reales_fields_settings[' . $key . '][name]" value="' . $value['name'] . '"></td>
                    <td><input type="text" name="reales_fields_settings[' . $key . '][label]" value="' . $value['label'] . '"></td>
                    <td>
                        <select name="reales_fields_settings[' . $key . '][type]">';

                print '<option value="text_field"';
                if($value['type'] == 'text_field') {
                    print ' selected ';
                }
                print '>' . __('Text', 'reales') . '</option>';

                print '<option value="numeric_field"';
                if($value['type'] == 'numeric_field') {
                    print ' selected ';
                }
                print '>' . __('Numeric', 'reales') . '</option>';

                print '<option value="date_field"';
                if($value['type'] == 'date_field') {
                    print ' selected ';
                }
                print '>' . __('Date', 'reales') . '</option>';

                print '</select></td>';

                print '<td>
                        <select name="reales_fields_settings[' . $key . '][mandatory]">';

                print '<option value="no"';
                if(isset($value['mandatory']) && $value['mandatory'] == 'no') {
                    print ' selected ';
                }
                print '>' . __('No', 'reales') . '</option>';

                print '<option value="yes"';
                if(isset($value['mandatory']) && $value['mandatory'] == 'yes') {
                    print ' selected ';
                }
                print '>' . __('Yes', 'reales') . '</option>';

                print '</select></td>';

                print '<td><a href="javascript:void(0);" data-row="' . $key . '" class="delete-field">' . __('Delete', 'reales') . '</a></td>';
                print '</tr>';
            }
        }

        print '</tbody></table>';
    }
endif;

if( !function_exists('reales_add_custom_fields') ): 
    function reales_add_custom_fields () {
        check_ajax_referer('add_custom_fields_ajax_nonce', 'security');
        $name = isset($_POST['name']) ? sanitize_text_field($_POST['name']) : '';
        $label = isset($_POST['label']) ? sanitize_text_field($_POST['label']) : '';
        $type = isset($_POST['type']) ? sanitize_text_field($_POST['type']) : '';
        $mandatory = isset($_POST['mandatory']) ? sanitize_text_field($_POST['mandatory']) : '';

        if($name == '') {
            echo json_encode(array('add'=>false, 'message'=>__('Field name is mandatory.', 'reales')));
            exit();
        }
        if($label == '') {
            echo json_encode(array('add'=>false, 'message'=>__('Field label is mandatory.', 'reales')));
            exit();
        }
        if($type == '') {
            echo json_encode(array('add'=>false, 'message'=>__('Field type is mandatory.', 'reales')));
            exit();
        }

        $var_name = str_replace(' ', '_', trim($name));
        $var_name = sanitize_key($var_name);

        $reales_fields_settings = get_option('reales_fields_settings');
        $reales_fields_settings[$var_name]['name'] = $name;
        $reales_fields_settings[$var_name]['label'] = $label;
        $reales_fields_settings[$var_name]['type'] = $type;
        $reales_fields_settings[$var_name]['mandatory'] = $mandatory;
        update_option('reales_fields_settings', $reales_fields_settings);

        echo json_encode(array('add'=>true, 'var_name'=>$var_name, 'name'=>$name, 'label'=>$label, 'type'=>$type, 'mandatory'=>$mandatory));
        exit();

        die();
    }
endif;
add_action( 'wp_ajax_nopriv_reales_add_custom_fields', 'reales_add_custom_fields' );
add_action( 'wp_ajax_reales_add_custom_fields', 'reales_add_custom_fields' );

if( !function_exists('reales_delete_custom_fields') ): 
    function reales_delete_custom_fields () {
        check_ajax_referer('add_custom_fields_ajax_nonce', 'security');
        $field_name = isset($_POST['field_name']) ? sanitize_text_field($_POST['field_name']) : '';

        $reales_fields_settings = get_option('reales_fields_settings');
        unset($reales_fields_settings[$field_name]);
        update_option('reales_fields_settings', $reales_fields_settings);

        echo json_encode(array('delete'=>true));
        exit();

        die();
    }
endif;
add_action( 'wp_ajax_nopriv_reales_delete_custom_fields', 'reales_delete_custom_fields' );
add_action( 'wp_ajax_reales_delete_custom_fields', 'reales_delete_custom_fields' );

if( !function_exists('reales_prop_fields_section_callback') ): 
    function reales_prop_fields_section_callback() { 
        echo '';
    }
endif;

if( !function_exists('reales_search_section_callback') ): 
    function reales_search_section_callback() { 
        echo '';
    }
endif;

if( !function_exists('reales_filter_section_callback') ): 
    function reales_filter_section_callback() { 
        echo '';
    }
endif;

if( !function_exists('reales_auth_section_callback') ): 
    function reales_auth_section_callback() { 
        echo '';
    }
endif;

if( !function_exists('reales_settings_page') ): 
    function reales_settings_page() { 
        $allowed_html = array();
        $active_tab = isset( $_GET[ 'tab' ] ) ? wp_kses( $_GET[ 'tab' ],$allowed_html ) : 'general_settings';
        $tab = 'reales_general_settings';

        switch ($active_tab) {
            case "general_settings":
                reales_admin_general_settings();
                $tab = 'reales_general_settings';
                break;
            case "contact":
                reales_admin_contact();
                $tab = 'reales_contact_settings';
                break;
            case "appearance":
                reales_admin_appearance();
                $tab = 'reales_appearance_settings';
                break;
            case "gmaps":
                reales_admin_gmaps();
                $tab = 'reales_gmaps_settings';
                break;
            case "colors":
                reales_admin_colors();
                $tab = 'reales_colors_settings';
                break;
            case "amenities":
                reales_admin_amenities();
                $tab = 'reales_amenities_settings';
                break;
            case "property_fields":
                reales_admin_prop_fields();
                $tab = 'reales_prop_fields_settings';
                break;
            case "fields":
                reales_admin_fields();
                $tab = 'reales_fields_settings';
                break;
            case "search":
                reales_admin_search();
                $tab = 'reales_search_settings';
                break;
            case "filter":
                reales_admin_filter();
                $tab = 'reales_filter_settings';
                break;
            case "auth":
                reales_admin_auth();
                $tab = 'reales_auth_settings';
                break;
        }
        ?>

        <div class="reales-wrapper">
            <div class="reales-leftSide">
                <div class="reales-logo"><span class="fa fa-home"></span> Reales WP Settings</div>
                <ul class="reales-tabs">
                    <li class="<?php echo $active_tab == 'general_settings' ? 'reales-tab-active' : '' ?>">
                        <a href="themes.php?page=admin/settings.php&tab=general_settings">
                            <span class="icon-settings reales-tab-icon"></span><span class="reales-tab-link"><?php esc_html_e('General Settings','reales') ?></span>
                        </a>
                    </li>
                    <li class="<?php echo $active_tab == 'contact' ? 'reales-tab-active' : '' ?>">
                        <a href="themes.php?page=admin/settings.php&tab=contact">
                            <span class="icon-envelope reales-tab-icon"></span><span class="reales-tab-link"><?php esc_html_e('Contact','reales') ?></span>
                        </a>
                    </li>
                    <li class="<?php echo $active_tab == 'appearance' ? 'reales-tab-active' : '' ?>">
                        <a href="themes.php?page=admin/settings.php&tab=appearance">
                            <span class="icon-screen-desktop reales-tab-icon"></span><span class="reales-tab-link"><?php esc_html_e('Appearance','reales') ?></span>
                        </a>
                    </li>
                    <li class="<?php echo $active_tab == 'gmaps' ? 'reales-tab-active' : '' ?>">
                        <a href="themes.php?page=admin/settings.php&tab=gmaps">
                            <span class="icon-map reales-tab-icon"></span><span class="reales-tab-link"><?php esc_html_e('Google Maps','reales') ?></span>
                        </a>
                    </li>
                    <li class="<?php echo $active_tab == 'colors' ? 'reales-tab-active' : '' ?>">
                        <a href="themes.php?page=admin/settings.php&tab=colors">
                            <span class="icon-drop reales-tab-icon"></span><span class="reales-tab-link"><?php esc_html_e('Colors','reales') ?></span>
                        </a>
                    </li>
                    <li class="<?php echo $active_tab == 'amenities' ? 'reales-tab-active' : '' ?>">
                        <a href="themes.php?page=admin/settings.php&tab=amenities">
                            <span class="icon-grid reales-tab-icon"></span><span class="reales-tab-link"><?php esc_html_e('Amenities','reales') ?></span>
                        </a>
                    </li>
                    <li class="<?php echo $active_tab == 'property_fields' ? 'reales-tab-active' : '' ?>">
                        <a href="themes.php?page=admin/settings.php&tab=property_fields">
                            <span class="icon-list reales-tab-icon"></span><span class="reales-tab-link"><?php esc_html_e('Property Fields','reales') ?></span>
                        </a>
                    </li>
                    <li class="<?php echo $active_tab == 'fields' ? 'reales-tab-active' : '' ?>">
                        <a href="themes.php?page=admin/settings.php&tab=fields">
                            <span class="icon-plus reales-tab-icon"></span><span class="reales-tab-link"><?php esc_html_e('Property Custom Fields','reales') ?></span>
                        </a>
                    </li>
                    <li class="<?php echo $active_tab == 'search' ? 'reales-tab-active' : '' ?>">
                        <a href="themes.php?page=admin/settings.php&tab=search">
                            <span class="icon-magnifier reales-tab-icon"></span><span class="reales-tab-link"><?php esc_html_e('Search Area Fields','reales') ?></span>
                        </a>
                    </li>
                    <li class="<?php echo $active_tab == 'filter' ? 'reales-tab-active' : '' ?>">
                        <a href="themes.php?page=admin/settings.php&tab=filter">
                            <span class="icon-equalizer reales-tab-icon"></span><span class="reales-tab-link"><?php esc_html_e('Filter Area Fields','reales') ?></span>
                        </a>
                    </li>
                    <li class="<?php echo $active_tab == 'auth' ? 'reales-tab-active' : '' ?>">
                        <a href="themes.php?page=admin/settings.php&tab=auth">
                            <span class="icon-user reales-tab-icon"></span><span class="reales-tab-link"><?php esc_html_e('Authentication','reales') ?></span>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="reales-content">
                <form action='options.php' method='post'>
                    <?php
                    wp_nonce_field( 'update-options' );
                    settings_fields( $tab );
                    do_settings_sections( $tab );
                    submit_button();
                    ?>
                </form>
            </div>
            <div class="clearfix"></div>
        </div>

        <?php
    }
endif;

?>