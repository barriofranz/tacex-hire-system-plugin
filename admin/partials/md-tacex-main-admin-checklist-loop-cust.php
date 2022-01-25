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


include plugin_dir_path( __FILE__ ) . 'md-tacex-main-admin-checklist-options-cust.php'; ?>

  <ul>
    <li <?php echo $class_issue ?>><label>
      <input type="radio" value="issue" data-meta-option="<?php echo $option ?>" name="<?php echo '_'.$option ?>" <?php echo $option_issue ?>>
      <span>issue</span></label>
    </li>
    <li <?php echo $class_pending ?>><label>
      <input type="radio" value="pending" data-meta-option="<?php echo $option ?>" name="<?php echo '_'.$option ?>" <?php echo $option_pending ?> <?php echo $option_default ?>>
      <span>pending</span></label>
    </li>
    <li <?php echo $class_ready ?>><label>
      <input type="radio" value="ready" data-meta-option="<?php echo $option ?>" name="<?php echo '_'.$option ?>" <?php echo $option_ready ?>>
      <span>ready</span></label>
    </li>
    <span class="shadow"></span>
  </ul>
</div>

<?php 
  include plugin_dir_path( __FILE__ ) . 'md-tacex-main-admin-upload-loop-cust.php'; 