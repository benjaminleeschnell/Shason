<?php
/*
Template Name: Property Search Results
*/

/**
 * @package WordPress
 * @subpackage Reales
 */

global $post;
get_header();
$reales_appearance_settings = get_option('reales_appearance_settings','');
$show_bc = isset($reales_appearance_settings['reales_breadcrumbs_field']) ? $reales_appearance_settings['reales_breadcrumbs_field'] : '';
$show_type_label = isset($reales_appearance_settings['reales_type_label_field']) ? $reales_appearance_settings['reales_type_label_field'] : '';
$sort = isset($_GET['sort']) ? sanitize_text_field($_GET['sort']) : 'newest';
$searched_posts = reales_search_properties();
$total_p = $searched_posts->found_posts;
$users = get_users();
?>

<div id="wrapper">

    <div id="mapView">
        <div class="mapPlaceholder"><span class="fa fa-spin fa-spinner"></span> <?php esc_html_e('Loading map...', 'reales'); ?></div>
    </div>
    <?php wp_nonce_field('app_map_ajax_nonce', 'securityAppMap', true); ?>

    <div id="content">
        <?php get_template_part('templates/filter_properties'); ?>
        <div class="resultsList">
            <?php if($show_bc != '') {
                reales_breadcrumbs();
            } ?>
            <h1 class="pull-left"><?php echo esc_html($post->post_title); ?></h1>
            <div class="pull-right sort">

                <div class="form-group">
                    <?php esc_html_e('Sort by:', 'reales'); ?>&nbsp;&nbsp;
                    <a href="javascript:void(0);" data-toggle="dropdown" class="btn btn-white dropdown-toggle">
                        <span class="dropdown-label"><?php esc_html_e('Newest', 'reales'); ?></span>&nbsp;&nbsp;&nbsp;<span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu dropdown-select sorter">
                        <li class="active"><input type="radio" name="sort" value="newest" <?php if(!$sort || $sort == '' || $sort == 'newest') { echo 'checked="checked"'; } ?> ><a href="javascript:void(0);"><?php esc_html_e('Newest', 'reales'); ?></a></li>
                        <li><input type="radio" name="sort" value="price_lo" <?php if($sort && $sort != '' && $sort == 'price_lo') { echo 'checked="checked"'; } ?> ><a href="javascript:void(0);"><?php esc_html_e('Price (Lo-Hi)', 'reales'); ?></a></li>
                        <li><input type="radio" name="sort" value="price_hi" <?php if($sort && $sort != '' && $sort == 'price_hi') { echo 'checked="checked"'; } ?> ><a href="javascript:void(0);"><?php esc_html_e('Price (Hi-Lo)', 'reales'); ?></a></li>
                        <li><input type="radio" name="sort" value="bedrooms" <?php if($sort && $sort != '' && $sort == 'bedrooms') { echo 'checked="checked"'; } ?> ><a href="javascript:void(0);"><?php esc_html_e('Bedrooms', 'reales'); ?></a></li>
                        <li><input type="radio" name="sort" value="bathrooms" <?php if($sort && $sort != '' && $sort == 'bathrooms') { echo 'checked="checked"'; } ?> ><a href="javascript:void(0);"><?php esc_html_e('Bathrooms', 'reales'); ?></a></li>
                        <li><input type="radio" name="sort" value="area" <?php if($sort && $sort != '' && $sort == 'area') { echo 'checked="checked"'; } ?> ><a href="javascript:void(0);"><?php esc_html_e('Area', 'reales'); ?></a></li>
                    </ul>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="row">
            <?php
            while ( $searched_posts->have_posts() ) {
                $searched_posts->the_post();

                $prop_id = get_the_ID();
                $gallery = get_post_meta($prop_id, 'property_gallery', true);
                $images = explode("~~~", $gallery);
                $price = get_post_meta($prop_id, 'property_price', true);
                $reales_general_settings = get_option('reales_general_settings');
                $currency = isset($reales_general_settings['reales_currency_symbol_field']) ? $reales_general_settings['reales_currency_symbol_field'] : '';
                $currency_pos = isset($reales_general_settings['reales_currency_symbol_pos_field']) ? $reales_general_settings['reales_currency_symbol_pos_field'] : '';
                $price_label = get_post_meta($prop_id, 'property_price_label', true);
                setlocale(LC_MONETARY, 'en_US');
                $address = get_post_meta($prop_id, 'property_address', true);
                $city = get_post_meta($prop_id, 'property_city', true);
                $state = get_post_meta($prop_id, 'property_state', true);
                $neighborhood = get_post_meta($prop_id, 'property_neighborhood', true);
                $zip = get_post_meta($prop_id, 'property_zip', true);
                $country = get_post_meta($prop_id, 'property_country', true);
                $type =  wp_get_post_terms($prop_id, 'property_type_category');
                $bedrooms = get_post_meta($prop_id, 'property_bedrooms', true);
                $bathrooms = get_post_meta($prop_id, 'property_bathrooms', true);
                $area = get_post_meta($prop_id, 'property_area', true);
                $unit = isset($reales_general_settings['reales_unit_field']) ? $reales_general_settings['reales_unit_field'] : '';
            ?>
                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                    <a href="<?php echo esc_url(get_permalink($prop_id)); ?>" class="card" id="card-<?php echo esc_attr($prop_id); ?>">
                        <div class="figure">
                        <div class="img" style="background-image:url(<?php echo esc_url($images[1]); ?>);"></div>
                            <div class="figCaption">
                                <?php if($currency_pos == 'before') { ?>
                                <div><?php echo esc_html($currency) . money_format('%!.0i', esc_html($price)) . esc_html($price_label); ?></div>
                                <?php } else { ?>
                                <div><?php echo money_format('%!.0i', esc_html($price)) . esc_html($currency) . esc_html($price_label); ?></div>
                                <?php } ?>
                                <span><span class="icon-eye"></span> <?php echo esc_html(reales_get_post_views($prop_id, '')); ?></span>
                                <?php
                                $favs = 0;
                                foreach ($users as $user) {
                                    $user_fav = get_user_meta($user->data->ID, 'property_fav', true);
                                    if(is_array($user_fav) && in_array($prop_id, $user_fav)) {
                                        $favs = $favs + 1;
                                    }
                                }
                                ?>
                                <span><span class="icon-heart"></span> <?php echo esc_html($favs); ?></span>
                                <span><span class="icon-bubble"></span> <?php comments_number('0', '1', '%'); ?></span>
                            </div>
                            <div class="figView"><span class="icon-eye"></span></div>
                            <?php if($type) { ?>
                                <div class="figType"><?php echo esc_html($type[0]->name); ?></div>
                            <?php } ?>
                        </div>
                        <h2><?php the_title(); ?></h2>
                        <div class="cardAddress">
                            <?php 
                            if($address != '') {
                                echo esc_html($address) . ', ';
                            }
                            if($neighborhood !== '') {
                                echo esc_html($neighborhood) . ', ';
                            }
                            if($city !== '') {
                                echo esc_html($city);
                            }
                            if($address != '' || $neighborhood !== '' || $city !== '') {
                                echo '<br />';
                            }
                            if($state !== '') {
                                echo esc_html($state) . ', ';
                            }
                            if($zip !== '') {
                                echo esc_html($zip) . ', ';
                            }
                            echo esc_html($country);
                            ?>
                        </div>
                        <ul class="cardFeat">
                            <?php if($bedrooms !== '') { ?>
                                <li><span class="fa fa-moon-o"></span> <?php echo esc_html($bedrooms); ?></li>
                            <?php } ?>
                            <?php if($bathrooms !== '') { ?>
                                <li><span class="icon-drop"></span> <?php echo esc_html($bathrooms); ?></li>
                            <?php } ?>
                            <?php if($area !== '') { ?>
                                <li><span class="icon-frame"></span> <?php echo esc_html($area) . ' ' . esc_html($unit); ?></li>
                            <?php } ?>
                        </ul>
                        <div class="clearfix"></div>
                    </a>
                </div>
            <?php } ?>
            </div>
            <div class="pull-left">
                <?php reales_pagination($searched_posts->max_num_pages); ?>
            </div>
            <div class="pull-right search_prop_calc">
                <?php
                $reales_appearance_settings = get_option('reales_appearance_settings');
                $per_p_field = isset($reales_appearance_settings['reales_properties_per_page_field']) ? $reales_appearance_settings['reales_properties_per_page_field'] : '';
                $per_p = $per_p_field != '' ? intval($per_p_field) : 10;
                $page_no = (get_query_var('paged')) ? get_query_var('paged') : 1;

                $from_p = ($page_no == 1) ? 1 : $per_p * ($page_no - 1) + 1;
                $to_p = ($total_p - ($page_no - 1) * $per_p > $per_p) ? $per_p * $page_no : $total_p;
                echo esc_html($from_p) . ' - ' . esc_html($to_p) . __(' of ', 'reales') . esc_html($total_p) . __(' Properties found', 'reales');
                ?>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>

</div>

<?php
get_template_part('templates/app_footer');
?>