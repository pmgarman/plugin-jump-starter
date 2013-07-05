<?php
class {class_name} {

	/**
	 * Construct
	 * 
	 * @param string $file
	 */
	function __construct( $file ) {
		$this->name = '{plugin_jump_starter_name}';
		$this->token = '{clean_class_name}';

		add_action( 'init', array( $this, 'init' ) );
	}

	/**
	 * Init the extension settings
	 * 
	 * @return void
	 */
	function init() {
		$tabs = array(
			'{clean_class_name}' => '{class_name}_Settings'
		);

		foreach( $tabs as $key => $obj ) {
			if( !class_exists( $obj ) )
				continue;
			$this->settings_objs[ $key ] = new $obj;
			$this->settings[ $key ] = $this->settings_objs[ $key ]->get_settings();
			add_action( 'admin_init', array( $this->settings_objs[ $key ], 'setup_settings' ) );
		}

		$this->settings_screen = new {class_name}_Settings_Screen( array(
			'default_tab' => '{clean_class_name}'
		));
	}
}