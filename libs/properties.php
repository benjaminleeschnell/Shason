<?php
/**
 * @package WordPress
 * @subpackage Reales
 */

/**
 * Get single property
 */
if( !function_exists('reales_get_single_property') ): 
    function reales_get_single_property() {
        check_ajax_referer('app_map_ajax_nonce', 'security');

        $single_id = isset($_POST['single_id']) ? sanitize_text_field($_POST['single_id']) : '';

        $args = array(
            'p' => $single_id,
            'posts_per_page' => 1,
            'post_type' => 'property',
            'post_status' => 'publish'
        );


        $query = new WP_Query($args);

        $props = array();
        $reales_general_settings = get_option('reales_general_settings');

        while($query->have_posts()) {
            $query->the_post();

            $post_id = get_the_ID();
            $prop = new stdClass();

            $prop->id = $post_id;
            $prop->title = get_the_title();
            $prop->link = get_permalink($post_id);
            $prop->city = get_post_meta($post_id, 'property_city', true);
            $prop->lat = get_post_meta($post_id, 'property_lat', true);
            $prop->lng = get_post_meta($post_id, 'property_lng', true);
            $prop->address = get_post_meta($post_id, 'property_address', true);
            $prop->state = get_post_meta($post_id, 'property_state', true);
            $prop->zip = get_post_meta($post_id, 'property_zip', true);
            $prop->country = get_post_meta($post_id, 'property_country', true);
            $prop->price = get_post_meta($post_id, 'property_price', true);
            $prop->currency = isset($reales_general_settings['reales_currency_symbol_field']) ? $reales_general_settings['reales_currency_symbol_field'] : '';
            $prop->currency_pos = isset($reales_general_settings['reales_currency_symbol_pos_field']) ? $reales_general_settings['reales_currency_symbol_pos_field'] : '';
            $prop->price_label = get_post_meta($post_id, 'property_price_label', true);
            $prop->area = get_post_meta($post_id, 'property_area', true);
            $prop->unit = isset($reales_general_settings['reales_unit_field']) ? $reales_general_settings['reales_unit_field'] : '';
            $prop->bedrooms = get_post_meta($post_id, 'property_bedrooms', true);
            $prop->bathrooms = get_post_meta($post_id, 'property_bathrooms', true);
            $prop->gallery = get_post_meta($post_id, 'property_gallery', true);
            $prop->type =  wp_get_post_terms($post_id, 'property_type_category');

            array_push($props, $prop);
        }

        wp_reset_postdata();
        wp_reset_query();

        if(count($props) > 0) {
            echo json_encode(array('getprops'=>true, 'props'=>$props));
            exit();
        } else {
            echo json_encode(array('getprops'=>false));
            exit();
        }

        die();
    }
endif;
add_action( 'wp_ajax_nopriv_reales_get_single_property', 'reales_get_single_property' );
add_action( 'wp_ajax_reales_get_single_property', 'reales_get_single_property' );

/**
 * Get searched properties
 */
