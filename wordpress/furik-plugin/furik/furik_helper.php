<?php
function furik_url($url) {
	global $furik_homepage_https, $furik_homepage_url;
	$baseurl = $furik_homepage_https ? "https" : "http" . "://" . $furik_homepage_url;
	return $baseurl.$url;
}