<?php

// Content of the options page
function apexchat_options_page() {
?>
	<div class="wrap">
		<h2>ApexChat Options</h2>
		<form action="options.php" method="POST">
			<?php settings_fields( APEXCHAT_PLUGIN_SETTING_NAME ); ?>
			<?php do_settings_sections( APEXCHAT_OPTIONS_PAGE_SLUG ); ?>
			<?php submit_button(); ?>
		</form>
	</div>
<?php
}

// Older versions of the plugin used a different name for the settings check if exists before 
function apexchat_check_for_old_settings() {
	$oldOptions = get_option( 'apex_settings_group' );
	if ($oldOptions) {
		$newOptions[ 'apexchat_setting_companykey' ] = $oldOptions[ 'apex_setting_companykey' ];
		$newOptions[ 'apexchat_setting_enabled' ] = $oldOptions[ 'apex_setting_enabled' ];
		update_option( APEXCHAT_PLUGIN_SETTING_NAME, $newOptions );
		delete_option( 'apex_settings_group' );
	}
}

// Create two records in WordPress' backend
function apexchat_register_settings() {
	register_setting( APEXCHAT_PLUGIN_SETTING_NAME, APEXCHAT_VERSION_SETTING_NAME, 'sanitize_text_field' );
	register_setting( APEXCHAT_PLUGIN_SETTING_NAME, APEXCHAT_PLUGIN_SETTING_NAME, 'apexchat_settings_validate_callback_function' );
}

// Set the values for the records
function apexchat_add_options_to_settings() {
	update_option( APEXCHAT_VERSION_SETTING_NAME, APEXCHAT_CURRENT_VERSION ); // Upserts. No need to check if option exists.

	// Add the section to reading settings so we can add our fields to it
	add_settings_section( 'apexchat_setting_section', 'General', 'apexchat_setting_section_callback_function', APEXCHAT_OPTIONS_PAGE_SLUG );

	// Add the field with the names and function to use for our new settings, put it in our new section
	add_settings_field( 'apexchat_setting_companykey', 'Company Key', 'apexchat_setting_companykey_callback_function', APEXCHAT_OPTIONS_PAGE_SLUG, 'apexchat_setting_section' );
	add_settings_field( 'apexchat_setting_enabled', 'Enabled', 'apexchat_setting_enabled_callback_function', APEXCHAT_OPTIONS_PAGE_SLUG, 'apexchat_setting_section' );
	add_settings_field( 'apexchat_admin_disbled', 'Disable For Logged In Users', 'apexchat_admin_disabled_callback_function', APEXCHAT_OPTIONS_PAGE_SLUG, 'apexchat_setting_section' );
}

function apexchat_setting_section_callback_function() {
	echo ''; // describes the general section
}

function apexchat_setting_companykey_callback_function() {
	$options = get_option( APEXCHAT_PLUGIN_SETTING_NAME );
	echo '<input name="apexchat_settings_group[apexchat_setting_companykey]" id="apexchat_setting_companykey" type="text" value="' . sanitize_text_field(@$options[ 'apexchat_setting_companykey' ]) . '" class="code" /> The company key provided to you by ApexChat';
}

function apexchat_setting_enabled_callback_function() {
	$options = get_option( APEXCHAT_PLUGIN_SETTING_NAME );
	echo '<input name="apexchat_settings_group[apexchat_setting_enabled]" id="apexchat_setting_enabled" type="checkbox" value="1" ' . checked( @$options[ 'apexchat_setting_enabled' ], true, false ) . ' class="code" /> Enable the chat invitation';
}

function apexchat_admin_disabled_callback_function() {
	$options = get_option( APEXCHAT_PLUGIN_SETTING_NAME );
	echo '<input name="apexchat_settings_group[apexchat_admin_disbled]" id="apexchat_admin_disbled" type="checkbox" value="1" ' . checked( @$options[ 'apexchat_admin_disbled' ], true, false ) . ' class="code" /> Disable for logged in users';
}

function apexchat_settings_validate_callback_function( $input ) {
	$input[ 'apexchat_setting_companykey' ] = sanitize_company_key($input[ 'apexchat_setting_companykey' ]);
	$input[ 'apexchat_setting_enabled' ] = $input[ 'apexchat_setting_enabled' ] ? $input[ 'apexchat_setting_enabled' ] : false ;
	$input[ 'apexchat_admin_disbled' ] = $input[ 'apexchat_admin_disbled' ] ? $input[ 'apexchat_admin_disbled' ] : false ;
	return $input;
}

function sanitize_company_key( $unfiltered_key ){
   return trim(esc_attr(sanitize_text_field($unfiltered_key)));
}