if( !function_exists('reales_get_searched_properties') ): 
    function reales_get_searched_properties() {
        check_ajax_referer('app_map_ajax_nonce', 'security');

        $search_country = isset($_POST['country']) ? sanitize_text_field($_POST['country']) : '';
        $search_state = isset($_POST['state']) ? sanitize_text_field($_POST['state']) : '';
        $search_city = isset($_POST['city']) ? sanitize_text_field($_POST['city']) : '';
        $search_category = isset($_POST['category']) ? sanitize_text_field($_POST['category']) : '0';
        $search_type = isset($_POST['type']) ? sanitize_text_field($_POST['type']) : '0';
        $search_min_price = isset($_POST['min_price']) ? sanitize_text_field($_POST['min_price']) : '';
        $search_max_price = isset($_POST['max_price']) ? sanitize_text_field($_POST['max_price']) : '';
        $search_bedrooms = isset($_POST['bedrooms']) ? sanitize_text_field($_POST['bedrooms']) : '';
        $search_bathrooms = isset($_POST['bathrooms']) ? sanitize_text_field($_POST['bathrooms']) : '';
        $search_neighborhood = isset($_POST['neighborhood']) ? sanitize_text_field($_POST['neighborhood']) : '';
        $search_min_area = isset($_POST['min_area']) ? sanitize_text_field($_POST['min_area']) : '';
        $search_max_area = isset($_POST['max_area']) ? sanitize_text_field($_POST['max_area']) : '';
        $search_amenities = isset($_POST['amenities']) ? sanitize_text_field($_POST['amenities']) : '';
        $reales_appearance_settings = get_option('reales_appearance_settings');
        $posts_per_page_setting = isset($reales_appearance_settings['reales_properties_per_page_field']) ? $reales_appearance_settings['reales_properties_per_page_field'] : '';
        $posts_per_page = $posts_per_page_setting != '' ? $posts_per_page_setting : 10;
        $the_page = isset($_POST['page']) ? sanitize_text_field($_POST['page']) : 0;
        $page = ($the_page == 0) ? 1 : $the_page;
        $sort = isset($_POST['sort']) ? sanitize_text_field($_POST['sort']) : 'newest';

        $args = array(
            'posts_per_page' => $posts_per_page,
            'paged' => $page,
            'post_type' => 'property',
            'post_status' => 'publish'
        );

        if($sort == 'price_lo') {
            $args['meta_key'] = 'property_price';
            $args['orderby'] = 'meta_value_num';
            $args['order'] = 'ASC';
        } else if($sort == 'price_hi') {
            $args['meta_key'] = 'property_price';
            $args['orderby'] = 'meta_value_num';
            $args['order'] = 'DESC';
        } else if($sort == 'bedrooms') {
            $args['meta_key'] = 'property_bedrooms';
            $args['orderby'] = 'meta_value_num';
            $args['order'] = 'DESC';
        } else if($sort == 'bathrooms') {
            $args['meta_key'] = 'property_bathrooms';
            $args['orderby'] = 'meta_value_num';
            $args['order'] = 'DESC';
        } else if($sort == 'area') {
            $args['meta_key'] = 'property_area';
            $args['orderby'] = 'meta_value_num';
            $args['order'] = 'DESC';
        }

        if($search_category != '0' && $search_type != '0') {
            $args['tax_query'] = array(
                'relation' => 'AND',
                array(
                    'taxonomy' => 'property_category',
                    'field'    => 'id',
                    'terms'    => $search_category,
                ),
                array(
                    'taxonomy' => 'property_type_category',
                    'field'    => 'id',
                    'terms'    => $search_type,
                ),
            );
        } else if($search_category != '0') {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'property_category',
                    'field'    => 'id',
                    'terms'    => $search_category,
                ),
            );
        } else if($search_type != '0') {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'property_type_category',
                    'field'    => 'id',
                    'terms'    => $search_type,
                ),
            );
        }

        $args['meta_query'] = array('relation' => 'AND');

        if($search_country != '') {
            array_push($args['meta_query'], array(
                'key'     => 'property_country',
                'value'   => $search_country,
            ));
        }

        if($search_state != '') {
            array_push($args['meta_query'], array(
                'key'     => 'property_state',
                'value'   => $search_state,
            ));
        }

        if($search_city != '') {
            array_push($args['meta_query'], array(
                'key'     => 'property_city',
                'value'   => $search_city,
            ));
        }

        if($search_min_price != '' && $search_min_price != '' && is_numeric($search_min_price) && is_numeric($search_max_price)) {
            array_push($args['meta_query'], array(
                'key'     => 'property_price',
                'value'   => array($search_min_price, $search_max_price),
                'compare' => 'BETWEEN',
                'type' => 'NUMERIC'
            ));
        } else if($search_min_price != '' && is_numeric($search_min_price)) {
            array_push($args['meta_query'], array(
                'key'     => 'property_price',
                'value'   => $search_min_price,
                'compare' => '>=',
                'type' => 'NUMERIC'
            ));
        } else if($search_max_price != '' && is_numeric($search_max_price)) {
            array_push($args['meta_query'], array(
                'key'     => 'property_price',
                'value'   => $search_max_price,
                'compare' => '<=',
                'type' => 'NUMERIC'
            ));
        }

        if($search_bedrooms != '' && $search_bedrooms != 0) {
            array_push($args['meta_query'], array(
                'key'     => 'property_bedrooms',
                'value'   => $search_bedrooms,
                'compare' => '>=',
                'type'    => 'NUMERIC'
            ));
        }

        if($search_bathrooms != '' && $search_bathrooms != 0) {
            array_push($args['meta_query'], array(
                'key'     => 'property_bathrooms',
                'value'   => $search_bathrooms,
                'compare' => '>=',
                'type'    => 'NUMERIC'
            ));
        }

        if($search_neighborhood != '') {
            array_push($args['meta_query'], array(
                'key'     => 'property_neighborhood',
                'value'   => $search_neighborhood,
                'compare' => 'LIKE'
            ));
        }

        if($search_min_area != '' && $search_min_area != '' && is_numeric($search_min_area) && is_numeric($search_max_area)) {
            array_push($args['meta_query'], array(
                'key'     => 'property_area',
                'value'   => array($search_min_area, $search_max_area),
                'compare' => 'BETWEEN',
                'type' => 'NUMERIC'
            ));
        } else if($search_min_area != '' && is_numeric($search_min_area)) {
            array_push($args['meta_query'], array(
                'key'     => 'property_area',
                'value'   => $search_min_area,
                'compare' => '>=',
                'type' => 'NUMERIC'
            ));
        } else if($search_max_area != '' && is_numeric($search_max_area)) {
            array_push($args['meta_query'], array(
                'key'     => 'property_area',
                'value'   => $search_max_area,
                'compare' => '<=',
                'type' => 'NUMERIC'
            ));
        }

        if(is_array($search_amenities)) {
            foreach($search_amenities as $amnt) {
                array_push($args['meta_query'], array(
                    'key'     => $amnt,
                    'value'   => 1
                ));
            }
        }


        $query = new WP_Query($args);

        $props = array();
        $reales_general_settings = get_option('reales_general_settings');
        $reales_amenities_settings = get_option('reales_amenities_settings');
        $amenities_list = array();
        $amenities = isset($reales_amenities_settings['reales_amenities_field']) ? $reales_amenities_settings['reales_amenities_field'] : '';
        $amenities_list = explode(',', $amenities);

        while($query->have_posts()) {
            $query->the_post();

            $post_id = get_the_ID();
            $prop = new stdClass();

            $prop->id = $post_id;
            $prop->title = get_the_title();
            $prop->link = get_permalink($post_id);
            $prop->city = get_post_meta($post_id, 'property_city', true);
            $prop->lat = get_post_meta($post_id, 'property_lat', true);
            $prop->lng = get_post_meta($post_id, 'property_lng', true);
            $prop->address = get_post_meta($post_id, 'property_address', true);
            $prop->state = get_post_meta($post_id, 'property_state', true);
            $prop->zip = get_post_meta($post_id, 'property_zip', true);
            $prop->country = get_post_meta($post_id, 'property_country', true);
            $prop->price = get_post_meta($post_id, 'property_price', true);
            $prop->currency = isset($reales_general_settings['reales_currency_symbol_field']) ? $reales_general_settings['reales_currency_symbol_field'] : '';
            $prop->currency_pos = isset($reales_general_settings['reales_currency_symbol_pos_field']) ? $reales_general_settings['reales_currency_symbol_pos_field'] : '';
            $prop->price_label = get_post_meta($post_id, 'property_price_label', true);
            $prop->area = get_post_meta($post_id, 'property_area', true);
            $prop->unit = isset($reales_general_settings['reales_unit_field']) ? $reales_general_settings['reales_unit_field'] : '';
            $prop->bedrooms = get_post_meta($post_id, 'property_bedrooms', true);
            $prop->bathrooms = get_post_meta($post_id, 'property_bathrooms', true);

            $prop->amenities = array();
            if($amenities != '') {
                foreach($amenities_list as $key => $value) {
                    $post_var_name = str_replace(' ', '_', trim($value));
                    $input_name = reales_substr45(sanitize_title($post_var_name));
                    $input_name = sanitize_key($input_name);
                    if (get_post_meta($post_id, $input_name, true) == 1) {
                        array_push($prop->amenities, $value);
                    }
                }
            }

            $prop->agent = get_post_meta($post_id, 'property_agent', true);
            $prop->gallery = get_post_meta($post_id, 'property_gallery', true);
            $prop->category =  wp_get_post_terms($post_id, 'property_category');
            $prop->type =  wp_get_post_terms($post_id, 'property_type_category');

            array_push($props, $prop);
        }

        wp_reset_postdata();

        if(count($props) > 0) {
            echo json_encode(array('getprops'=>true, 'props'=>$props));
            exit();
        } else {
            echo json_encode(array('getprops'=>false));
            exit();
        }

        die();
    }
