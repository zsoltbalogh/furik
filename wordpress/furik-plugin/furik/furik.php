<?php
/**
 * Plugin Name: Furik Donation Plugin
 */
include_once "config.php";

include_once "furik_helper.php";
include_once "furik_localization.php";

include_once "furik_database.php";

include_once "furik_shortcode_back_to_campaign_url.php";
include_once "furik_shortcode_campaigns.php";
include_once "furik_shortcode_donate_form.php";
include_once "furik_shortcode_donate_link.php";
include_once "furik_shortcode_donations.php";
include_once "furik_shortcode_payment_info.php";
include_once "furik_shortcode_progress.php";

include_once "furik_payment_processing.php";

include_once "furik_admin_donations.php";
include_once "furik_campaigns.php";

register_activation_hook( __FILE__, 'furik_install' );