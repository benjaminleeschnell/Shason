<?php
/**
 * @package WordPress
 * @subpackage Reales
 */

$reales_prop_fields_settings = get_option('reales_prop_fields_settings');
$p_plans_r = isset($reales_prop_fields_settings['reales_p_plans_r_field']) ? $reales_prop_fields_settings['reales_p_plans_r_field'] : '';
?>

<div class="submit_container">
    <div class="submit_container_header"><?php esc_html_e('Floor Plans', 'reales');?></div>
    <div id="upload-container-plans">
        <div id="aaiu-upload-container-plans">
            <div id="aaiu-upload-imagelist-plans"></div>
            <div id="imagelist-plans"></div>
            <div class="clearfix"></div>
            <a href="javascript:void(0);" id="aaiu-uploader-plans" class="btn btn-o btn-default"><span class="glyphicon glyphicon-folder-open"></span>&nbsp;&nbsp;&nbsp;<?php esc_html_e('Browse Plans Images', 'reales');?></a>
            <input type="hidden" name="new_plans" id="new_plans">
        </div>
    </div>
</div>