endif;
add_action( 'wp_ajax_nopriv_reales_get_searched_properties', 'reales_get_searched_properties' );
add_action( 'wp_ajax_reales_get_searched_properties', 'reales_get_searched_properties' );

/**
 * Get properties by city name
 */
if( !function_exists('reales_get_properties_by_city') ): 
    function reales_get_properties_by_city() {
        check_ajax_referer('home_map_ajax_nonce', 'security');

        $city = isset($_POST['city']) ? sanitize_text_field($_POST['city']) : '';

        if ($city != '') {
            $args = array(
                'posts_per_page'   => -1,
                'post_type'        => 'property',
                'orderby'          => 'post_date',
                'order'            => 'DESC',
                'meta_key'         => 'property_city',
                'meta_value'       => $city,
                'post_status'      => 'publish');
            $props = array();
            $posts = get_posts($args);
            $reales_general_settings = get_option('reales_general_settings');
            $reales_amenities_settings = get_option('reales_amenities_settings');
            $amenities_list = array();
            $amenities = isset($reales_amenities_settings['reales_amenities_field']) ? $reales_amenities_settings['reales_amenities_field'] : '';
            $amenities_list = explode(',', $amenities);
            
            foreach($posts as $post) : setup_postdata($post);
                $prop = new stdClass();
                $prop->data = $post;
                $prop->link = get_permalink($post->ID);
                $prop->city = get_post_meta($post->ID, 'property_city', true);
                $prop->lat = get_post_meta($post->ID, 'property_lat', true);
                $prop->lng = get_post_meta($post->ID, 'property_lng', true);
                $prop->address = get_post_meta($post->ID, 'property_address', true);
                $prop->state = get_post_meta($post->ID, 'property_state', true);
                $prop->zip = get_post_meta($post->ID, 'property_zip', true);
                $prop->country = get_post_meta($post->ID, 'property_country', true);
                $prop->price = get_post_meta($post->ID, 'property_price', true);
                $prop->currency = isset($reales_general_settings['reales_currency_symbol_field']) ? $reales_general_settings['reales_currency_symbol_field'] : '';
                $prop->currency_pos = isset($reales_general_settings['reales_currency_symbol_pos_field']) ? $reales_general_settings['reales_currency_symbol_pos_field'] : '';
                $prop->price_label = get_post_meta($post->ID, 'property_price_label', true);
                $prop->area = get_post_meta($post->ID, 'property_area', true);
                $prop->unit = isset($reales_general_settings['reales_unit_field']) ? $reales_general_settings['reales_unit_field'] : '';
                $prop->bedrooms = get_post_meta($post->ID, 'property_bedrooms', true);
                $prop->bathrooms = get_post_meta($post->ID, 'property_bathrooms', true);

                $prop->amenities = array();
                if($amenities != '') {
                    foreach($amenities_list as $key => $value) {
                        $post_var_name = str_replace(' ', '_', trim($value));
                        $input_name = reales_substr45(sanitize_title($post_var_name));
                        $input_name = sanitize_key($input_name);
                        if (get_post_meta($post->ID, $input_name, true) == 1) {
                            array_push($prop->amenities, $value);
                        }
                    }
                }

                $prop->agent = get_post_meta($post->ID, 'property_agent', true);
                $prop->gallery = get_post_meta($post->ID, 'property_gallery', true);
                $prop->category =  wp_get_post_terms($post->ID, 'property_category');
                $prop->type =  wp_get_post_terms($post->ID, 'property_type_category');

                array_push($props, $prop);
            endforeach;

            wp_reset_postdata();
            if(count($props) > 0) {
                echo json_encode(array('getprops'=>true, 'props'=>$props));
                exit();
            } else {
                echo json_encode(array('getprops'=>false));
                exit();
            }
        }

        die();
    }
