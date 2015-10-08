<?php
/**
 * @package WordPress
 * @subpackage Reales
 */

if($post) {
    $page_image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' );
} else {
    $page_image = '';
} ?>
<div id="page-hero-container">
    <div class="page-hero" style="background-image:url(<?php echo esc_url($page_image[0]); ?>)"></div>
    <div class="slideshowShadow"></div>