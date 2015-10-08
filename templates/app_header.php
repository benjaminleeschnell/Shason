<?php
/**
 * @package WordPress
 * @subpackage Reales
 */

$reales_appearance_settings = get_option('reales_appearance_settings','');
$reales_general_settings = get_option('reales_general_settings','');
$leftside_menu = isset($reales_appearance_settings['reales_leftside_menu_field']) ? $reales_appearance_settings['reales_leftside_menu_field'] : ''; ?>
<div id="header">
    <div class="logo">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>">
            <?php
            $logo = isset($reales_general_settings['reales_logo_field']) ? $reales_general_settings['reales_logo_field'] : '';
            $app_logo = isset($reales_general_settings['reales_app_logo_field']) ? $reales_general_settings['reales_app_logo_field'] : '';
            if($logo != '' && $app_logo != '') {
                print '<img src="' . esc_url($app_logo) . '" alt="' . esc_attr(get_bloginfo('name')) . '" class="logo-min" />';
                print '<img src="' . esc_url($logo) . '" alt="' . esc_attr(get_bloginfo('name')) . '" class="logo-full" />';
            } else {
                print '<span class="fa fa-home marker"></span><span class="logoText">' . esc_html(get_bloginfo('name')) . '</span>';
            }
            ?>
        </a>
    </div>
    <?php if($leftside_menu) { ?>
    <a href="javascript:void(0);" class="navHandler"><span class="fa fa-ellipsis-v"></span></a>
    <?php } ?>
    <a href="javascript:void(0);" class="mapHandler"><span class="icon-map"></span></a>
    <?php 
        $user_menu = isset($reales_appearance_settings['reales_user_menu_field']) ? $reales_appearance_settings['reales_user_menu_field'] : false;
        if($user_menu) {
            get_template_part('templates/user_menu');
        }
    ?>
    <a href="javascript:void(0);" class="top-navHandler visible-xs"><span class="fa fa-bars"></span></a>
    <div class="top-nav">
        <?php
        wp_nav_menu( array( 'theme_location' => 'primary' ) );
        ?>
    </div>
    <div class="clearfix"></div>
</div>
<?php 
if($leftside_menu) {
    get_template_part('templates/leftside_menu');
}
?>