endif;
add_action( 'wp_ajax_nopriv_reales_get_properties_by_city', 'reales_get_properties_by_city' );
add_action( 'wp_ajax_reales_get_properties_by_city', 'reales_get_properties_by_city' );

/**
 * Get my properties
 */
if( !function_exists('reales_get_my_properties') ): 
    function reales_get_my_properties() {
        check_ajax_referer('app_map_ajax_nonce', 'security');

        $agent_id = isset($_POST['agent_id']) ? sanitize_text_field($_POST['agent_id']) : '';
        $reales_appearance_settings = get_option('reales_appearance_settings');

        $posts_per_page_setting = isset($reales_appearance_settings['reales_properties_per_page_field']) ? $reales_appearance_settings['reales_properties_per_page_field'] : '';
        $posts_per_page = $posts_per_page_setting != '' ? $posts_per_page_setting : 10;
        $the_page = isset($_POST['page']) ? sanitize_text_field($_POST['page']) : 0;
        $page = ($the_page == 0) ? 1 : $the_page;

        $args = array(
            'posts_per_page' => $posts_per_page,
            'paged' => $page,
            'post_type' => 'property',
            'post_status' => array('publish', 'pending')
        );

        $args['meta_query'] = array(
            'relation' => 'AND',
            array(
                'key'     => 'property_agent',
                'value'   => $agent_id,
            )
        );


        $query = new WP_Query($args);

        $props = array();
        $reales_general_settings = get_option('reales_general_settings');
        $reales_amenities_settings = get_option('reales_amenities_settings');
        $amenities_list = array();
        $amenities = isset($reales_amenities_settings['reales_amenities_field']) ? $reales_amenities_settings['reales_amenities_field'] : '';
        $amenities_list = explode(',', $amenities);
        
        while($query->have_posts()) {
            $query->the_post();

            $post_id = get_the_ID();
            $prop = new stdClass();

            $prop->id = $post_id;
            $prop->title = get_the_title();
            $prop->link = get_permalink($post_id);
            $prop->city = get_post_meta($post_id, 'property_city', true);
            $prop->lat = get_post_meta($post_id, 'property_lat', true);
            $prop->lng = get_post_meta($post_id, 'property_lng', true);
            $prop->address = get_post_meta($post_id, 'property_address', true);
            $prop->state = get_post_meta($post_id, 'property_state', true);
            $prop->zip = get_post_meta($post_id, 'property_zip', true);
            $prop->country = get_post_meta($post_id, 'property_country', true);
            $prop->price = get_post_meta($post_id, 'property_price', true);
            $prop->currency = isset($reales_general_settings['reales_currency_symbol_field']) ? $reales_general_settings['reales_currency_symbol_field'] : '';
            $prop->currency_pos = isset($reales_general_settings['reales_currency_symbol_pos_field']) ? $reales_general_settings['reales_currency_symbol_pos_field'] : '';
            $prop->price_label = get_post_meta($post_id, 'property_price_label', true);
            $prop->area = get_post_meta($post_id, 'property_area', true);
            $prop->unit = isset($reales_general_settings['reales_unit_field']) ? $reales_general_settings['reales_unit_field'] : '';
            $prop->bedrooms = get_post_meta($post_id, 'property_bedrooms', true);
            $prop->bathrooms = get_post_meta($post_id, 'property_bathrooms', true);

            $prop->amenities = array();
            if($amenities != '') {
                foreach($amenities_list as $key => $value) {
                    $post_var_name = str_replace(' ', '_', trim($value));
                    $input_name = reales_substr45(sanitize_title($post_var_name));
                    $input_name = sanitize_key($input_name);
                    if (get_post_meta($post_id, $input_name, true) == 1) {
                        array_push($prop->amenities, $value);
                    }
                }
            }

            $prop->agent = get_post_meta($post_id, 'property_agent', true);
            $prop->gallery = get_post_meta($post_id, 'property_gallery', true);
            $prop->category =  wp_get_post_terms($post_id, 'property_category');
            $prop->type =  wp_get_post_terms($post_id, 'property_type_category');

            array_push($props, $prop);
        }

        wp_reset_postdata();

        if(count($props) > 0) {
            echo json_encode(array('getprops'=>true, 'props'=>$props));
            exit();
        } else {
            echo json_encode(array('getprops'=>false));
            exit();
        }

        die();
    }
