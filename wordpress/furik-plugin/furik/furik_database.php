<?php
define("FURIK_STATUS_UNKNOWN", 0);
define("FURIK_STATUS_SUCCESSFUL", 1);
define("FURIK_STATUS_UNSUCCESSFUL", 2);

function furik_install() {
	global $wpdb;

	$table_name = $wpdb->prefix . 'furik_transactions';

	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		transaction_id varchar(100) NOT NULL,
		email varchar(255),
		amount int,
		transaction_status int,
		PRIMARY KEY  (id)
	) $charset_collate;";

	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($sql);

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