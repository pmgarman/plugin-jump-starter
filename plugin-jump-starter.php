<?php
/**
 * Plugin Name: Plugin Jump Starter
 * Plugin URI: https://github.com/pmgarman/plugin-jump-starter
 * Description: Jump start plugin development with a set of base files.
 * Version: 1.1.0
 * Author: Patrick Garman
 * Author URI: http://pmgarman.me
 * Text Domain: plugin-jump-starter
 * Domain Path: /languages/
 * License: GPLv3
 */

/**
 * Copyright 2013  Patrick Garman  (email: contact@pmgarman.me)
 *
 * Based off of Pluginception by Otto
 * http://wordpress.org/plugins/pluginception/
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2, as
 * published by the Free Software Foundation.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

add_action( 'init', 'plugin_jump_starter_load_textdomain' );
function plugin_jump_starter_load_textdomain() {
	load_plugin_textdomain( 'plugin-jump-starter', false, dirname( plugin_basename( __FILE__ ) ) );
}


add_action( 'admin_menu', 'plugin_jump_starter_admin_add_page' );
function plugin_jump_starter_admin_add_page() {
	add_plugins_page( __( 'Jump Start a Plugin', 'plugin-jump-starter' ), __( 'Jump Start a Plugin', 'plugin-jump-starter' ), 'edit_plugins', 'plugin-jump-starter', 'plugin_jump_starter_options_page' );
}

function plugin_jump_starter_options_page() {
	$results = plugin_jump_starter_create_plugin();
	if ( $results === true )
		return;
	?>
	<div class="wrap">
		<?php screen_icon(); ?>
		<h2><?php _e( 'Jump Start a Plugin', 'plugin-jump-starter' ); ?></h2>
		<?php settings_errors(); ?>
		<form method="post" action="">
		<?php wp_nonce_field( 'plugin_jump_starter_nonce' ); ?>
		<table class="form-table">
		<?php 
		$opts = array(
			'name' => __( 'Plugin Name', 'plugin-jump-starter' ),
			'slug' => __( 'Plugin Slug (optional)', 'plugin-jump-starter' ),
			'uri' => __( 'Plugin URI (optional)', 'plugin-jump-starter' ),
			'description' => __( 'Description (optional)', 'plugin-jump-starter' ),
			'version' => __( 'Version (optional)', 'plugin-jump-starter' ),
			'author' => __( 'Author (optional)', 'plugin-jump-starter' ),
			'author_email' => __( 'Author Email (optional)', 'plugin-jump-starter' ),
			'author_uri' => __( 'Author URI (optional)', 'plugin-jump-starter' ),
			'textdomain' => __( 'Textdomain (optional)', 'plugin-jump-starter' ),
			'class' => __( 'Main Class Name (ex: Jump_Start)', 'plugin-jump-starter' )
		);
		foreach($opts as $slug => $title) {
			$value = '';
			if ( !empty( $results[ 'plugin_jump_starter_' . $slug ] ) ) $value = esc_attr( $results[ 'plugin_jump_starter_' . $slug ] );
			echo "<tr valign='top'><th scope='row'>{$title}</th><td><input class='regular-text' type='text' name='plugin_jump_starter_{$slug}' value='{$value}'></td></tr>\n";
		}
		?>
		</table>
		<?php submit_button( __( 'Give me a jump?', 'plugin-jump-starter' ) ); ?>
		</form>
	</div>
<?php
}

function plugin_jump_starter_create_plugin() {
	if ( 'POST' != $_SERVER['REQUEST_METHOD'] )
		return false;
	
	check_admin_referer( 'plugin_jump_starter_nonce' );
		
	// remove the magic quotes
	$_POST = stripslashes_deep( $_POST );

	if ( empty( $_POST['plugin_jump_starter_name'] ) ) {
		add_settings_error( 'plugin-jump-starter', 'required_name', __( 'Plugin Name is required', 'plugin-jump-starter' ), 'error' );
		return $_POST;
	}
	
	if ( empty($_POST['plugin_jump_starter_slug'] ) ) {
		$_POST['plugin_jump_starter_slug'] = sanitize_title( $_POST['plugin_jump_starter_name'] );
	} else {
		$_POST['plugin_jump_starter_slug'] = sanitize_title( $_POST['plugin_jump_starter_slug'] );
	}
	
	if ( file_exists( trailingslashit( WP_PLUGIN_DIR ) . $_POST['plugin_jump_starter_slug'] ) ) {
		add_settings_error( 'plugin-jump-starter', 'existing_plugin', __( 'This plugin already exists, you cannot jump start here.', 'plugin-jump-starter' ), 'error' );
		return $_POST;
	}

	$form_fields = array (
		'plugin_jump_starter_name',
		'plugin_jump_starter_slug',
		'plugin_jump_starter_uri',
		'plugin_jump_starter_description',
		'plugin_jump_starter_version',
		'plugin_jump_starter_author',
		'plugin_jump_starter_author_email',
		'plugin_jump_starter_author_uri',
		'plugin_jump_starter_textdomain',
		'plugin_jump_starter_class'
	);

	$method = ''; // TODO TESTING

	// okay, let's see about getting credentials
	$url = wp_nonce_url( 'plugins.php?page=plugin_jump_starter', 'plugin_jump_starter_nonce' );
	if ( false === ( $creds = request_filesystem_credentials( $url, $method, false, false, $form_fields ) ) ) {
		return true; 
	}

	// now we have some credentials, try to get the wp_filesystem running
	if ( !WP_Filesystem( $creds ) ) {
		// our credentials were no good, ask the user for them again
		request_filesystem_credentials( $url, $method, true, false, $form_fields );
		return true;
	}

	global $wp_filesystem;

	// create the plugin directories
	$plugdir = $wp_filesystem->wp_plugins_dir() . $_POST['plugin_jump_starter_slug'];
	
	if ( !$wp_filesystem->mkdir( $plugdir ) ) {
		add_settings_error( 'plugin-jump-starter', 'create_directory', __( 'Unable to create the plugin directory.', 'plugin-jump-starter' ), 'error' );
		return $_POST;
	}
	
	if ( !$wp_filesystem->mkdir( trailingslashit( $plugdir ) . 'classes' ) ) {
		add_settings_error( 'plugin-jump-starter', 'create_directory', __( 'Unable to create the classes directory.', 'plugin-jump-starter' ), 'error' );
		return $_POST;
	}
	
	if ( !$wp_filesystem->mkdir( trailingslashit( $plugdir ) . 'languages' ) ) {
		add_settings_error( 'plugin-jump-starter', 'create_directory', __( 'Unable to create the languages directory.', 'plugin-jump-starter' ), 'error' );
		return $_POST;
	}

	$class_name = trim( $_POST['plugin_jump_starter_class'] );
	$clean_class_name = str_replace( '_', '-', sanitize_title( $_POST['plugin_jump_starter_class'] ) );

	$files = array(
		'plugin.php' => $_POST['plugin_jump_starter_slug'] . '.php',
		'class.php' => 'classes/class-' . $clean_class_name . '.php',
		'settings.php' => 'classes/class-' . $clean_class_name . '-settings.php',
		'settings-api.php' => 'classes/class-' . $clean_class_name . '-settings-api.php',
		'settings-screen.php' => 'classes/class-' . $clean_class_name . '-settings-screen.php',
	);

	foreach( $files as $slug => $file ) {
		$contents = file_get_contents( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'base-files/' . $slug );
		foreach( $form_fields as $field ) {
			$contents = str_replace( '{' . $field . '}', $_POST[ $field ], $contents );
		}
		$contents = str_replace( '{class_name}', $class_name, $contents );
		$contents = str_replace( '{clean_class_name}', $clean_class_name, $contents );
		$plugfile = trailingslashit( $plugdir ) . $file;
		if ( !$wp_filesystem->put_contents( $plugfile, $contents, FS_CHMOD_FILE ) ) {
			add_settings_error( 'plugin-jump-starter', 'create_file', __( 'Unable to create the plugin file.', 'plugin-jump-starter' ), 'error' );
		}
	}

	$plugslug = $_POST['plugin_jump_starter_slug'] . '/' . $_POST['plugin_jump_starter_slug'] . '.php';
	$plugeditor = admin_url( 'plugin-editor.php?file=' . urlencode( $plugslug ) );

	if ( null !== activate_plugin( $plugslug, '', false, true ) ) {
		add_settings_error( 'plugin-jump-starter', 'activate_plugin', __( 'Unable to activate the new plugin.', 'plugin-jump-starter' ), 'error' );
	}
	
	// plugin created and activated, redirect to the plugin editor
	?>
	<script type="text/javascript">
	<!--
	window.location = "<?php echo $plugeditor; ?>"
	//-->
	</script>
	<?php
	
	$message = sprintf( __( 'The new plugin has been created and activated. You can %sgo to the editor</a> if your browser does not redirect you.', 'plugin-jump-starter' ), '<a href="'.$plugeditor.'">' );
	
	add_settings_error( 'plugin-jump-starter', 'plugin_active', $message, 'plugin-jump-starter', 'updated' );
	
	return true;
}