endif;
add_action( 'wp_ajax_nopriv_reales_get_my_properties', 'reales_get_my_properties' );
add_action( 'wp_ajax_reales_get_my_properties', 'reales_get_my_properties' );

/**
 * Get my favourite properties
 */
if( !function_exists('reales_get_fav_properties') ): 
    function reales_get_fav_properties() {
        check_ajax_referer('app_map_ajax_nonce', 'security');

        $user_id = isset($_POST['user_id']) ? sanitize_text_field($_POST['user_id']) : '';
        $reales_appearance_settings = get_option('reales_appearance_settings');
        $posts_per_page_setting = isset($reales_appearance_settings['reales_properties_per_page_field']) ? $reales_appearance_settings['reales_properties_per_page_field'] : '';
        $posts_per_page = $posts_per_page_setting != '' ? $posts_per_page_setting : 10;
        $the_page = isset($_POST['page']) ? sanitize_text_field($_POST['page']) : 0;
        $page = ($the_page == 0) ? 1 : $the_page;
        $fav = get_user_meta($user_id, 'property_fav', true);

        if($fav && $fav != '') {

            $args = array(
                'post__in' => $fav,
                'posts_per_page' => $posts_per_page,
                'paged' => $page,
                'post_type' => 'property',
                'post_status' => 'publish',
                'ignore_sticky_posts' => true
            );

            $query = new WP_Query($args);

            $props = array();
            $reales_general_settings = get_option('reales_general_settings');
            $reales_amenities_settings = get_option('reales_amenities_settings');
            $amenities_list = array();
            $amenities = isset($reales_amenities_settings['reales_amenities_field']) ? $reales_amenities_settings['reales_amenities_field'] : '';
            $amenities_list = explode(',', $amenities);
            
            while($query->have_posts()) {
                $query->the_post();

                $post_id = get_the_ID();

                $prop = new stdClass();

                $prop->id = $post_id;
                $prop->title = get_the_title();
                $prop->link = get_permalink($post_id);
                $prop->city = get_post_meta($post_id, 'property_city', true);
                $prop->lat = get_post_meta($post_id, 'property_lat', true);
                $prop->lng = get_post_meta($post_id, 'property_lng', true);
                $prop->address = get_post_meta($post_id, 'property_address', true);
                $prop->state = get_post_meta($post_id, 'property_state', true);
                $prop->zip = get_post_meta($post_id, 'property_zip', true);
                $prop->country = get_post_meta($post_id, 'property_country', true);
                $prop->price = get_post_meta($post_id, 'property_price', true);
                $prop->currency = isset($reales_general_settings['reales_currency_symbol_field']) ? $reales_general_settings['reales_currency_symbol_field'] : '';
                $prop->currency_pos = isset($reales_general_settings['reales_currency_symbol_pos_field']) ? $reales_general_settings['reales_currency_symbol_pos_field'] : '';
                $prop->price_label = get_post_meta($post_id, 'property_price_label', true);
                $prop->area = get_post_meta($post_id, 'property_area', true);
                $prop->unit = isset($reales_general_settings['reales_unit_field']) ? $reales_general_settings['reales_unit_field'] : '';
                $prop->bedrooms = get_post_meta($post_id, 'property_bedrooms', true);
                $prop->bathrooms = get_post_meta($post_id, 'property_bathrooms', true);

                $prop->amenities = array();
                if($amenities != '') {
                    foreach($amenities_list as $key => $value) {
                        $post_var_name = str_replace(' ', '_', trim($value));
                        $input_name = reales_substr45(sanitize_title($post_var_name));
                        $input_name = sanitize_key($input_name);
                        if (get_post_meta($post_id, $input_name, true) == 1) {
                            array_push($prop->amenities, $value);
                        }
                    }
                }

                $prop->agent = get_post_meta($post_id, 'property_agent', true);
                $prop->gallery = get_post_meta($post_id, 'property_gallery', true);
                $prop->category =  wp_get_post_terms($post_id, 'property_category');
                $prop->type =  wp_get_post_terms($post_id, 'property_type_category');

                array_push($props, $prop);
            }

            wp_reset_postdata();

            if(count($props) > 0) {
                echo json_encode(array('getprops'=>true, 'props'=>$props));
                exit();
            } else {
                echo json_encode(array('getprops'=>false));
                exit();
            }
        } else {
            echo json_encode(array('getprops'=>false));
            exit();
        }

        die();
    }
