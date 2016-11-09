<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://lukasbesch.com
 * @since      1.0.0
 *
 * @package    Find_Post_By_Password
 * @subpackage Find_Post_By_Password/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Find_Post_By_Password
 * @subpackage Find_Post_By_Password/public
 * @author     Lukas Besch <connect@lukasbesch.com>
 */
class Find_Post_By_Password_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

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
		 * defined in Find_Post_By_Password_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Find_Post_By_Password_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		//wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/find-post-by-password-public.css', array(), $this->version, 'all' );

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
		 * defined in Find_Post_By_Password_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Find_Post_By_Password_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		//wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/find-post-by-password-public.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Searches password protected posts and sets cookie
	 *
	 * @since    1.0.0
	 */
	public function find_post_by_password() {
		
		$referer = wp_get_referer();
		
		if ( ! array_key_exists( 'post_password', $_POST ) ) {
			wp_safe_redirect( $referer );
			exit();
		}
		
		$post_password = wp_unslash( $_POST['post_password'] );
		
		$args = array (
			'post_type'							 => 'any',
			'has_password'           => true,
			'post_password'          => $post_password,
		);
		
		$query = new WP_Query( $args );
		
		if ( ! $query || $query->post_count !== 1 ) {
			wp_safe_redirect( add_query_arg( 'invalid_pass', true, $referer ) );
			exit();
		}
		
		$permalink = get_permalink( $query->posts[0] );
		
		require_once(ABSPATH . WPINC . '/class-phpass.php');

		$hasher = new PasswordHash( 8, true );
		
		/**
		 * Filters the life span of the post password cookie.
		 *
		 * By default, the cookie expires 10 days from creation. To turn this
		 * into a session cookie, return 0.
		 *
		 * @since 3.7.0
		 *
		 * @param int $expires The expiry time, as passed to setcookie().
		 */
		
		$expire = apply_filters( 'post_password_expires', time() + 10 * DAY_IN_SECONDS );
		
		if ( $referer ) {
			$secure = ( 'https' === parse_url( $referer, PHP_URL_SCHEME ) );
		} else {
			$secure = false;
		}
		
		setcookie( 'wp-postpass_' . COOKIEHASH, $hasher->HashPassword( wp_unslash( $_POST['post_password'] ) ), $expire, COOKIEPATH, COOKIE_DOMAIN, $secure );
		
		wp_safe_redirect( $permalink );
		exit();
	}
	
	public function add_query_vars( $vars ) {
  	$vars[] = "invalid_pass";
		return $vars;
	}
	
	public function register_shortcodes() {
		add_shortcode( 'find_post_by_password_form', array( $this, 'find_post_by_password_form' ) );
	}
	
	public function find_post_by_password_form () {
		
		$html = '';
		
		$action_url = admin_url( 'admin-ajax.php' );
		$action_url = add_query_arg( 'action', 'find_post_by_password', $action_url );
		
		$invalid_pass = (bool) get_query_var( 'invalid_pass', false );
		
		ob_start();
		?>
		<form class="protected-post-form find-post-by-password-form" action="<?php echo $action_url; ?>" method="post">
			<?php
			if ( $invalid_pass ) {
				?>
				<div class="alert alert-warning">
					<?php _e( "Das eingegebene Passwort ist leider nicht richtig.", "fpbp" ) ?>
				</div>
				<?php
			}
			?>
			<div class="form-group">
				<label class="pass-label hidden-xs-up" for="pwbox"><?php _e( "Passwort: " ) ?></label>
				<input class="form-control" name="post_password" id="pwbox" type="password" size="20" placeholder="<?php esc_attr_e( "Passwort", "fpbp" ) ?>" />
			</div>
			<input type="submit" name="Submit" class="btn btn-success btn-block" value="<?php esc_attr_e( "Freischalten", "fpbp" ) ?>" />
		</form>
			
		<?php
		$html = ob_get_clean();
		
		return apply_filters( 'fpbp/form', $html );
		
	}
	

}
