<?php
define("FURIK_STATUS_UNKNOWN", 0);
define("FURIK_STATUS_SUCCESSFUL", 1);
define("FURIK_STATUS_UNSUCCESSFUL", 2);
define("FURIK_STATUS_IPN_SUCCESSFUL", 10);

function furik_install() {
	global $wpdb;

	$charset_collate = $wpdb->get_charset_collate();

	$sql_transactions = "CREATE TABLE {$wpdb->prefix}furik_transactions (
		id int NOT NULL AUTO_INCREMENT,
		time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		transaction_id varchar(100) NOT NULL,
		name varchar(255),
		anon int,
		email varchar(255),
		amount int,
		campaign int,
		message longtext,
		transaction_status int,
		PRIMARY KEY  (id)
	) $charset_collate;";

	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($sql_transactions);

	add_option('furik_db_version', 1);
}

function furik_update_transaction_status($order_ref, $status) {
	global $wpdb;

	$table_name = $wpdb->prefix . 'furik_transactions';
	$wpdb->update(
		$table_name,
		array("transaction_status" => $status),
		array("transaction_id" => $order_ref)
	);
}