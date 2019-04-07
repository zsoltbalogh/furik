<?php
function furik_url($url, $parameters = array()) {
	global $furik_homepage_https, $furik_homepage_url;
	$url = $furik_homepage_https ? "https" : "http" . "://" . $furik_homepage_url . $url;

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