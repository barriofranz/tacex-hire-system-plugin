<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://morningstardigital.com.au/
 * @since      1.0.0
 *
 * @package    Md_Tacex_Main
 * @subpackage Md_Tacex_Main/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Md_Tacex_Main
 * @subpackage Md_Tacex_Main/admin
 * @author     Morningstar Digital <https://morningstardigital.com.au/>
 */
class Md_Tacex_Main_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Md_Tacex_Main_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Md_Tacex_Main_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/md-tacex-main-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Md_Tacex_Main_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Md_Tacex_Main_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/md-tacex-main-admin.js', array( 'jquery' ), $this->version, false );

	}

	// pages to display
	public $admin_pattern = "/wp-admin\/options-general\.php$|wp-admin\/post.php$/i";
	public $setting_pattern = "/wp-admin\/options-general\.php$/i";
	public $edit_pattern = "/wp-admin\/post.php$/i";
	public $query_pattern = "/page=thsplugin_page|post=2209&action=edit/i";

	public function enqueue_thsp_styles() {
		if (preg_match($this->setting_pattern, $_SERVER['PHP_SELF']) && preg_match($this->query_pattern, $_SERVER['QUERY_STRING'])) {
			wp_enqueue_style( 'thsp-materialize-style', 'https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css' );
		}

		if (preg_match($this->admin_pattern, $_SERVER['PHP_SELF']) && preg_match($this->query_pattern, $_SERVER['QUERY_STRING'])) {
			wp_enqueue_style( 'thsp-admin-checklist-style', plugin_dir_url( __FILE__ ) . 'css/md-tacex-main-admin-checklist.css', array(), $this->version, 'all' );
			wp_enqueue_style( 'thsp-admin-settings-style', plugin_dir_url( __FILE__ ) . 'css/md-tacex-main-admin-settings.css', array(), $this->version, 'all' );
		}
	}

	public function enqueue_thsp_scripts() {
		// if (preg_match($this->admin_pattern, $_SERVER['PHP_SELF']) && preg_match($this->query_pattern, $_SERVER['QUERY_STRING'])) {
			wp_enqueue_script( 'thsp-feather-icons-script', 'https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.9.0/feather.min.js');
		
			wp_enqueue_script( 'thsp-admin-order-script', plugin_dir_url( __FILE__ ) . 'js/md-tacex-main-admin-order.js', array( 'jquery' ), $this->version, true );
			wp_localize_script( 'thsp-admin-order-script', 'thspadminorder', array(
				'ajaxurlA' => admin_url( 'admin-ajax.php' ),
				'ajaxnonceA' => wp_create_nonce( 'ajax_post_validation' ),
			));
		// }

		if (preg_match($this->setting_pattern, $_SERVER['PHP_SELF']) && preg_match($this->query_pattern, $_SERVER['QUERY_STRING'])) {
			wp_enqueue_script( 'thsp-materialize-script', 'https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js');

			wp_enqueue_script( 'thsp-admin-settings-script', plugin_dir_url( __FILE__ ) . 'js/md-tacex-main-admin-settings.js', array( 'jquery' ), $this->version, true );
			wp_localize_script( 'thsp-admin-settings-script', 'thspadminsettings', array(
				'ajaxurlB' => admin_url( 'admin-ajax.php' ),
				'ajaxnonceB' => wp_create_nonce( 'ajax_post_validation' ),
			));
		}
	}


	public function thsp_order_metabox_checklist() {
		add_action( 'wp_ajax_my_ajax_shared_post', 'my_ajax_shared_post' );
		add_action( 'wp_ajax_checklist_info_ajax', 'checklist_info_ajax' );
		add_action( 'wp_ajax_delete_info_ajax', 'delete_info_ajax' );
		// add_action( 'wp_ajax_nopriv_my_ajax_shared_post', 'my_ajax_shared_post' );
		add_action( 'add_meta_boxes', 'thsp_add_checklist_meta_box' );

		// setting checklist values via ajax 
		function my_ajax_shared_post() {
			if ($_SERVER["REQUEST_METHOD"] === "POST") {
				$order_id = $_POST['order_id'];
				$rad_ids = json_decode(stripslashes($_POST['radids']));
				$rad_metas = json_decode(stripslashes($_POST['radmetas']));
				$rad_values = json_decode(stripslashes($_POST['radvalues']));
				$upl_ids = json_decode(stripslashes($_POST['uplids']));
				$upl_metas = json_decode(stripslashes($_POST['uplmetas']));
				$upl_values = json_decode(stripslashes($_POST['uplvalues']));
				$stat_ids = json_decode(stripslashes($_POST['statids']));
				$stat_metas = json_decode(stripslashes($_POST['statmetas']));
				$stat_values = json_decode(stripslashes($_POST['statvalues']));
				$note_ids = json_decode(stripslashes($_POST['noteids']));
				$note_metas = json_decode(stripslashes($_POST['notemetas']));
				$note_values = json_decode(stripslashes($_POST['notevalues']));
				// $order = wc_get_order( $order_id );
					
				if (count($rad_ids) > 0 && count($rad_metas) > 0 && count($rad_values) > 0) {
					for ($i = 0; $i < count($rad_ids); $i++) {
						$old_meta_val = get_post_meta($rad_ids[$i], $rad_metas[$i], true);

						if (empty($old_meta_val)) {
							add_post_meta( $rad_ids[$i], $rad_metas[$i], $rad_values[$i] );
							// echo "added: $rad_ids[$i] --> $rad_metas[$i] $rad_values[$i] \r\n";
						} 
						if ($old_meta_val !== $rad_values[$i]) {
							update_post_meta( $rad_ids[$i], $rad_metas[$i], $rad_values[$i], $old_meta_val );
							// echo "update: $rad_ids[$i] --> $rad_metas[$i] $rad_values[$i] $old_meta_val \r\n";
						}
					}
				} else {
					// echo 'No product id found';
				}

				if (count($upl_ids) > 0 && count($upl_metas) > 0 && count($upl_values) > 0) {
					for ($i = 0; $i < count($upl_ids); $i++) {
						$old_meta_val = get_post_meta($upl_ids[$i], $upl_metas[$i], true);

						if (empty($old_meta_val)) {
							add_post_meta( $upl_ids[$i], $upl_metas[$i], $upl_values[$i] );
							// echo "added: $upl_ids[$i] --> $upl_metas[$i] $upl_values[$i] \r\n";
						} 
						if ($old_meta_val !== $upl_values[$i]) {
							update_post_meta($upl_ids[$i], $upl_metas[$i], $upl_values[$i], $old_meta_val);
							// echo "updated: $upl_ids[$i] --> $upl_metas[$i] $upl_values[$i] $old_meta_val \r\n";
						}
					}
				} else {
					// echo 'No upload image found';
				}

				if (count($stat_ids) > 0 && count($stat_metas) > 0 && count($stat_values) > 0) {
					for ($i = 0; $i < count($stat_ids); $i++) {
						$old_meta_val = get_post_meta($stat_ids[$i], $stat_metas[$i], true);

						if (empty($old_meta_val)) {
							add_post_meta( $stat_ids[$i], $stat_metas[$i], $stat_values[$i] );
							// echo "added: $stat_ids[$i] --> $stat_metas[$i] $stat_values[$i] \r\n";
						} 
						if ($old_meta_val !== $stat_values[$i]) {
							update_post_meta( $stat_ids[$i], $stat_metas[$i], $stat_values[$i], $old_meta_val );
							// echo "updated: $stat_ids[$i] --> $stat_metas[$i] $stat_values[$i] $old_meta_val \r\n";
						}
					}
				} else {
					// echo 'No status id found';
				}

				if (count($note_ids) > 0 && count($note_metas) > 0 && count($note_values) > 0) {
					for ($i = 0; $i < count($note_ids); $i++) {
						$old_meta_val = get_post_meta($note_ids[$i], $note_metas[$i], true);

						if (empty($old_meta_val)) {
							add_post_meta( $note_ids[$i], $note_metas[$i], $note_values[$i] );
							// echo "added: $note_ids[$i] --> $note_metas[$i] $note_values[$i] \r\n";
						}
						if ($old_meta_val !== $note_values[$i]) {
							update_post_meta( $note_ids[$i], $note_metas[$i], $note_values[$i], $old_meta_val );
							// echo "updated: $note_ids[$i] --> $note_metas[$i] $note_values[$i] $old_meta_val \r\n";
						} 
					}
				} else {
					// echo 'No note id found';
				}
				wp_send_json_success($_POST);
			}
		}

		// add info AJAX
		function checklist_info_ajax() {
			global $wpdb;
			$title = $_POST['title'];
			$order_num = $_POST['order_num'];
			$description = $_POST['description'];

			if (!empty($title) && !empty($description) && $_SERVER["REQUEST_METHOD"] === "POST") {
				$thsp_table = $wpdb->prefix . 'thsp_infos';
				$sql = "INSERT INTO $thsp_table (title, descriptions, order_num) VALUES ('$title', '$description', '$order_num')";
				$wpdb->query($wpdb->prepare($sql));
				
				$results = $wpdb->get_results( "SELECT * FROM $thsp_table ORDER BY id DESC", ARRAY_A );
				wp_send_json_success(array('response' => $_POST, 'data' => $results[0]));
			}
		}


		// delete info AJAX 
		function delete_info_ajax() {
			global $wpdb;
			$id = $_POST['id'];

			if (!empty($id) && $_SERVER["REQUEST_METHOD"] === "POST") {
				$thsp_table = $wpdb->prefix . 'thsp_infos';
				$sql = "DELETE FROM $thsp_table WHERE id = $id";
				$wpdb->query($wpdb->prepare($sql));

				wp_send_json_success($_POST);
			}
		}


		include plugin_dir_path( __FILE__ ) . 'upload.php';


		// ADDING META BOX TO WOOCOMMERCE ORDER PAGE
		function thsp_add_checklist_meta_box() {
		
			foreach ( wc_get_order_types( 'order-meta-boxes' ) as $type ) {
				add_meta_box( 'woocommerce-order-checklist', 'Custom Checklist Metabox', 'WC_Checklist_Meta_Box_Order_Items::outputs', $type, 'normal', 'high' );
			}

			class WC_Checklist_Meta_Box_Order_Items {

				public static function outputs( $post ) {
					$order = wc_get_order( $post->ID );
					$pre_excav_args = array();
					$pre_trail_args = array();
					$pre_attach_args = array();
					$pre_equip_args = array();
					$custo_collect_args = array();
					$a = 0;

					// $meta_data = new WC_Meta_Data();
					// $order->add_meta_data( '_custo_collection_checklist_1', 'Test 1' );
					// $order->save();

					// print_r(get_post_meta( $post->ID, '_custo_collection_checklist_1', true ));
					// print_r($order->get_meta);

					?>

					<div class="s_meta_box" data-order-id="<?php echo $post->ID ?>" data-url="<?php echo plugin_dir_url( __FILE__ ) ?>">
						<h1 class="checklist_meta_headline"><strong>Pre Departure Checklist (Customer Pickup)</strong></h1>

						<?php
						$pre_excav_checklist = get_option( 'thsp_lvl1_excav' ); 
						$pre_trail_checklist = get_option( 'thsp_lvl1_trail' ); 
						$pre_attach_checklist = get_option( 'thsp_lvl1_attach' ); 
						$pre_equip_checklist = get_option( 'thsp_lvl1_equip' ); 
						$custo_collect_checklist = get_option( 'thsp_custo_collect' ); 

						$pre_excav_checklist_array = preg_split('/\r\n|\r|\n/', $pre_excav_checklist);
						$pre_trail_checklist_array = preg_split('/\r\n|\r|\n/', $pre_trail_checklist);
						$pre_attach_checklist_array = preg_split('/\r\n|\r|\n/', $pre_attach_checklist);
						$pre_equip_checklist_array = preg_split('/\r\n|\r|\n/', $pre_equip_checklist);
						$custo_collect_checklist_array = preg_split('/\r\n|\r|\n/', $custo_collect_checklist);

						for ($i = 0; $i < count($pre_excav_checklist_array); $i++) {
							$pre_excav_args[] = $pre_excav_checklist_array[$i];
						}

						for ($i = 0; $i < count($pre_trail_checklist_array); $i++) {
							$pre_trail_args[] = $pre_trail_checklist_array[$i];
						}

						for ($i = 0; $i < count($pre_attach_checklist_array); $i++) {
							$pre_attach_args[] = $pre_attach_checklist_array[$i];
						}

						for ($i = 0; $i < count($pre_equip_checklist_array); $i++) {
							$pre_equip_args[] = $pre_equip_checklist_array[$i];
						}

						for ($i = 0; $i < count($custo_collect_checklist_array); $i++) {
							$custo_collect_args[] = $custo_collect_checklist_array[$i];
						}

						foreach ($order->get_items() as $item_id => $item ) {
							$product = $item->get_product();
							$active_price   = $product->get_price();
							$prod_id = $item->get_product_id();
							$prod_name = $item->get_name();
							$prod_img = $product->get_image(); ?>

							<div class="list_items" data-items="<?php echo $prod_lists; ?>">
								<?php

								// $metas = get_post_meta($prod_id);
								// print_r(array_combine(array_keys($metas), array_column($metas, '0')));

								$term_obj_list = get_the_terms( $prod_id, 'product_cat' );
								$terms_string = wp_list_pluck($term_obj_list, 'slug');

								foreach ($terms_string as $term_string) {
									include plugin_dir_path( __FILE__ ) . 'partials/md-tacex-main-admin-heading-area.php';

									if ($term_string === 'excavator') { ?>
										<div class="r_checklist_meta_box r_excavator" style="display:none;">
											<h6>item view</h6>
										
											<?php
											for ($i = 0; $i < count($pre_excav_args); $i++) { ?>

												<div class="checklist_meta_box" data-meta-id="<?php echo $prod_id; ?>">
													<div class="checklist_option">
														<h4><?php echo $pre_excav_args[$i] ?></h4>
														<?php include plugin_dir_path( __FILE__ ) . 'partials/md-tacex-main-admin-checklist-loop.php'; ?>
												</div>

												<?php
											} ?>
										</div>
										<?php
									} else if ($term_string === 'trailer') { ?>
										<div class="r_checklist_meta_box r_trailer" style="display:none;">
											<h6>item view</h6>

											<?php
											for ($i = 0; $i < count($pre_trail_args); $i++) { ?>

												<div class="checklist_meta_box" data-meta-id="<?php echo $prod_id; ?>">
													<div class="checklist_option">
														<h4><?php echo $pre_trail_args[$i] ?></h4>
														<?php include plugin_dir_path( __FILE__ ) . 'partials/md-tacex-main-admin-checklist-loop.php'; ?>
												</div>

												<?php
											} ?>
										</div>
										<?php
									} else if ($term_string === 'attachments') { ?>
										<div class="r_checklist_meta_box r_attachments" style="display:none;">
											<h6>item view</h6>

											<?php
											for ($i = 0; $i < count($pre_attach_args); $i++) { ?>

												<div class="checklist_meta_box" data-meta-id="<?php echo $prod_id; ?>">
													<div class="checklist_option">
														<h4><?php echo $pre_attach_args[$i] ?></h4>
														<?php include plugin_dir_path( __FILE__ ) . 'partials/md-tacex-main-admin-checklist-loop.php'; ?>
												</div>

												<?php
											} ?>
										</div>
										<?php
									} else if ($term_string === 'equipment') { ?>
										<div class="r_checklist_meta_box r_equipment" style="display:none;">
											<h6>item view</h6>

											<?php
											for ($i = 0; $i < count($pre_equip_args); $i++) { ?>

												<div class="checklist_meta_box" data-meta-id="<?php echo $prod_id; ?>">
													<div class="checklist_option">
														<h4><?php echo $pre_equip_args[$i] ?></h4>
														<?php include plugin_dir_path( __FILE__ ) . 'partials/md-tacex-main-admin-checklist-loop.php'; ?>
												</div>
												
												<?php
											} ?>
										</div>
										<?php
									}
								}
								?>
							</div>
							<?php
							$a++;
						}
						?>

						<div>
							<hr />
						</div>

						<div class="list_items">
							<?php include plugin_dir_path( __FILE__ ) . 'partials/md-tacex-main-admin-heading-area-cust.php'; ?>
								
							<div class="r_checklist_meta_box r_custo_collection" style="display:none;">
								<h6>item view</h6>

								<?php
								for ($i = 0; $i < count($custo_collect_args); $i++) { ?>

									<div class="checklist_meta_box" data-meta-id="<?php echo $post->ID; ?>">
										<div class="checklist_option">
											<h4><?php echo $custo_collect_args[$i] ?></h4>
											<?php include plugin_dir_path( __FILE__ ) . 'partials/md-tacex-main-admin-checklist-loop-cust.php'; ?>
									</div>

									<?php
								} ?>
							</div>
						</div>
					</div>
					
					<?php
				}
			}
		}
	}

}
