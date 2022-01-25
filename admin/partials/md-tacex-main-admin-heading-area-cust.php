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

$stat_all     = '_customer_collect_checklist_'.$a.$post->ID.$a.'_all';
$stat_display = get_post_meta($post->ID, $stat_all, true);

if (empty($stat_display)) {
  add_post_meta( $post->ID, $stat_all, 'pending', true );
}

?>
  
<div class="checklist_heading_area">
  <div class="ordered_img customer">
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M20.822 18.096c-3.439-.794-6.64-1.49-5.09-4.418 4.72-8.912 1.251-13.678-3.732-13.678-5.082 0-8.464 4.949-3.732 13.678 1.597 2.945-1.725 3.641-5.09 4.418-3.073.71-3.188 2.236-3.178 4.904l.004 1h23.99l.004-.969c.012-2.688-.092-4.222-3.176-4.935z"/></svg>
  </div>
  <div class="checklist_meta_subheadline">
    <h3><strong>Customer's Collection Checklist</strong> - <span><?php echo $post->ID ?></span></h3>
    <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Cum quisquam tenetur itaque cumque consequatur recusandae quidem voluptate ab unde, eius vitae odit? Laborum, assumenda quo nisi distinctio et impedit architecto? <a href="#">#link</a></p>
    
  </div>
  <div class="checklist_prod_stat">
    <p><em>status:</em> <span data-meta-stat="<?php echo $stat_all ?>"><?php echo (!empty($stat_display)) ? $stat_display : 'pending' ?></span></p>
    <a href="#" class="review_btn" id="review_btn_<?php echo $a ?>">review checklist</a>
    <a href="#" class="expandall_btn" id="expandall_btn_<?php echo $a ?>">expand all checklist</a>
  </div>
</div>