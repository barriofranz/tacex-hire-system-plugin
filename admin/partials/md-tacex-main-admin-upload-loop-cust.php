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


$issue  = ($checklist_option === 'issue'); 
$img    = get_post_meta($post->ID, '_checklist_uploaded_'.$i.$post->ID.$i, true );
$note   = get_post_meta($post->ID, '_checklist_note_'.$i.$post->ID.$i, true ); ?>

<div class="s_issue_info comment_upload" style="display: <?php echo $issue ? 'flex' : 'none' ?>;">
  <div class="comment_area">
    <textarea class="checklist_note" id="checklist_note_<?php echo $i.$post->ID.$i ?>" data-meta-note="_checklist_note_<?php echo $i.$post->ID.$i ?>" placeholder="Add your issue notes here..."><?php echo (!empty($note)) ? $note : '' ?></textarea>
  </div>

  <div class="upload_area" data-meta-id="<?php echo $post->ID ?>" data-meta-upload="<?php echo '_checklist_uploaded_'.$i.$post->ID.$i ?>">
    <?php 
    if (!empty($img)) { ?>
      <div class="checklist_uploaded_img">
        <canvas class="file_thumb" height="84" width="84" style="object-fit:contain;"></canvas>
        <div class="modal" style="opacity:0;visibility:hidden;pointer-events:none;">
          <div class="modal_content">
            <span class="close">&times;</span>
            <img class="modal_img mod_img"src="<?php echo THSP_UPLOAD_URL . $img ?>" alt="<?php echo $img ?>" />
            <div class="caption"><?php echo $img ?></div>
          </div>
        </div>
        <span class="remove_btn" title="Replace image">
          <i data-feather="x-circle"></i>
        </span>
      </div>
    <?php	
    } ?>

    <div class="upload_item" style="<?php echo (!empty($img)) ? 'display:none;' : '' ?>">
      <div class="upload_img">
        <label for="fileupload_<?php echo $i.$post->ID.$i ?>"><i data-feather="image"></i> upload photo</label>
        <input type="file" id="fileupload_<?php echo $i.$post->ID.$i ?>" accept="image/*" capture />
      </div>
      <div class="file_preview"></div>
    </div>
  </div>
</div>