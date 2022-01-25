<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://morningstardigital.com.au/
 * @since      1.0.0
 *
 * @package    Md_Tacex_Main
 * @subpackage Md_Tacex_Main/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Md_Tacex_Main
 * @subpackage Md_Tacex_Main/includes
 * @author     Morningstar Digital <https://morningstardigital.com.au/>
 */
class Md_Tacex_Main {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Md_Tacex_Main_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'MD_TACEX_MAIN_VERSION' ) ) {
			$this->version = MD_TACEX_MAIN_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'md-tacex-main';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$this->thsp_settings();

		register_activation_hook( __FILE__, array( $this, 'activate' ) );
		add_action( 'init', array( $this, 'rewrite' ) );
		add_filter( 'template_include', array( $this, 'change_template' ), 99, 1 );
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Md_Tacex_Main_Loader. Orchestrates the hooks of the plugin.
	 * - Md_Tacex_Main_i18n. Defines internationalization functionality.
	 * - Md_Tacex_Main_Admin. Defines all hooks for the admin area.
	 * - Md_Tacex_Main_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-md-tacex-main-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-md-tacex-main-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-md-tacex-main-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-md-tacex-main-public.php';

		$this->loader = new Md_Tacex_Main_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Md_Tacex_Main_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Md_Tacex_Main_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Md_Tacex_Main_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_thsp_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_thsp_scripts' );
		$plugin_admin->thsp_order_metabox_checklist();
		// $plugin_admin->thsp_custom_meta_fields();

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Md_Tacex_Main_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	}


	public function thsp_settings() {
		define('THSP_TITLE', 'Tacex Hire System');
		define('THSP_SONETITLE', 'This section 1 title');
		define('THSP_STWOTITLE', 'This section 2 title');
		define('THSP_STHRTITLE', 'This section 3 title');
		define('THSP_SFOUTITLE', 'This section 4 title');
		define('THSP_SFIVTITLE', 'This section 5 title');
		define('THSP_SSIXTITLE', 'This section 6 title');
		define('THSP_SSEVTITLE', 'This section 7 title');
		define('THSP_SEIGTITLE', 'This section 8 title');
		define('THSP_ENDPOINT', get_option('thsp_endpoint', 'tacex' ));

		add_action('admin_menu', 'thsp_admin_page');
		add_action('admin_init', 'thsp_settings_page');

		function thsp_admin_page() {
			add_options_page('Tacex Hire Plugin', 'Tacex System', 'manage_options', 'thsplugin_page', 'thsp_option');
		}

		function thsp_settings_page() {
			$upload_dir = wp_upload_dir();
			$thsp_dir = $upload_dir['basedir'].'/thsp_uploads';
			
			if ( ! file_exists( $thsp_dir ) ) {
				mkdir( $thsp_dir, 0755, true );
			}
			
			// sections 
			add_settings_section( 'thsp_s1section', null, 'thsp_secone_callback', 'thsplugin_page' );
			add_settings_section( 'thsp_s2section', null, 'thsp_sectwo_callback', 'thsplugin_page' );
			add_settings_section( 'thsp_s3section', null, 'thsp_secthr_callback', 'thsplugin_page' );
			add_settings_section( 'thsp_s4section', null, 'thsp_secfou_callback', 'thsplugin_page' );
			add_settings_section( 'thsp_s5section', null, 'thsp_secfiv_callback', 'thsplugin_page' );
			add_settings_section( 'thsp_s6section', null, 'thsp_secsix_callback', 'thsplugin_page' );
			add_settings_section( 'thsp_s7section', null, 'thsp_secsev_callback', 'thsplugin_page' );
			add_settings_section( 'thsp_s8section', null, 'thsp_seceig_callback', 'thsplugin_page' );

			// section 1 - general settings
			add_settings_field( 'thsp_endpoint', null, 'endpoint_html', 'thsplugin_page', 'thsp_s1section' );
			register_setting( 'thsplugin', 'thsp_endpoint', array('sanitize_callback' => 'sanitize_text_field', 'default' => 'tacex') );

			add_settings_field( 'thsp_postcode', null, 'postcode_html', 'thsplugin_page', 'thsp_s1section' );
			register_setting( 'thsplugin', 'thsp_postcode', array('sanitize_callback' => 'sanitize_textarea_field', 'default' => '8000') );

			add_settings_field( 'thsp_booqable_sync', null, 'booqable_data_html', 'thsplugin_page', 'thsp_s1section' );
			register_setting( 'thsplugin', 'thsp_booqable_sync');

			// section 2 - order checkslits
			add_settings_field( 'thsp_lvl1_excav', 'Level 1 Departure Excavator Checklist', 'checklist_html', 'thsplugin_page', 'thsp_s2section', array('the_name' => 'thsp_lvl1_excav') );
			register_setting( 'thsplugin', 'thsp_lvl1_excav', array('sanitize_callback' => 'sanitize_textarea_field', 'default' => null) );

			add_settings_field( 'thsp_lvl1_trail', 'Level 1 Departure Trailer Checklist', 'checklist_html', 'thsplugin_page', 'thsp_s2section', array('the_name' => 'thsp_lvl1_trail') );
			register_setting( 'thsplugin', 'thsp_lvl1_trail', array('sanitize_callback' => 'sanitize_textarea_field', 'default' => null) );

			add_settings_field( 'thsp_lvl1_attach', 'Level 1 Departure Attachments Checklist', 'checklist_html', 'thsplugin_page', 'thsp_s2section', array('the_name' => 'thsp_lvl1_attach') );
			register_setting( 'thsplugin', 'thsp_lvl1_attach', array('sanitize_callback' => 'sanitize_textarea_field', 'default' => null) );

			add_settings_field( 'thsp_lvl1_equip', 'Level 1 Departure Equipment Checklist', 'checklist_html', 'thsplugin_page', 'thsp_s2section', array('the_name' => 'thsp_lvl1_equip') );
			register_setting( 'thsplugin', 'thsp_lvl1_equip', array('sanitize_callback' => 'sanitize_textarea_field', 'default' => null) );

			add_settings_field( 'thsp_custo_collect', 'Collection Checklist for Customer', 'checklist_html', 'thsplugin_page', 'thsp_s2section', array('the_name' => 'thsp_custo_collect') );
			register_setting( 'thsplugin', 'thsp_custo_collect', array('sanitize_callback' => 'sanitize_textarea_field', 'default' => null) );

			// section 3 - order checklists 
			add_settings_field( 'thsp_lvl2_excav', 'Level 2 Excavator Checklist', 'checklist_html', 'thsplugin_page', 'thsp_s3section', array('the_name' => 'thsp_lvl2_excav') );
			register_setting( 'thsplugin', 'thsp_lvl2_excav', array('sanitize_callback' => 'sanitize_textarea_field', 'default' => null) );

			add_settings_field( 'thsp_lvl2_trail', 'Level 2 Trailer Checklist', 'checklist_html', 'thsplugin_page', 'thsp_s3section', array('the_name' => 'thsp_lvl2_trail') );
			register_setting( 'thsplugin', 'thsp_lvl2_trail', array('sanitize_callback' => 'sanitize_textarea_field', 'default' => null) );

			add_settings_field( 'thsp_lvl2_attach', 'Level 2 Attachments Checklist', 'checklist_html', 'thsplugin_page', 'thsp_s3section', array('the_name' => 'thsp_lvl2_attach') );
			register_setting( 'thsplugin', 'thsp_lvl2_attach', array('sanitize_callback' => 'sanitize_textarea_field', 'default' => null) );

			add_settings_field( 'thsp_lvl2_equip', 'Level 2 Equipment Checklist', 'checklist_html', 'thsplugin_page', 'thsp_s3section', array('the_name' => 'thsp_lvl2_equip') );
			register_setting( 'thsplugin', 'thsp_lvl2_equip', array('sanitize_callback' => 'sanitize_textarea_field', 'default' => null) );

			// section 4 - order checklists 
			add_settings_field( 'thsp_lvl3_excav', 'Level 3 Excavator Checklist', 'checklist_html', 'thsplugin_page', 'thsp_s4section', array('the_name' => 'thsp_lvl3_excav') );
			register_setting( 'thsplugin', 'thsp_lvl3_excav', array('sanitize_callback' => 'sanitize_textarea_field', 'default' => null) );

			add_settings_field( 'thsp_lvl3_trail', 'Level 3 Trailer Checklist', 'checklist_html', 'thsplugin_page', 'thsp_s4section', array('the_name' => 'thsp_lvl3_trail') );
			register_setting( 'thsplugin', 'thsp_lvl3_trail', array('sanitize_callback' => 'sanitize_textarea_field', 'default' => null) );

			add_settings_field( 'thsp_lvl3_attach', 'Level 3 Attachments Checklist', 'checklist_html', 'thsplugin_page', 'thsp_s4section', array('the_name' => 'thsp_lvl3_attach') );
			register_setting( 'thsplugin', 'thsp_lvl3_attach', array('sanitize_callback' => 'sanitize_textarea_field', 'default' => null) );

			add_settings_field( 'thsp_lvl3_equip', 'Level 3 Equipment Checklist', 'checklist_html', 'thsplugin_page', 'thsp_s4section', array('the_name' => 'thsp_lvl3_equip') );
			register_setting( 'thsplugin', 'thsp_lvl3_equip', array('sanitize_callback' => 'sanitize_textarea_field', 'default' => null) );

			// section 5 - order checklists 
			add_settings_field( 'thsp_lvl4_excav', 'Level 4 Excavator Checklist', 'checklist_html', 'thsplugin_page', 'thsp_s5section', array('the_name' => 'thsp_lvl4_excav') );
			register_setting( 'thsplugin', 'thsp_lvl4_excav', array('sanitize_callback' => 'sanitize_textarea_field', 'default' => null) );

			add_settings_field( 'thsp_lvl4_trail', 'Level 4 Trailer Checklist', 'checklist_html', 'thsplugin_page', 'thsp_s5section', array('the_name' => 'thsp_lvl4_trail') );
			register_setting( 'thsplugin', 'thsp_lvl4_trail', array('sanitize_callback' => 'sanitize_textarea_field', 'default' => null) );

			add_settings_field( 'thsp_lvl4_attach', 'Level 4 Attachments Checklist', 'checklist_html', 'thsplugin_page', 'thsp_s5section', array('the_name' => 'thsp_lvl4_attach') );
			register_setting( 'thsplugin', 'thsp_lvl4_attach', array('sanitize_callback' => 'sanitize_textarea_field', 'default' => null) );

			add_settings_field( 'thsp_lvl4_equip', 'Level 4 Equipment Checklist', 'checklist_html', 'thsplugin_page', 'thsp_s5section', array('the_name' => 'thsp_lvl4_equip') );
			register_setting( 'thsplugin', 'thsp_lvl4_equip', array('sanitize_callback' => 'sanitize_textarea_field', 'default' => null) );

			// section 6 - order checklists 
			add_settings_field( 'thsp_lvl5_excav', 'Level 5 Excavator Checklist', 'checklist_html', 'thsplugin_page', 'thsp_s6section', array('the_name' => 'thsp_lvl5_excav') );
			register_setting( 'thsplugin', 'thsp_lvl5_excav', array('sanitize_callback' => 'sanitize_textarea_field', 'default' => null) );

			add_settings_field( 'thsp_lvl5_trail', 'Level 5 Trailer Checklist', 'checklist_html', 'thsplugin_page', 'thsp_s6section', array('the_name' => 'thsp_lvl5_trail') );
			register_setting( 'thsplugin', 'thsp_lvl5_trail', array('sanitize_callback' => 'sanitize_textarea_field', 'default' => null) );

			add_settings_field( 'thsp_lvl5_attach', 'Level 5 Attachments Checklist', 'checklist_html', 'thsplugin_page', 'thsp_s6section', array('the_name' => 'thsp_lvl5_attach') );
			register_setting( 'thsplugin', 'thsp_lvl5_attach', array('sanitize_callback' => 'sanitize_textarea_field', 'default' => null) );

			add_settings_field( 'thsp_lvl5_equip', 'Level 5 Equipment Checklist', 'checklist_html', 'thsplugin_page', 'thsp_s7section', array('the_name' => 'thsp_lvl6_equip') );
			register_setting( 'thsplugin', 'thsp_lvl5_equip', array('sanitize_callback' => 'sanitize_textarea_field', 'default' => null) );

			// section 6 - order checklists 
			add_settings_field( 'thsp_lvl6_excav', 'Level 6 Excavator Checklist', 'checklist_html', 'thsplugin_page', 'thsp_s7section', array('the_name' => 'thsp_lvl6_excav') );
			register_setting( 'thsplugin', 'thsp_lvl6_excav', array('sanitize_callback' => 'sanitize_textarea_field', 'default' => null) );

			add_settings_field( 'thsp_lvl6_trail', 'Level 6 Trailer Checklist', 'checklist_html', 'thsplugin_page', 'thsp_s7section', array('the_name' => 'thsp_lvl6_trail') );
			register_setting( 'thsplugin', 'thsp_lvl6_trail', array('sanitize_callback' => 'sanitize_textarea_field', 'default' => null) );

			add_settings_field( 'thsp_lvl6_attach', 'Level 6 Attachments Checklist', 'checklist_html', 'thsplugin_page', 'thsp_s7section', array('the_name' => 'thsp_lvl6_attach') );
			register_setting( 'thsplugin', 'thsp_lvl6_attach', array('sanitize_callback' => 'sanitize_textarea_field', 'default' => null) );

			add_settings_field( 'thsp_lvl6_equip', 'Level 6 Equipment Checklist', 'checklist_html', 'thsplugin_page', 'thsp_s7section', array('the_name' => 'thsp_lvl6_equip') );
			register_setting( 'thsplugin', 'thsp_lvl6_equip', array('sanitize_callback' => 'sanitize_textarea_field', 'default' => null) );

			// section 7 - order checklists 
			add_settings_field( 'thsp_checklist_info', 'Checklist Information', 'info_html', 'thsplugin_page', 'thsp_s8section' );
			register_setting( 'thsplugin', 'thsp_checklist_info', array('sanitize_callback' => 'sanitize_text_field', 'default' => null) );
			
		}

		function postcode_html() { ?>
			<div class="input-field">
				<textarea name="thsp_postcode" class="materialize-textarea validate"><?php echo esc_attr( get_option( 'thsp_postcode') ); ?></textarea>
				<label for="thsp_postcode">Multiple post codes will be separated by comma...</label>
			</div>
		<?php }

		function endpoint_html() { ?>
			<div class="input-field">
				<input name="thsp_endpoint" type="text" class="validate" value="<?php echo esc_attr( get_option( 'thsp_endpoint') ); ?>">
				<label for="thsp_endpoint">Endpoint Page</label>
			</div>
		<?php }

		// REUSABLE FUNCTION 
		function checklist_html($args) { ?>
			<div class="input-field">
				<textarea name="<?php echo $args['the_name'] ?>" class="materialize-textarea validate" style="white-space: pre-line"><?php echo esc_attr( get_option($args['the_name']) ); ?></textarea>
				<label for="<?php echo $args['the_name'] ?>">Multiple post codes will be separated by comma...</label>
			</div>
		<?php }

		function booqable_data_html() { ?>
			<a href="#" class="btn brand z-depth-0" id="booqable_sync_btn">Sync Booqable Data</a>
		<?php
		}

		function info_html($args) { 
			require_once plugin_dir_path( __DIR__ ) . 'admin/partials/md-tacex-main-admin-information.php';
		?>
		<?php 
		}

		function thsp_secone_callback($args) { ?>
			<h3 class="thsp_stitle">
				<?php 
				$args['title'] = THSP_SONETITLE; 
				echo $args['title'] ?>
			</h3>
		<?php 
		}

		function thsp_sectwo_callback($args) { ?>
			<h3 class="thsp_stitle">
				<?php 
				$args['title'] = THSP_STWOTITLE; 
				echo $args['title'] ?>
			</h3>
		<?php 
		}

		function thsp_secthr_callback($args) { ?>
			<h3 class="thsp_stitle">
				<?php 
				$args['title'] = THSP_STHRTITLE; 
				echo $args['title'] ?>
			</h3>
		<?php 
		}

		function thsp_secfou_callback($args) { ?>
			<h3 class="thsp_stitle">
				<?php 
				$args['title'] = THSP_SFOUTITLE; 
				echo $args['title'] ?>
			</h3>
		<?php 
		}

		function thsp_secfiv_callback($args) { ?>
			<h3 class="thsp_stitle">
				<?php 
				$args['title'] = THSP_SFIVTITLE; 
				echo $args['title'] ?>
			</h3>
		<?php 
		}

		function thsp_secsix_callback($args) { ?>
			<h3 class="thsp_stitle">
				<?php 
				$args['title'] = THSP_SSIXTITLE; 
				echo $args['title'] ?>
			</h3>
		<?php 
		}

		function thsp_secsev_callback($args) { ?>
			<h3 class="thsp_stitle">
				<?php 
				$args['title'] = THSP_SSEVTITLE; 
				echo $args['title'] ?>
			</h3>
		<?php 
		}

		function thsp_seceig_callback($args) { ?>
			<h3 class="thsp_stitle">
				<?php 
				$args['title'] = THSP_SEIGTITLE; 
				echo $args['title'] ?>
			</h3>
		<?php 
		}


		function do_settings_custom_sections($page) {
			global $wp_settings_sections, $wp_settings_fields;

			if ( ! isset( $wp_settings_sections[ $page ] ) ) {
				return;
			}
	
			foreach ( (array) $wp_settings_sections[ $page ] as $section ) :
				$style_stat = (strpos($section['id'], 'thsp_s1section') !== false) ? 'block' : 'none';
				echo '<div class="tab_content row" id="'.$section['id'].'" style="display:'.$style_stat.'">';
					if ( $section['title'] ) {
						echo "<h2>{$section['title']}</h2>\n";
					}

					if ( $section['callback'] ) {
						call_user_func( $section['callback'], $section );
					}

					if ( ! isset( $wp_settings_fields ) || ! isset( $wp_settings_fields[ $page ] ) || ! isset( $wp_settings_fields[ $page ][ $section['id'] ] ) ) {
						continue;
					}
					echo '<div class="form-table" role="presentation">';
						do_settings_fields( $page, $section['id'] );
					echo '</div>';
				echo '</div>';
			endforeach;
		}
		add_action('do_settings_sections', 'do_settings_custom_sections');


		function thsp_option() { ?>
			<div class="wrap thsp_page container">
				<h1><?php echo THSP_TITLE; ?></h1>
				<form action="options.php" method="POST">
					<?php settings_fields( 'thsplugin' ); ?>
					<div id="thsp_tab" class="tab_links">
						<a href="#" class="tab_link activ" data-link="thsp_s1s">General Settings</a>
						<a href="#" class="tab_link" data-link="thsp_s2s">Processing Checklist</a>
						<a href="#" class="tab_link" data-link="thsp_s3s">Ready for Hire Checklist</a>
						<a href="#" class="tab_link" data-link="thsp_s4s">Pre Hire Issue Checklist</a>
						<a href="#" class="tab_link" data-link="thsp_s5s">Hired Out Checklist</a>
						<a href="#" class="tab_link" data-link="thsp_s6s">Returned Checklist</a>
						<a href="#" class="tab_link" data-link="thsp_s7s">Process Complete Checklist</a>
						<a href="#" class="tab_link" data-link="thsp_s8s">Checklist Information</a>
					</div>
					<?php do_settings_custom_sections( 'thsplugin_page' ); ?>
					<?php submit_button(); ?>
				</form>
			</div>
		<?php }
	}
	

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Md_Tacex_Main_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	function activate() {
		set_transient( 'vpt_flush', 1, 60 );
	}

	function rewrite() {
		add_rewrite_endpoint( THSP_ENDPOINT, EP_PERMALINK );
		flush_rewrite_rules();
		// var_dump(get_transient( 'vpt_flush' ));
		if(get_transient( 'vpt_flush' )) {
			delete_transient( 'vpt_flush' );
			flush_rewrite_rules();
		}
	}

	public function change_template( $template ) {
		// $this->define_public_hooks();echo 333;die();
		if( get_query_var( THSP_ENDPOINT, false ) !== false ) {

		// $plugin = new Md_Tacex_Main();
		// $plugin_public = new Md_Tacex_Main_Public( $plugin->get_plugin_name(), $plugin->get_version() );
		// wp_enqueue_script( 'tacex-public-css', $plugin_public->enqueue_styles() );


			//Check plugin directory next
			// $newTemplate = plugin_dir_path( __FILE__ ) . '../public/templates/index-page.php';
			$newTemplate = plugin_dir_path( __FILE__ ) . '../public/templates/index-endpoint.php';
			if( file_exists( $newTemplate ) ) {
				return $newTemplate;
			}
		}

		//Fall back to original template
		return $template;

	}

}
