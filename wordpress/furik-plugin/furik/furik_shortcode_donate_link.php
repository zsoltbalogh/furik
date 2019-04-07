<?php
/**
 * WordPress shortcode: [furik_donate_link], paramters: amount and name.
 */
function furik_shortcode_donate_link( $atts ) {
	global $furik_donations_url;
    $a = shortcode_atts( array(
	'amount' => '5000',
	'name' => '',
    ), $atts );

    return furik_url($furik_donations_url, array('amount' => $atts['amount']));
}

add_shortcode( 'furik_donate_link', 'furik_shortcode_donate_link' );