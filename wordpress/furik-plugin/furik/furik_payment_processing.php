<?php
function furik_process_payment() {
	require "config.php";
	require_once 'patched_SimplePayment.class.php';

	$backref = new SimpleBackRef($config, "HUF");
	$backref->order_ref = (isset($_REQUEST['order_ref'])) ? $_REQUEST['order_ref'] : 'N/A';

	if ($backref->checkResponse()){
		furik_update_status($backref->order_ref, FURIK_STATUS_SUCCESSFUL);
		echo "Sikeres" . $backref->order_ref;
	}
	else {
		furik_update_status($backref->order_ref, FURIK_STATUS_UNSUCCESSFUL);
		echo "Sikertelen.";
	}
	die();
}

function furik_redirect() {
	global $wpdb;

	require "config.php";
	require_once 'patched_SimplePayment.class.php';

	$amount = is_numeric($_POST['furik_form_amount']) && $_POST['furik_form_amount'] > 0 ? $_POST['furik_form_amount'] : die("Error: amount is not a number.");
	$email = $_POST['furik_form_email'];

	$orderCurrency = 'HUF';
	$transactionId = str_replace(array('.', ':'), "", $_SERVER['SERVER_ADDR']) . @date("U", time()) . rand(1000, 9999);
	$lu = new SimpleLiveUpdate($config, $orderCurrency);
	$lu->setField("ORDER_REF", $transactionId);
	$lu->setField("LANGUAGE", "HU");
	$lu->addProduct(array(
	    'name' => 'Adomány',
	    'code' => 'sku0001',
	    'info' => 'Az alapítvány támogatása',
	    'price' => $amount,
	    'vat' => 0,
	    'qty' => 1
	));
	$lu->setField("BILL_EMAIL", "sdk_test@otpmobil.com"); 
	$display = $lu->createHtmlForm('SimplePayForm', 'auto', "Átirányítás a SimplePay oldalára");
	echo $display;

	$table_name = $wpdb->prefix . 'furik_transactions';

	$wpdb->insert(
		$table_name,
		array(
			'time' => current_time( 'mysql' ),
			'transaction_id' => $transactionId,
			'email' => $email,
			'amount' => $amount
		)
	);
	die("Redirecting to Simple Pay");
}

if ($_POST['furik_action'] == "redirect") {
	furik_redirect();
}

if (isset($_GET['order_ref'])) {
	furik_process_payment();
}

