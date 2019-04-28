<?php
/**
 * WordPress shortcode: [furik_form], paramters: amount.
 */
function furik_form_func( $atts ) {
	global $furik_data_transmission_declaration_url;
    $a = shortcode_atts( array(
	   'amount' => '5000',
    ), $atts );

    $amount = is_numeric($_GET['furik_amount']) ? $_GET['furik_amount'] : $atts['amount'];
    if (is_numeric($_GET['furik_campaign'])) {
        $post = get_post($_GET['furik_campaign']);
        $campaign = $post->post_title;
        $campaign_id = $post->ID;
    }
    else {
        $campaign = __('General donation', 'furik');
    }

    $r = "<form method=\"POST\" action=\"".$_SERVER['REQUEST_URI']."\">";
    $r .= "<input type=\"hidden\" name=\"furik_action\" value=\"process_payment_form\" />";
    $r .= "<input type=\"hidden\" name=\"furik_campaign\" value=\"$campaign_id\" />";


    $r .= "<div class=\"form-field form-required\">";
    $r .= "<label for=\"furik_form_campaign\">".__('Supported cause', 'furik').":</label>";
    $r .= "<input type=\"text\" id=\"furik_form_campaign\" disabled=\"1\" value=\"$campaign\"/>";
    $r .= "</div>";

    $r .= "<br />";

    $r .= "<div class=\"form-field form-required\">";
    $r .= "<label for=\"furik_form_name\">".__('Your name', 'furik').":</label>";
    $r .= "<input type=\"text\" name=\"furik_form_name\" id=\"furik_form_name\" required=\"1\"/>";
    $r .= "</div>";

    $r .= "<div class=\"form-field\">";
    $r .= "<label for=\"furik_form_anon\"><input type=\"checkbox\" name=\"furik_form_anon\" id=\"furik_form_anon\">".__('Anonymous donation', 'furik')."</label>";
    $r .= "</div>";

    $r .= "<br />";

    $r .= "<div class=\"form-field form-required\">";
    $r .= "<label for=\"furik_form_email\">".__('E-mail address', 'furik').":</label>";
    $r .= "<input type=\"email\" name=\"furik_form_email\" id=\"furik_form_email\" required=\"1\" />";
    $r .= "</div>";

    $r .= "<br />";

    $r .= "<div class=\"form-field form-required\">";
    $r .= "<label for=\"furik_form_email\">".__('Donation amount', 'furik')." (Forint):</label>";
    $r .= "<input type=\"number\" name=\"furik_form_amount\" id=\"furik_form_amount\" value=\"$amount\" required=\"1\" />";
    $r .= "</div>";

    $r .= "<br />";

    $r .= "<div class=\"form-field\">";
    $r .= "<label for=\"furik_form_message\">".__('Message', 'furik').":</label>";
    $r .= "<textarea name=\"furik_form_message\" id=\"furik_form_message\"></textarea>";
    $r .= "</div>";

    $r .= "<br />";

    $r .= "<div class=\"form-field\">";
    $r .= __('Type of donation', 'furik') . ": <br />";
    $r .= "<label for=\"furik_form_type_0\"><input type=\"radio\" id=\"furik_form_type_0\" name=\"furik_form_type\" value=\"0\" checked=\"1\">".__('Online payment', 'furik')."</label>";
    $r .= "<label for=\"furik_form_type_1\"><input type=\"radio\" id=\"furik_form_type_1\" name=\"furik_form_type\" value=\"1\">".__('Bank transfer', 'furik')."</label>";
    $r .= "<label for=\"furik_form_type_2\"><input type=\"radio\" id=\"furik_form_type_2\" name=\"furik_form_type\" value=\"2\">".__('Cash donation', 'furik')."</label>";
    $r .= "</div>";

    $r .= "<br />";

    $r .= "<div class=\"form-field\">";
    $r .= "<label for=\"furik_form_accept\"><input type=\"checkbox\" name=\"furik_form_accept\" id=\"furik_form_accept\" required=\"1\"><a href=\"".furik_url($furik_data_transmission_declaration_url)."\" target=\"_blank\">".__('I accept the data transmission declaration', 'furik')."</a></label>";
    $r .= "</div>";

    $r .= "<br />";
    $r .= "<p class=\"submit\"><input type=\"submit\" class=\"button button-primary\" value=\"".__('Donate', 'furik')."\" /></p>";
    $r .= "</form>";

    $r .= "<a href=\"http://simplepartner.hu/PaymentService/Fizetesi_tajekoztato.pdf\" target=\"_blank\"><img src=\"".furik_url("/wp-content/plugins/furik/images/simplepay.png")."\" title=\"SimplePay - Online bankkártyás fizetés\" alt=\"SimplePay vásárlói tájékoztató\"></a>";

    return $r;
}

add_shortcode( 'furik_form', 'furik_form_func' );