<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/*
 * {plugin_jump_starter_name} Settings
 *
 * All functionality pertaining to the subscribe settings screen.
 *
 * @package WordPress
 * @subpackage {class_name}_Settings
 * @category Admin
 * @author {plugin_jump_starter_author}
 * @since 1.0.0
 */
class {class_name}_Settings extends {class_name}_Settings_API {
	
	/**
	 * __construct function.
	 * 
	 * @access public
	 * @since 1.0.0
	 * @return void
	 */
	public function __construct () {
		global ${class_name};
		parent::__construct( ${class_name}->name, '{clean_class_name}' ); // Required in extended classes.
	} // End __construct()

	/**
	 * init_sections function.
	 * 
	 * @access public
	 * @since 1.0.0
	 * @return void
	 */
	public function init_sections () {
		$sections = array();

		$sections['general'] = array(
			'name' => __('General Settings' , '{plugin_jump_starter_textdomain}'),
		);

		$this->sections = $sections;
	} // End init_sections()
	
	/**
	 * init_fields function.
	 * 
	 * @access public
	 * @since 1.0.0
	 * @return void
	 */
	public function init_fields () {
		$fields = array();

		$fields['{clean_class_name}'] = array(
			'name' => __( 'Enable {plugin_jump_starter_name}', '{plugin_jump_starter_textdomain}' ),
			'description' => '',
			'type' => 'checkbox',
			'default' => true,
			'section' => 'general'
		);
		
		$this->fields = $fields;
	} // End init_fields()
	
} // End Class
?>