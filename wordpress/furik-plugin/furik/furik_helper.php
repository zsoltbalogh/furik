<?php
function furik_extra_field_enabled($name) {
	global $furik_enable_extra_fields;

	return in_array($name, $furik_enable_extra_fields);
}
/**
 * Load template.
 *
 * @param string $_template_file Filename with extension.
 * @param array $args Array of arguments.
 */
function furik_load_template( $_template_file, $args = array() ) {
    $plugin = basename( plugin_dir_path(   __FILE__   ) );
    $overridden_template = locate_template( $plugin . '/' . $_template_file );

    ob_start();

    if ( $overridden_template ) {
        /*
         * Method locate_template() returns path to file.
         * If either the child theme or the parent theme have overridden the template.
         */
        load_template( $overridden_template, true, $args );
    } else {
        /*
         * If neither the child nor parent theme have overridden the template,
         * we load the template from the 'templates' sub-directory of the directory this file is in.
         */
        load_template( dirname( __FILE__ ) . '/templates/' . $_template_file, true, $args );
    }

    $content = ob_get_contents();
    ob_end_clean();

    return $content;
}

function furik_numr($name, $def = 0) {
	return is_numeric($_REQUEST[$name]) ? $_REQUEST[$name] : $def;
}

function furik_order_sign($order_ref) {
	global $furik_payment_secret_key;

	return md5($order_ref . $furik_payment_secret_key . "internal");
}

function furik_transaction_id($local_id) {
	$transactionId = substr(md5($_SERVER['SERVER_ADDR']), 0, 4) . '-' . $local_id;
	return $transactionId;
}

function furik_url($uri, $parameters = array(), $add_proto = true) {
	global $furik_homepage_https, $furik_homepage_url;
	$url = "";

	if ($add_proto) {
		$url .= ($furik_homepage_https ? "https" : "http") . "://" ;
	}

	$url .= $furik_homepage_url . $uri;

	if (count($parameters)) {
		foreach ($parameters as $key => $value) {
			$url .= strpos($url, '?') ? "&" : "?";
			$url .= urlencode($key);
			$url .= "=";
			$url .= urlencode($value);
		}
	}

	return $url;
}