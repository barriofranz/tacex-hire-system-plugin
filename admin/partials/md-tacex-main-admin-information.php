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

?>

<div class="s_results">
  <?php
  global $wpdb;
  $thsp_table = $wpdb->prefix . 'thsp_infos';
  $results = $wpdb->get_results("SELECT * FROM $thsp_table ORDER BY order_num", ARRAY_A); ?>

  <div id="info_form">
    <div class="col s6 grey lighten-5">
      <div class="input-field">
        <input name="order_num" type="number" class="validate">
        <label for="order_num">Order Number</label>
      </div>
      <div class="input-field">
        <input name="title" type="text" class="validate">
        <label for="title">Title</label>
      </div>
      <div class="input-field">
        <textarea name="description" class="materialize-textarea validate"></textarea>
        <label for="description">Description</label>
      </div>
      <input type="hidden" name="action" value="checklist_info_ajax">
      <div class="center-align red-text" id="form_error"></div>
      <div class="center-align">
        <a class="btn brand z-depth-0" id="save_info" href="#">save</a>
      </div>
    </div>
  </div>

  <div id="r_info_articles" data-action="delete_info_ajax">
  <?php if (count($results) > 0) : ?>
    <?php foreach ($results as $result) : ?>
      <div class="card info_card z-depth-0" data-order="<?php echo $result['order_num'] ?>">
        <div class="card-content">
          <span class="card-title"><?php echo htmlspecialchars($result['title']) ?></span>
          <p style="white-space: pre-line"><?php echo htmlspecialchars($result['descriptions']) ?></p>
        </div>
        <div class="card-action right-align">
          <a href="#" class="btn brand z-depth-0 id_to_delete" data-value="<?php echo htmlspecialchars($result['id']) ?>"><i data-feather="trash-2">x</i></a>
        </div>
      </div>
    <?php endforeach; ?>
  <?php else : ?>
    <h4 class="center-align no_articles">No available information article. Please add your article.</h4>
  <?php endif; ?>
  </div>
</div>
<script>
  const idtoDelete = document.querySelectorAll('.id_to_delete') || []
  let stat = false
</script>