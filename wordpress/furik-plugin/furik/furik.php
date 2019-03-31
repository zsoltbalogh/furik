<?php
/**
 * Plugin Name: Furik Donation Plugin
 */
include_once "config.php";

include_once "furik_database.php";
include_once "furik_shortcode_form.php";

include_once "furik_payment_processing.php";

include_once "furik_admin_donations.php";
include_once "furik_admin_campaign_groups.php";

register_activation_hook( __FILE__, 'furik_install' );