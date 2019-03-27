<?php
/**
 * Plugin Name: Furik Donation Plugin
 */

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

function furik_process_simple_workflow_elements() {
	if ($_POST['furik_action'] == "redirect") {
		furik_redirect();
	}
}

function furik_redirect() {
	require "config.php";
	require_once 'patched_SimplePayment.class.php';

	$amount = is_numeric($_POST['furik_form_amount']) && $_POST['furik_form_amount'] > 0 ? $_POST['furik_form_amount'] : die("Error: amount is not a number.");

	$orderCurrency = 'HUF';
	$testOrderId = str_replace(array('.', ':'), "", $_SERVER['SERVER_ADDR']) . @date("U", time()) . rand(1000, 9999);
	$lu = new SimpleLiveUpdate($config, $orderCurrency);
	$lu->setField("ORDER_REF", $testOrderId);
	$lu->setField("LANGUAGE", "HU");
	$lu->addProduct(array(
	    'name' => 'Adomány',
	    'code' => 'sku0001',
	    'info' => 'A házra',
	    'price' => $amount,
	    'vat' => 0,
	    'qty' => 1
	));
	$lu->setField("BILL_EMAIL", "sdk_test@otpmobil.com"); 
	$display = $lu->createHtmlForm('SimplePayForm', 'auto', "Átirányítás a SimplePay oldalára");
	echo $display;
	die("Redirecting to Simple Pay");
}

add_shortcode( 'furik_form', 'furik_form_func' );
furik_process_simple_workflow_elements();