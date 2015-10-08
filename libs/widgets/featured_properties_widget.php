<?php
/**
 * @package WordPress
 * @subpackage Reales
 */

class Featured_Properties_Widget extends WP_Widget {
    function Featured_Properties_Widget() {
        $widget_ops = array('classname' => 'featured_properties_sidebar', 'description' => 'Featured listed properties.');
        $control_ops = array('id_base' => 'featured_properties_widget');
        $this->WP_Widget('featured_properties_widget', 'Reales WP Featured Properties', $widget_ops, $control_ops);
    }

    function form($instance) {
        $defaults = array(
            'title' => '',
            'limit' => ''
        );
        $instance = wp_parse_args((array) $instance, $defaults);
        $display = '
            <p>
                <label for="' . esc_attr($this->get_field_id('title')) . '">' . __('Title', 'reales') . ':</label>
                <input type="text" class="widefat" id="' . esc_attr($this->get_field_id('title')) . '" name="' . esc_attr($this->get_field_name('title')) . '" value="' . esc_attr($instance['title']) . '" />
            </p>
            <p>
                <label for="' . esc_attr($this->get_field_id('limit')) . '">' . __('Number of properties to show', 'reales') . ':</label>
                <input type="text" size="3" id="' . esc_attr($this->get_field_id('limit')) . '" name="' . esc_attr($this->get_field_name('limit')) . '" value="' . esc_attr($instance['limit']) . '" />
            </p>
        ';

        print $display;
    }


    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = sanitize_text_field($new_instance['title']);
        $instance['limit'] = sanitize_text_field($new_instance['limit']);

        if(function_exists('icl_register_string')) {
            icl_register_string('reales_featured_properties_widget', 'featured_properties_widget_title', sanitize_text_field($new_instance['title']));
            icl_register_string('reales_featured_properties_widget', 'featured_properties_widget_limit', sanitize_text_field($new_instance['limit']));
        }

        return $instance;
    }

    function widget($args, $instance) {
        extract($args);
        $display = '';
        $title = apply_filters('widget_title', $instance['title']);

        print $before_widget;

        if($title) {
            print $before_title . esc_html($title) . $after_title;
        }

        if($instance['limit'] && $instance['limit'] != '') {
            $limit = $instance['limit'];
        } else {
            $limit = 4;
        }

        $args = array(
            'posts_per_page'   => $instance['limit'],
            'post_type'        => 'property',
            'orderby'          => 'post_date',
            'order'            => 'DESC',
            'meta_key'         => 'property_featured',
            'meta_value'       => '1',
            'post_status'      => 'publish' );
        $posts = get_posts($args);

        $display .= '<div class="propsWidget"><ul class="propList">';
        foreach($posts as $post) : setup_postdata($post);
            $gallery = get_post_meta($post->ID, 'property_gallery', true);
            $images = explode("~~~", $gallery);
            $price = get_post_meta($post->ID, 'property_price', true);
            $reales_general_settings = get_option('reales_general_settings');
            $currency = isset($reales_general_settings['reales_currency_symbol_field']) ? $reales_general_settings['reales_currency_symbol_field'] : '';
            $currency_pos = isset($reales_general_settings['reales_currency_symbol_pos_field']) ? $reales_general_settings['reales_currency_symbol_pos_field'] : '';
            $price_label = get_post_meta($post->ID, 'property_price_label', true);
            setlocale(LC_MONETARY, 'en_US');
            $address = get_post_meta($post->ID, 'property_address', true);
            $city = get_post_meta($post->ID, 'property_city', true);
            $zip = get_post_meta($post->ID, 'property_zip', true);
            $country = get_post_meta($post->ID, 'property_country', true);
            $type =  wp_get_post_terms($post->ID, 'property_type_category');

            $display .= '<li>';
            $display .= '<a href="' . esc_url(get_permalink($post->ID)) . '">';
            $display .= '<div class="image"><img src="' . esc_url($images[1]) . '" alt="' . esc_attr($title) . '" /></div>';
            $display .= '<div class="info text-nowrap">';
            $display .= '<div class="name">' . esc_html($post->post_title) . '</div>';
            $display .= '<div class="address">';
            if($address != '') {
                $display .= esc_html($address) . ', ';
            }
            if($city != '') {
                $display .= esc_html($city) . ', ';
            }
            if($zip != '') {
                $display .= esc_html($zip) . ', ';
            }
            if($country != '') {
                $display .= esc_html($country);
            }
            $display .= '</div>';
            if($type) {
                $type_name = $type[0]->name;
            } else {
                $type_name = '';
            }
            if($currency_pos == 'before') {
                $display .= '<div class="price">' . esc_html($currency) . money_format('%!.0i', esc_html($price)) . esc_html($price_label) . ' <span class="badge">' . esc_html($type_name) . '</span></div>';
            } else {
                $display .= '<div class="price">' . money_format('%!.0i', esc_html($price)) . esc_html($currency) . esc_html($price_label) . ' <span class="badge">' . esc_html($type_name) . '</span></div>';
            }
            $display .= '</div>';
            $display .= '<div class="clearfix"></div>';
            $display .= '</a>';
            $display .= '</li>';
        endforeach;

        $display .= '</ul></div>';

        wp_reset_postdata();
        wp_reset_query();
        print $display;
        print $after_widget;
    }

}

?>