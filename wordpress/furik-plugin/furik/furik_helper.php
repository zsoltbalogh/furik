<?php
function furik_numr($name, $def = 0) {
	return is_numeric($_REQUEST[$name]) ? $_REQUEST[$name] : $def;
}

function furik_order_sign($order_ref) {
	global $furik_payment_secret_key;

	return md5($order_ref . $furik_payment_secret_key . "internal");
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