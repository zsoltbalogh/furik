<?php
/**
 * Plugin Name: Furik Donation Plugin
 */

function furik_func( $atts ) {
    $a = shortcode_atts( array(
	'amount' => '5000',
	'name' => 'támogasd az Alapítványt',
    ), $atts );

    return "Katt ide, hogy {$a['name']} {$a['amount']} forinttal!";
}
add_shortcode( 'furik', 'furik_func' );