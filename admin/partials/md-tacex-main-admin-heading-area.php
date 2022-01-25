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


$stat_all     = '_'.$term_string.'_checklist_'.$a.$prod_id.$post->ID.$a.'_all'; //_attachments_checklist_21562209_all
$stat_display = get_post_meta($prod_id, $stat_all, true);

if (empty($stat_display)) {
  add_post_meta( $prod_id, $stat_all, 'pending', true );
}

?>

<div class="checklist_heading_area">
  <div class="ordered_img prod_item"><?php echo $prod_img ? $prod_img : '<p>No image found</p>'; ?></div>
  <div class="checklist_meta_subheadline">
    <h3><strong><?php echo $prod_name ?></strong> <em title="Product Category">(<?php echo $term_string ?></em> - <span><?php echo $post->ID ?></span>)</h3>
    <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Cum quisquam tenetur itaque cumque consequatur recusandae quidem voluptate ab unde, eius vitae odit? Laborum, assumenda quo nisi distinctio et impedit architecto? <a href="#">#link</a></p>
    <span><?php echo $prod_id ?></span>
  </div>
  <div class="checklist_prod_stat">
    <p><em>status:</em> <span data-meta-stat="<?php echo $stat_all ?>"><?php echo (!empty($stat_display)) ? $stat_display : 'pending' ?></span></p>
    <a href="#" class="review_btn" id="review_btn_<?php echo $a ?>">review checklist</a>
    <a href="#" class="expandall_btn" id="expandall_btn_<?php echo $a ?>">expand all checklist</a>
  </div>
</div>