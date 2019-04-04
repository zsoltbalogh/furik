<?php
/**
 * WordPress shortcode: [furik_form], paramters: amount and name. 
 */
function furik_form_func( $atts ) {
	global $furik_wordpress_url, $furik_data_transmission_declaration_url;
    $a = shortcode_atts( array(
	'amount' => '5000',
	'name' => 'támogasd az Alapítványt',
    ), $atts );
    $r = "<form method=\"POST\" action=\"".$_SERVER['REQUEST_URI']."\">";
    $r .= "<input type=\"hidden\" name=\"furik_action\" value=\"redirect\" />";

    $r .= "<div class=\"form-field form-required\">";
    $r .= "<label for=\"furik_form_email\">Neved:</label>";
    $r .= "<input type=\"text\" name=\"furik_form_name\" id=\"furik_form_name\" required=\"1\"/>";
    $r .= "</div>";


    $r .= "<div class=\"form-field\">";
    $r .= "<label for=\"furik_form_anon\"><input type=\"checkbox\" name=\"furik_form_anon\" id=\"furik_form_anon\">Szeretnék publikusan névtelen maradni</label>";
    $r .= "</div>";

    $r .= "<br />";

    $r .= "<div class=\"form-field form-required\">";
    $r .= "<label for=\"furik_form_email\">E-mail címed:</label>";
    $r .= "<input type=\"email\" name=\"furik_form_email\" id=\"furik_form_email\" required=\"1\" />";
    $r .= "</div>";

    $r .= "<br />";

    $r .= "<div class=\"form-field form-required\">";
    $r .= "<label for=\"furik_form_email\">Az adomány összege:</label>";
    $r .= "<input type=\"number\" name=\"furik_form_amount\" id=\"furik_form_amount\" value=\"" . $atts['amount'] ."\" required=\"1\" />";
    $r .= "</div>";

    $r .= "<br />";

    $r .= "<div class=\"form-field\">";
    $r .= "<label for=\"furik_form_message\">Üzenet:</label>";
    $r .= "<textarea name=\"furik_form_message\" id=\"furik_form_message\"></textarea>";
    $r .= "</div>";

    $r .= "<br />";

    $r .= "<div class=\"form-field\">";
    $r .= "<label for=\"furik_form_accept\"><input type=\"checkbox\" name=\"furik_form_accept\" id=\"furik_form_accept\" required=\"1\">Az <a href=\"".$furik_wordpress_url.$furik_data_transmission_declaration_url."\" target=\"_blank\">adatkezelési és adattovábbítási nyilatkozatot</a> elfogadom</label>";
    $r .= "</div>";

    $r .= "<br />";
    $r .= "<p class=\"submit\"><input type=\"submit\" class=\"button button-primary\" value=\"Online támogatás\" /></p>";
    $r .= "</form>";

    return $r;
}

add_shortcode( 'furik_form', 'furik_form_func' );