<?php
function furik_form_func( $atts ) {
	global $furik_wordpress_url;
    $a = shortcode_atts( array(
	'amount' => '5000',
	'name' => 'támogasd az Alapítványt',
    ), $atts );
    $r = "<form method=\"POST\" action=\"".$_SERVER['REQUEST_URI']."\">";
    $r .= "<input type=\"hidden\" name=\"furik_action\" value=\"redirect\" />";

    $r .= "<div class=\"form-field form-required\">";
    $r .= "<label for=\"furik_form_email\">E-mailcímed:</label>";
    $r .= "<input type=\"text\" name=\"furik_form_email\" id=\"furik_form_email\" />";
    $r .= "</div>";

    $r .= "<br />";

    $r .= "<div class=\"form-field form-required\">";
    $r .= "<label for=\"furik_form_email\">Az adomány összege:</label>";
    $r .= "<input type=\"text\" name=\"furik_form_amount\" id=\"furik_form_amount\" value=\"" . $atts['amount'] ."\"/>";
    $r .= "</div>";

    $r .= "<br />";
    $r .= "<p class=\"submit\"><input type=\"submit\" class=\"button button-primary\" value=\"Online támogatás\" /></p>";
    $r .= "</form>";

    return $r;
}

add_shortcode( 'furik_form', 'furik_form_func' );