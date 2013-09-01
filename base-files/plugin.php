<?php
/**
 * Plugin Name: {plugin_jump_starter_name}
 * Plugin URI: {plugin_jump_starter_uri}
 * Description: {plugin_jump_starter_description}
 * Version: {plugin_jump_starter_version}
 * Author: {plugin_jump_starter_author}
 * Author URI: {plugin_jump_starter_author_uri}
 * Text Domain: {plugin_jump_starter_textdomain}
 * Domain Path: /languages/
 * License: GPLv2
 */

/**
 * Copyright 2013  {plugin_jump_starter_author}  (email: {plugin_jump_starter_author_email})
 *
 * Credit: Settings API from mattyza & pmgarman
 * https://twitter.com/mattyza
 * https://twitter.com/pmgarman
 *
 * Built using the Plugin Jump Starter!
 * https://github.com/pmgarman/plugin-jump-starter
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

if( !class_exists( '{class_name}' ) ) {
	require 'classes/class-{clean_class_name}-settings-api.php';
	require 'classes/class-{clean_class_name}-settings-screen.php';
	require 'classes/class-{clean_class_name}-settings.php';
	require 'classes/class-{clean_class_name}.php';

	global ${class_name};
	${class_name} = new {class_name}( __FILE__ );

	load_plugin_textdomain( '{plugin_jump_starter_textdomain}', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}