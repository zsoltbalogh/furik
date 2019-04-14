<?php
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