endif;
add_action( 'wp_ajax_nopriv_reales_get_fav_properties', 'reales_get_fav_properties' );
add_action( 'wp_ajax_reales_get_fav_properties', 'reales_get_fav_properties' );

/**
 * Get agent properties
 */
if( !function_exists('reales_get_agent_properties') ): 
    function reales_get_agent_properties() {
        check_ajax_referer('app_map_ajax_nonce', 'security');

        $agent_id = isset($_POST['agent_id']) ? sanitize_text_field($_POST['agent_id']) : '';

        $args = array(
            'post_type' => 'property',
            'post_status' => 'publish'
        );

        $args['meta_query'] = array(
            array(
                'key'     => 'property_agent',
                'value'   => $agent_id,
            )
        );


        $query = new WP_Query($args);

        $props = array();
        $reales_general_settings = get_option('reales_general_settings');
        $reales_amenities_settings = get_option('reales_amenities_settings');
        $amenities_list = array();
        $amenities = isset($reales_amenities_settings['reales_amenities_field']) ? $reales_amenities_settings['reales_amenities_field'] : '';
        $amenities_list = explode(',', $amenities);
        
        while($query->have_posts()) {
            $query->the_post();

            $post_id = get_the_ID();
            $prop = new stdClass();

            $prop->id = $post_id;
            $prop->title = get_the_title();
            $prop->link = get_permalink($post_id);
            $prop->city = get_post_meta($post_id, 'property_city', true);
            $prop->lat = get_post_meta($post_id, 'property_lat', true);
            $prop->lng = get_post_meta($post_id, 'property_lng', true);
            $prop->address = get_post_meta($post_id, 'property_address', true);
            $prop->state = get_post_meta($post_id, 'property_state', true);
            $prop->zip = get_post_meta($post_id, 'property_zip', true);
            $prop->country = get_post_meta($post_id, 'property_country', true);
            $prop->price = get_post_meta($post_id, 'property_price', true);
            $prop->currency = isset($reales_general_settings['reales_currency_symbol_field']) ? $reales_general_settings['reales_currency_symbol_field'] : '';
            $prop->currency_pos = isset($reales_general_settings['reales_currency_symbol_pos_field']) ? $reales_general_settings['reales_currency_symbol_pos_field'] : '';
            $prop->price_label = get_post_meta($post_id, 'property_price_label', true);
            $prop->area = get_post_meta($post_id, 'property_area', true);
            $prop->unit = isset($reales_general_settings['reales_unit_field']) ? $reales_general_settings['reales_unit_field'] : '';
            $prop->bedrooms = get_post_meta($post_id, 'property_bedrooms', true);
            $prop->bathrooms = get_post_meta($post_id, 'property_bathrooms', true);

            $prop->amenities = array();
            if($amenities != '') {
                foreach($amenities_list as $key => $value) {
                    $post_var_name = str_replace(' ', '_', trim($value));
                    $input_name = reales_substr45(sanitize_title($post_var_name));
                    $input_name = sanitize_key($input_name);
                    if (get_post_meta($post_id, $input_name, true) == 1) {
                        array_push($prop->amenities, $value);
                    }
                }
            }

            $prop->agent = get_post_meta($post_id, 'property_agent', true);
            $prop->gallery = get_post_meta($post_id, 'property_gallery', true);
            $prop->category =  wp_get_post_terms($post_id, 'property_category');
            $prop->type =  wp_get_post_terms($post_id, 'property_type_category');

            array_push($props, $prop);
        }

        wp_reset_postdata();

        if(count($props) > 0) {
            echo json_encode(array('getprops'=>true, 'props'=>$props));
            exit();
        } else {
            echo json_encode(array('getprops'=>false));
            exit();
        }

        die();
    }
