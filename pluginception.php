<?php
/* 
Plugin Name: Pluginception
Plugin URI: http://ottopress.com/wordpress-plugins/pluginception
Description: A plugin to create other plugins. Pluginception.
Version: 1.1
Author: Otto
Author URI: http://ottopress.com
License: GPLv2 only
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

// Load the textdomain
add_action('init', 'pluginception_load_textdomain');
function pluginception_load_textdomain() {
	load_plugin_textdomain('pluginception', false, dirname(plugin_basename(__FILE__)));
}


add_action('admin_menu', 'pluginception_admin_add_page');
function pluginception_admin_add_page() {
	add_plugins_page(__('Create a New Plugin','pluginception'), __('Create a New Plugin','pluginception'), 'edit_plugins', 'pluginception', 'pluginception_options_page');
}

function pluginception_options_page() {
	$results = pluginception_create_plugin();
	
	if ( $results === true ) return;
	?>
	<div class="wrap">
		<?php screen_icon(); ?>
		<h2><?php _e('Create a New Plugin','pluginception'); ?></h2>
		<?php settings_errors(); ?>
		<form method="post" action="">
		<?php wp_nonce_field('pluginception_nonce'); ?>
		<table class="form-table">
		<?php 
		$opts = array(
			'name' => __('Plugin Name', 'pluginception'),
			'slug' => __('Plugin Slug (optional)', 'pluginception'),
			'uri' => __('Plugin URI (optional)', 'pluginception'),
			'description' => __('Description (optional)', 'pluginception'),
			'version' => __('Version (optional)', 'pluginception'),
			'author' => __('Author (optional)', 'pluginception'),
			'author_uri' => __('Author URI (optional)', 'pluginception'),
			'license' => __('License (optional)', 'pluginception'),
			'license_uri' => __('License URI (optional)', 'pluginception'),		
		);

		foreach ($opts as $slug=>$title) {
			$value = '';
			if (!empty($results['pluginception_'.$slug])) $value = esc_attr($results['pluginception_'.$slug]);
			echo "<tr valign='top'><th scope='row'>{$title}</th><td><input class='regular-text' type='text' name='pluginception_{$slug}' value='{$value}'></td></tr>\n";
		}
		?>
		</table>
		<?php submit_button( __('Create a blank plugin and activate it!', 'pluginception') ); ?>
		</form>
	</div>
<?php
}

function pluginception_create_plugin() {
	if ( 'POST' != $_SERVER['REQUEST_METHOD'] )
		return false;
	
	check_admin_referer('pluginception_nonce');
		
	// remove the magic quotes
	$_POST = stripslashes_deep( $_POST );

	if (empty($_POST['pluginception_name'])) {
		add_settings_error( 'pluginception', 'required_name',__('Plugin Name is required', 'pluginception'), 'error' );
		return $_POST;
	}
	
	if ( empty($_POST['pluginception_slug'] ) ) {
		$_POST['pluginception_slug'] = sanitize_title($_POST['pluginception_name']);
	} else {
		$_POST['pluginception_slug'] = sanitize_title($_POST['pluginception_slug']);
	}
	
	if ( file_exists(trailingslashit(WP_PLUGIN_DIR).$_POST['pluginception_slug'] ) ) {
		add_settings_error( 'pluginception', 'existing_plugin', __('That plugin appears to already exist. Use a different slug or name.', 'pluginception'), 'error' );
		return $_POST;
	}

	$form_fields = array ('pluginception_name', 'pluginception_slug', 'pluginception_uri', 'pluginception_description', 'pluginception_version', 
				'pluginception_author', 'pluginception_author_uri', 'pluginception_license', 'pluginception_license_uri');
	$method = ''; // TODO TESTING

	// okay, let's see about getting credentials
	$url = wp_nonce_url('plugins.php?page=pluginception','pluginception_nonce');
	if (false === ($creds = request_filesystem_credentials($url, $method, false, false, $form_fields) ) ) {
		return true; 
	}

	// now we have some credentials, try to get the wp_filesystem running
	if ( ! WP_Filesystem($creds) ) {
		// our credentials were no good, ask the user for them again
		request_filesystem_credentials($url, $method, true, false, $form_fields);
		return true;
	}


	global $wp_filesystem;

	// create the plugin directory
	$plugdir = $wp_filesystem->wp_plugins_dir() . $_POST['pluginception_slug'];
	
	if ( ! $wp_filesystem->mkdir($plugdir) ) {
		add_settings_error( 'pluginception', 'create_directory', __('Unable to create the plugin directory.', 'pluginception'), 'error' );
		return $_POST;
	}
	
	// create the plugin header
	
	$header = <<<END
<?php
/*
Plugin Name: {$_POST['pluginception_name']}
Plugin URI: {$_POST['pluginception_uri']}
Description: {$_POST['pluginception_description']}
Version: {$_POST['pluginception_version']}
Author: {$_POST['pluginception_author']}
Author URI: {$_POST['pluginception_author_uri']}
License: {$_POST['pluginception_license']}
License URI: {$_POST['pluginception_license_uri']}
*/

END;

	$plugfile = trailingslashit($plugdir).$_POST['pluginception_slug'].'.php';
	
	if ( ! $wp_filesystem->put_contents( $plugfile, $header, FS_CHMOD_FILE) ) {
		add_settings_error( 'pluginception', 'create_file', __('Unable to create the plugin file.', 'pluginception'), 'error' );
	}

	$plugslug = $_POST['pluginception_slug'].'/'.$_POST['pluginception_slug'].'.php';
	$plugeditor = admin_url('plugin-editor.php?file='.$_POST['pluginception_slug'].'%2F'.$_POST['pluginception_slug'].'.php');

	if ( null !== activate_plugin( $plugslug, '', false, true ) ) {
		add_settings_error( 'pluginception', 'activate_plugin', __('Unable to activate the new plugin.', 'pluginception'), 'error' );
	}
	
	// plugin created and activated, redirect to the plugin editor
	?>
	<script type="text/javascript">
	<!--
	window.location = "<?php echo $plugeditor; ?>"
	//-->
	</script>
	<?php
	
	$message = sprintf(__('The new plugin has been created and activated. You can %sgo to the editor</a> if your browser does not redirect you.', 'pluginception'), '<a href="'.$plugeditor.'">');
	
	add_settings_error('pluginception', 'plugin_active', $message, 'pluginception', 'updated');
	
	return true;
}

