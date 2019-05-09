<?php
define("FURIK_STATUS_UNKNOWN", 0);
define("FURIK_STATUS_SUCCESSFUL", 1);
define("FURIK_STATUS_UNSUCCESSFUL", 2);
define("FURIK_STATUS_CANCELLED", 3);
define("FURIK_STATUS_TRANSFER_ADDED", 4);
define("FURIK_STATUS_CASH_ADDED", 5);
define("FURIK_STATUS_IPN_SUCCESSFUL", 10);
define("FURIK_STATUS_DISPLAYABLE", "1, 4, 5, 10");

define("FURIK_TRANSACTION_TYPE_SIMPLEPAY", 0);
define("FURIK_TRANSACTION_TYPE_TRANSFER", 1);
define("FURIK_TRANSACTION_TYPE_CASH", 2);

function furik_get_transaction($order_ref) {
	global $wpdb;

	return $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}furik_transactions WHERE transaction_id=%s", $order_ref));
}

function furik_get_post_id_from_order_ref($order_ref) {
	global $wpdb;

	return $wpdb->get_var($wpdb->prepare("SELECT campaign FROM {$wpdb->prefix}furik_transactions WHERE transaction_id=%s", $order_ref));
}

function furik_install() {
	global $wpdb;

	$charset_collate = $wpdb->get_charset_collate();

	$sql_transactions = "CREATE TABLE {$wpdb->prefix}furik_transactions (
		id int NOT NULL AUTO_INCREMENT,
		time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		transaction_id varchar(100) NOT NULL,
		transaction_type int DEFAULT 0,
		name varchar(255),
		anon int,
		email varchar(255),
		amount int,
		campaign int,
		message longtext,
		transaction_status int,
		vendor_ref varchar(255),
		PRIMARY KEY  (id)
	) $charset_collate;";

	$sql_transaction_log = "CREATE TABLE {$wpdb->prefix}furik_transaction_log (
		id int NOT NULL AUTO_INCREMENT,
		time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		transaction_id varchar(100) NOT NULL,
		message text,
		PRIMARY KEY (id)
	) $charset_collate;";

	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($sql_transactions);
	dbDelta($sql_transaction_log);

	add_option('furik_db_version', 1);
}

function furik_transaction_log($transaction_id, $message) {
	global $wpdb;

	$wpdb->insert(
		"{$wpdb->prefix}furik_transaction_log",
		array(
			'time' => current_time( 'mysql' ),
			'transaction_id' => $transaction_id,
			'message' => $message
		)
	);
}

function furik_update_transaction_status($order_ref, $status, $vendor_ref = "") {
	global $wpdb;

	$table_name = $wpdb->prefix . 'furik_transactions';
	$update = array("transaction_status" => $status);
	if ($vendor_ref) {
		$update["vendor_ref"] = $vendor_ref;
	}
	$wpdb->update(
		$table_name,
		$update,
		array("transaction_id" => $order_ref)
	);
}