endif;
add_action( 'wp_ajax_nopriv_reales_get_agent_properties', 'reales_get_agent_properties' );
add_action( 'wp_ajax_reales_get_agent_properties', 'reales_get_agent_properties' );

function reales_notify_agent_on_publish( $ID, $post ) {
    $author = $post->post_author;
    $name = get_the_author_meta( 'display_name', $author );
    $email = get_the_author_meta( 'user_email', $author );
    $title = $post->post_title;
    $permalink = get_permalink( $ID );
    $edit = get_edit_post_link( $ID, '' );

    $to[] = sprintf( '%s <%s>', $name, $email );
    $message = sprintf ( __('Congratulations, %s! Your property "%s" has been published.', 'reales') . "\n\n", $name, $title );
    $message .= sprintf( __('View: %s', 'reales'), $permalink );
    $headers = 'From: noreply  <noreply@'.$_SERVER['HTTP_HOST'].'>' . "\r\n" .
            'Reply-To: noreply@' . $_SERVER['HTTP_HOST'] . "\r\n" .
            'X-Mailer: PHP/' . phpversion();
    wp_mail(
        $to,
        sprintf( __('[%s] Property Published: %s', 'reales'), get_option('blogname'), $title ),
        $message,
        $headers
    );
}
add_action('publish_property', 'reales_notify_agent_on_publish', 10, 2);

?>