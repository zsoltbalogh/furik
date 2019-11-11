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
Lists all the child campaigns. It can be configured with the `show` parameter which lists the type of data we should list and the order, comma separated. Available data types: `image`, `title`, `excerpt`. The `image` URL is taken from the `IMAGE` custom field of the campaign. Default value: `image,title,excerpt`.

### [furik_donate_form]
Displays the donation form. The `amount` is the default amount value in the form. If the `enable_cash` option is set to true, the cash donation option appears. If the `AMOUNT_CONTENT` custom field is set for the campaign or the parent campaign, it replaces the amount box. This field should contain a form field with the name `furik_form_amount`. If the `furik_form_amount` value is `other`, it will use the value of the `furik_form_amount_other` POST variable.

### [furik_donate_link amount=5000]
Prepares a link to the donations page and sets the default amount to the `amount` value. If this is put on a campaign page, the campaign information is included in the donation.

### [furik_donations]
Lists the donations.

### [furik_order_ref]
Displays the order reference if it's valid. Used on the bank transfer thank you pages.


### [furik_payment_info]
Provides information about the payment (date, referece ids), it's used on return page.

### [furik_progress]
Shows the percentage of the collected amount. The full amount can be specified with the "amount" variable, if it's not set, the full amount is shown. The goal of the campaign can be set in the `GOAL` custom field. CSS is required to show the progress bar, recommended CSS for a small red progress bar:

    .furik-progress-bar {
    	background-color: #aaaaaaa;
    	height: 20px;
    	padding: 5px;
    	width: 200px;
    	margin: 5px 0;
    	border-radius: 5px;
    	box-shadow: 0 1px 1px #444 inset, 0 1px 0 #888;
    	}
     
    .furik-progress-bar span {
    	display: inline-block;
    	float: left;
    	height: 100%;
    	border-radius: 3px;
    	box-shadow: 0 1px 0 rgba(255, 255, 255, .5) inset;
    	transition: width .4s ease-in-out;
    	overflow: hidden;
    	background-color: #D44236;
    	}