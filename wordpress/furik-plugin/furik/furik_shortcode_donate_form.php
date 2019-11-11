<?php
/**
 * WordPress shortcode: [furik_donate_form], paramters: amount.
 */
function furik_shortcode_donate_form( $atts ) {
	global $furik_data_transmission_declaration_url;
    $a = shortcode_atts( array(
	   'amount' => '5000',
       'skip_message' => false
    ), $atts );

    $amount = is_numeric($_GET['furik_amount']) ? $_GET['furik_amount'] : $atts['amount'];

    if (is_numeric($_GET['furik_campaign'])) {
        $post = get_post($_GET['furik_campaign']);
    }
    else {
        $post = get_post();
    }

    if ($post->post_type == 'campaign') {
        $campaign = $post->post_title;
        $campaign_id = $post->ID;
    }
    else {
        $campaign = __('General donation', 'furik');
    }

    $r = "<form method=\"POST\" action=\"".$_SERVER['REQUEST_URI']."\">";
    $r .= "<input type=\"hidden\" name=\"furik_action\" value=\"process_payment_form\" />";
    $r .= "<input type=\"hidden\" name=\"furik_campaign\" value=\"$campaign_id\" />";


    $r .= "<div class=\"form-field form-group form-required\">";
    $r .= "<label for=\"furik_form_campaign\">".__('Supported cause', 'furik').":</label>";
    $r .= "<input type=\"text\" id=\"furik_form_campaign\" class=\"form-control\" disabled=\"1\" value=\"" . htmlspecialchars($campaign) . "\"/>";
    $r .= "</div>";

    $r .= "<br />";

    $r .= "<div class=\"form-field form-group form-required\">";
    $r .= "<label for=\"furik_form_name\">".__('Your name', 'furik').":</label>";
    $r .= "<input type=\"text\" name=\"furik_form_name\" id=\"furik_form_name\" class=\"form-control\" required=\"1\"/>";
    $r .= "</div>";

    $r .= "<div class=\"form-field form-check\">";
    $r .= "<label for=\"furik_form_anon\" class=\"form-check-label\"><input type=\"checkbox\"  class=\"form-check-input\" name=\"furik_form_anon\" id=\"furik_form_anon\"> ".__('Anonymous donation', 'furik')."</label>";
    $r .= "</div>";

    $r .= "<br />";

    $r .= "<div class=\"form-field form-group form-required\">";
    $r .= "<label for=\"furik_form_email\">".__('E-mail address', 'furik').":</label>";
    $r .= "<input type=\"email\" class=\"form-control\" name=\"furik_form_email\" id=\"furik_form_email\" required=\"1\" />";
    $r .= "</div>";

    $r .= "<br />";

    $r .= "<div class=\"form-field form-group form-required\">";
    $r .= "<label for=\"furik_form_email\">".__('Donation amount', 'furik')." (Forint):</label>";
    $r .= "<input type=\"number\" class=\"form-control\" name=\"furik_form_amount\" id=\"furik_form_amount\" value=\"$amount\" required=\"1\" />";
    $r .= "</div>";

    $r .= "<br />";

    if (!$a['skip_message']) {
        $r .= "<div class=\"form-field form-group\">";
        $r .= "<label for=\"furik_form_message\">".__('Message', 'furik').":</label>";
        $r .= "<textarea class=\"form-control\" name=\"furik_form_message\" id=\"furik_form_message\"></textarea>";
        $r .= "</div>";

        $r .= "<br />";
    }

    $r .= "<div class=\"form-field form-group\">";
    $r .= "<label>" . __('Type of donation', 'furik') . "</label>";
    $r .= "<div>";
    $r .= "<div class=\"form-check form-check-inline\"><input type=\"radio\" id=\"furik_form_type_0\" class=\"form-check-input\" name=\"furik_form_type\" value=\"0\" checked=\"1\" /><label for=\"furik_form_type_0\" class=\"form-check-label\">".__('Online payment', 'furik')."</label></div>";
    $r .= "<div class=\"form-check form-check-inline\"><input type=\"radio\" id=\"furik_form_type_1\" class=\"form-check-input\" name=\"furik_form_type\" value=\"1\"><label for=\"furik_form_type_1\" class=\"form-check-label\">".__('Bank transfer', 'furik')."</label></div>";
    $r .= "<div class=\"form-check form-check-inline\"><input type=\"radio\" id=\"furik_form_type_2\" class=\"form-check-input\" name=\"furik_form_type\" value=\"2\"><label for=\"furik_form_type_2\" class=\"form-check-label\">".__('Cash donation', 'furik')."</label></div>";
    $r .= "</div>";
    $r .= "</div>";

    $r .= "<br />";

    $r .= "<div class=\"form-field form-check\">";
    $r .= "<label for=\"furik_form_accept\" class=\"form-check-label\"><input type=\"checkbox\" name=\"furik_form_accept\" id=\"furik_form_accept\" class=\"form-check-input\" required=\"1\"><a href=\"".furik_url($furik_data_transmission_declaration_url)."\" target=\"_blank\">".__('I accept the data transmission declaration', 'furik')."</a></label>";
    $r .= "</div>";

    $r .= "<br />";
    $r .= "<p class=\"submit\"><input type=\"submit\" class=\"button button-primary rounded-xl btn btn-primary\" value=\"".__('Donate', 'furik')."\" /></p>";
    $r .= "</form>";

    $r .= "<a href=\"http://simplepartner.hu/PaymentService/Fizetesi_tajekoztato.pdf\" target=\"_blank\"><img src=\"".furik_url("/wp-content/plugins/furik/images/simplepay.png")."\" title=\"SimplePay - Online bankkártyás fizetés\" alt=\"SimplePay vásárlói tájékoztató\"></a>";

    return $r;
}

add_shortcode( 'furik_donate_form', 'furik_shortcode_donate_form' );