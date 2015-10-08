<?php
/**
 * @package WordPress
 * @subpackage Reales
 */

if( !function_exists('reales_search_properties') ): 
    function reales_search_properties() {
        $search_country = isset($_GET['search_country']) ? sanitize_text_field($_GET['search_country']) : '';
        $search_state = isset($_GET['search_state']) ? sanitize_text_field($_GET['search_state']) : '';
        $search_city = isset($_GET['search_city']) ? sanitize_text_field($_GET['search_city']) : '';
        $search_category = isset($_GET['search_category']) ? sanitize_text_field($_GET['search_category']) : '0';
        $search_type = isset($_GET['search_type']) ? sanitize_text_field($_GET['search_type']) : '0';
        $search_min_price = isset($_GET['search_min_price']) ? sanitize_text_field($_GET['search_min_price']) : '';
        $search_max_price = isset($_GET['search_max_price']) ? sanitize_text_field($_GET['search_max_price']) : '';
        $search_bedrooms = isset($_GET['search_bedrooms']) ? sanitize_text_field($_GET['search_bedrooms']) : '';
        $search_bathrooms = isset($_GET['search_bathrooms']) ? sanitize_text_field($_GET['search_bathrooms']) : '';
        $search_neighborhood = isset($_GET['search_neighborhood']) ? sanitize_text_field($_GET['search_neighborhood']) : '';
        $search_min_area = isset($_GET['search_min_area']) ? sanitize_text_field($_GET['search_min_area']) : '';
        $search_max_area = isset($_GET['search_max_area']) ? sanitize_text_field($_GET['search_max_area']) : '';
        $reales_appearance_settings = get_option('reales_appearance_settings');
        $posts_per_page_setting = isset($reales_appearance_settings['reales_properties_per_page_field']) ? $reales_appearance_settings['reales_properties_per_page_field'] : '';
        $posts_per_page = $posts_per_page_setting != '' ? $posts_per_page_setting : 10;
        $sort = isset($_GET['sort']) ? sanitize_text_field($_GET['sort']) : 'newest';

        global $paged;

        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
        $args = array(
            'posts_per_page' => $posts_per_page,
            'paged' => $paged,
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
                'type' => 'NUMERIC'
            ));
        }

        if($search_bathrooms != '' && $search_bathrooms != 0) {
            array_push($args['meta_query'], array(
                'key'     => 'property_bathrooms',
                'value'   => $search_bathrooms,
                'compare' => '>=',
                'type' => 'NUMERIC'
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

        $reales_amenities_settings = get_option('reales_amenities_settings');
        $amenities_list = array();
        $amenities = $reales_amenities_settings['reales_amenities_field'];
        $amenities_list = explode(',', $amenities);

        if($amenities != '') {
            foreach($amenities_list as $key => $value) {
                $post_var_name = str_replace(' ', '_', trim($value));

                $input_name = reales_substr45(sanitize_title($post_var_name));
                $input_name = sanitize_key($input_name);
                if (isset($_GET[$input_name]) && esc_html($_GET[$input_name]) == 1) {
                    array_push($args['meta_query'], array(
                        'key'     => $input_name,
                        'value'   => 1
                    ));
                }
            }
        }

        $query = new WP_Query($args);
        wp_reset_postdata();
        return $query;
    }
endif;

if( !function_exists('reales_get_search_link') ): 
    function reales_get_search_link() {
        $pages = get_pages(array(
            'meta_key' => '_wp_page_template',
            'meta_value' => 'property-search-results.php'
        ));

        if($pages) {
            $search_submit = get_permalink($pages[0]->ID);
        } else {
            $search_submit = '';
        }

        return $search_submit;
    }
endif;

?>