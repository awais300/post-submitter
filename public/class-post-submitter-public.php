<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Post_Submitter
 * @subpackage Post_Submitter/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Post_Submitter
 * @subpackage Post_Submitter/public
 * @author     Awais <awais300@gmail.com>
 */
class Post_Submitter_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $plugin_name The name of the plugin.
	 * @param string $version The version of this plugin.
	 *
	 * @since    1.0.0
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Post_Submitter_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Post_Submitter_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/post-submitter-public-min.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Post_Submitter_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Post_Submitter_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_deregister_script( 'jquery' );
		wp_enqueue_script( 'jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js', array(), null, true );

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/post-submitter-public-min.js', array( 'jquery' ), $this->version, false );

		$wp_localize_data = array(
			'ajax_url'    => admin_url( 'admin-ajax.php' ),
			'_ajax_nonce' => wp_create_nonce( 'ajax' ),
		);

		wp_localize_script( $this->plugin_name, 'LOCAL_OBJ', $wp_localize_data );

	}

	/**
	 * Setup shortcodes for post submission form
	 * @return void
	 */
	public function setup_shortcodes() {
		add_shortcode( 'post-submitter', array( $this, 'init_post_submitter_form' ) );
	}

	/**
	 * Initiate the post submission form via shortcode
	 * @return string
	 */
	public function init_post_submitter_form() {
		ob_start();

		$post_info = array(
			'select'       => '',
			'is_logged_in' => '',
		);

		if ( ! is_user_logged_in() ) {
			$post_info['is_logged_in'] = false;
		} else {

			$select              = $this->get_custom_post_types_list();
			$post_info['select'] = $select;
		}

		include_once 'partials/post-submitter-public-display.php';
		$output = ob_get_contents();
		ob_end_clean();

		return $output;
	}

	/**
	 * Get the list of all CPT registred under WP install
	 * @return string
	 */
	public function get_custom_post_types_list() {
		$args = array(
			'public'   => true,
			'_builtin' => false,
		);

		$post_types = get_post_types( $args, 'objects' );
		$select     = '';
		$sel_txt    = esc_html__( '--Select--', 'post-submitter' );
		$select     .= '<select name="custom_post_type">';
		$select     .= "<option value=''>{$sel_txt}</option>";
		foreach ( $post_types as $key => $post_type ) {
			$slug   = $post_type->rewrite['slug'];
			$name   = $post_type->label;
			$select .= "<option value='{$slug}'>{$name}</option>";
		}
		$select .= "</select>";

		return $select;

	}

	/**
	 * Trigger feedback to save feedback via ajax
	 * @return JSON
	 */
	public function save_form() {
		if ( ! check_ajax_referer( 'ajax', '_ajax_nonce', false ) ) {
			wp_send_json_error();
			wp_die();
		} else {
			parse_str( $_POST['post_data'], $post );
			$info = $this->_save_form( $post );
			echo json_encode( $info );
		}
		exit();
	}

	/**
	 * Save feedback entry in DB securely
	 *
	 * @param Array $post
	 *
	 * @return Array
	 */
	public function _save_form( $post ) {

		$info = array(
			'error'   => false,
			'message' => '',
		);

		// Honeypot check
		if ( ! isset( $post['field'] ) || ! empty( $post['field'] ) ) {
			$info['error']   = true;
			$info['message'] = esc_html__( 'Spam detected', 'post-submitter' );

			return $info;
		}

		// Data to check
		$data = array(
			'post_title',
			'custom_post_type',
			'description',
			'excerpt',
		);

		// Check if all fields are not empty
		foreach ( $post as $key => $value ) {
			if ( in_array( $key, $data ) ) {
				if ( empty( trim( $value ) ) ) {
					$info['error']   = true;
					$info['message'] = esc_html__( 'All fields are required', 'post-submitter' );

					return $info;
				}
			}
		}

		if ( $_FILES['featured_image']['name'] == '' ) {
			$info['error']   = true;
			$info['message'] = esc_html__( 'All fields are required', 'post-submitter' );

			return $info;
		}

		// Insert post
		$post_id = wp_insert_post( array(
			'post_type'    => $post['custom_post_type'],
			'post_title'   => $post['post_title'],
			'post_content' => $post['description'],
			'post_excerpt' => $post['excerpt'],
			'post_status'  => 'draft',
		) );

		if ( $post_id ) {
			$this->set_featured_image( $post_id );
			$this->send_notification( $post_id );

			$info['error']   = false;
			$info['message'] = esc_html__( 'Post Submitted successfully and is under moderation by site admin', 'post-submitter' );

			return $info;
		} else {
			$info['error']   = true;
			$info['message'] = esc_html__( 'Something went wrong. Please try again later', 'post-submitter' );

			return $info;
		}
	}

	public function set_featured_image( $id ) {

		$upload = wp_upload_bits( $_FILES['featured_image']['name'], null, file_get_contents( $_FILES['featured_image']['tmp_name'] ) );

		if ( ! $upload_file['error'] ) {
			$post_id     = $id;
			$filename    = $upload['file'];
			$wp_filetype = wp_check_filetype( $filename, null );
			$attachment  = array(
				'post_mime_type' => $wp_filetype['type'],
				'post_title'     => sanitize_file_name( $filename ),
				'post_content'   => '',
				'post_status'    => 'inherit',
			);

			$attachment_id = wp_insert_attachment( $attachment, $filename, $post_id );

			if ( ! is_wp_error( $attachment_id ) ) {
				require_once ABSPATH . 'wp-admin/includes/image.php';

				$attachment_data = wp_generate_attachment_metadata( $attachment_id, $filename );
				wp_update_attachment_metadata( $attachment_id, $attachment_data );
				set_post_thumbnail( $post_id, $attachment_id );
			}
		}

	}

	public function send_notification( $id ) {
		$to      = get_option( 'admin_email' );
		$subject = esc_html__( 'New Post Submission', 'post-submitter' );

		$post_link = get_site_url() . "/wp-admin/post.php?post={$id}&action=edit";
		$here      = "<a href='{$post_link}'>" . esc_html__( 'here', 'post-submitter' ) . "</a>";
		$body      = sprintf( esc_html__( "A new post is submitted click %s to view it.", 'post-submitter' ), $here );
		$headers   = array( 'Content-Type: text/html; charset=UTF-8' );

		wp_mail( $to, $subject, $body, $headers );

	}

}
