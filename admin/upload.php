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
 * @subpackage Md_Tacex_Main/admin/upload
 */

$upload_dir = '../../../uploads';
$upl_path = $upload_dir.'/thsp_uploads'.'/';

$filename = $_FILES['file']['name'];
$location = $upl_path . $filename;
move_uploaded_file($_FILES['file']['tmp_name'], $location);
