<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://morningstardigital.com.au/
 * @since      1.0.0
 *
 * @package    Md_Tacex_Main
 * @subpackage Md_Tacex_Main/admin/partials
 */


$option           = '_'.$term_string.'_checklist_'.$i.$post->ID.$prod_id.$i;
$checklist_option = get_post_meta($prod_id, $option, true);

if (empty($checklist_option)) {
  add_post_meta( $prod_id, $option, 'pending', true );
}

$option_issue   = ($checklist_option === 'issue') ? 'checked="checked"' : '';
$option_pending = ($checklist_option === 'pending') ? 'checked="checked"' : '';
$option_ready   = ($checklist_option === 'ready') ? 'checked="checked"' : '';
$option_default = (empty($checklist_option)) ? 'checked="checked"' : '';

$class_issue    = ($checklist_option === 'issue') ? 'class="selected issue"' : '';
$class_pending  = ($checklist_option === 'pending') ? 'class="selected pending"' : '';
$class_ready    = ($checklist_option === 'ready') ? 'class="selected ready"' : '';