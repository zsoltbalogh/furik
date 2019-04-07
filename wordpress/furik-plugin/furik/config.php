<?php
$furik_production_system = false;

$furik_payment_merchant = "";
$furik_payment_secret_key = "";

// Do NOT add the protocol here (only indahousehungary.hu)
$furik_homepage_url = $_SERVER['HTTP_HOST'];
$furik_homepage_https = false;

// Paths of prepared pages, relative to $furik_homepage_url
$furik_payment_successful_url = "payment-successful";
$furik_payment_unsuccessful_url = "payment-unsuccessful";
$furik_payment_timeout_url = "payment-unsuccessful";
$furik_donations_url = "tamogatas";

$furik_data_transmission_declaration_url = "data-transmission-declaration";

@include_once "config_local.php";
?>
