<?php
# Copyright (C) 2008	John Reese
#
# This program is free software: you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation, either version 3 of the License, or
# (at your option) any later version.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.

form_security_validate( 'plugin_Source_manage_config' );
auth_reauthenticate();
access_ensure_global_level( plugin_config_get( 'manage_threshold' ) );

$f_view_threshold = gpc_get_int( 'view_threshold' );
$f_update_threshold = gpc_get_int( 'update_threshold' );
$f_manage_threshold = gpc_get_int( 'manage_threshold' );

$f_show_repo_link = gpc_get_bool( 'show_repo_link', OFF );
$f_show_search_link = gpc_get_bool( 'show_search_link', OFF );

$f_enable_mapping = gpc_get_bool( 'enable_mapping', OFF );
$f_enable_resolving = gpc_get_bool( 'enable_resolving', OFF );
$f_enable_porting = gpc_get_bool( 'enable_porting', OFF );

$f_buglink_regex_1 = gpc_get_string( 'buglink_regex_1' );
$f_buglink_reset_1 = gpc_get_string( 'buglink_reset_1', OFF );
$f_buglink_regex_2 = gpc_get_string( 'buglink_regex_2' );
$f_buglink_reset_2 = gpc_get_string( 'buglink_reset_2', OFF );

$f_bugfix_resolution = gpc_get_int( 'bugfix_resolution' );
$f_bugfix_regex_1 = gpc_get_string( 'bugfix_regex_1' );
$f_bugfix_reset_1 = gpc_get_string( 'bugfix_reset_1', OFF );
$f_bugfix_regex_2 = gpc_get_string( 'bugfix_regex_2' );
$f_bugfix_reset_2 = gpc_get_string( 'bugfix_reset_2', OFF );

function check_urls( $t_urls_in ) {
	$t_urls_in = explode( "\n", $t_urls_in );
	$t_urls_out = array();

	foreach( $t_urls_in as $t_url ) {
		$t_url = trim( $t_url );
		if ( is_blank( $t_url ) || in_array( $t_url, $t_urls_out ) ) {
			continue;
		}

		$t_urls_out[] = $t_url;
	}

	return $t_urls_out;
}

$f_remote_checkin = gpc_get_bool( 'remote_checkin', OFF );
$f_checkin_urls = gpc_get_string( 'checkin_urls' );

$f_remote_imports = gpc_get_bool( 'remote_imports', OFF );
$f_import_urls = gpc_get_string( 'import_urls' );

$t_checkin_urls = check_urls( $f_checkin_urls );
$t_import_urls = check_urls( $f_import_urls );

function maybe_set_option( $name, $value ) {
	if ( $value != plugin_config_get( $name ) ) {
		plugin_config_set( $name, $value );
	}
}

maybe_set_option( 'view_threshold', $f_view_threshold );
maybe_set_option( 'update_threshold', $f_update_threshold );
maybe_set_option( 'manage_threshold', $f_manage_threshold );

maybe_set_option( 'show_repo_link', $f_show_repo_link );
maybe_set_option( 'show_search_link', $f_show_search_link );

maybe_set_option( 'enable_mapping', $f_enable_mapping );
maybe_set_option( 'enable_resolving', $f_enable_resolving );
maybe_set_option( 'enable_porting', $f_enable_porting );

if ( ! $f_buglink_reset_1 ) {
	maybe_set_option( 'buglink_regex_1', $f_buglink_regex_1 );
} else {
	plugin_config_delete( 'buglink_regex_1' );
}

if ( ! $f_buglink_reset_2 ) {
	maybe_set_option( 'buglink_regex_2', $f_buglink_regex_2 );
} else {
	plugin_config_delete( 'buglink_regex_2' );
}

if ( ! $f_bugfix_reset_1 ) {
	maybe_set_option( 'bugfix_regex_1', $f_bugfix_regex_1 );
} else {
	plugin_config_delete( 'bugfix_regex_1' );
}

if ( ! $f_bugfix_reset_2 ) {
	maybe_set_option( 'bugfix_regex_2', $f_bugfix_regex_2 );
} else {
	plugin_config_delete( 'bugfix_regex_2' );
}

maybe_set_option( 'bugfix_resolution', $f_bugfix_resolution );

maybe_set_option( 'remote_checkin', $f_remote_checkin );
maybe_set_option( 'checkin_urls', serialize( $t_checkin_urls ) );
maybe_set_option( 'remote_imports', $f_remote_imports );
maybe_set_option( 'import_urls', serialize( $t_import_urls ) );

form_security_purge( 'plugin_Source_manage_config' );

print_successful_redirect( plugin_page( 'manage_config_page', true ) );

