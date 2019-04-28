# Furik wordpress module

## Payment settings
Please review config.php for payment settings. Only override configuration in
config_local.php.

On the SimplePay admin site the IPN URL field needs to point to the main URL of
the wordpress site + the `furik_process_ipn=1` request parameter. Example:

    https://indahousehungary.hu/?furik_process_ipn=1

## Shortcodes

### [furik_back_to_campaign_url]
Provides an URL back to the campaign which received the payment. It requires the campaign_id variable set in the request.

### [furik_campaigns]
Lists all the child campaigns.

### [furik_donate_link amount=5000]
Prepares a link to the donations page and sets the default amount to the `amount` value. If this is put on a campaign page, the campaign information is included in the donation.

### [furik_donations]
Lists the donations.

### [furik_form amount=5000]
Prepares a donation form with the provided `amount` value as default donation amount.

### [furik_payment_info]
Provides information about the payment (date, referece ids), it's used on return pages.