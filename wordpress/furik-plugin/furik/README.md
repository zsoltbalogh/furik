# Furik wordpress module

## Payment settings
Please review config.php for payment settings. Only override configuration in
config_local.php.

On the SimplePay admin site the IPN URL field needs to point to the main URL of
the wordpress site + the `furik_process_ipn=1` request parameter. Example:

    https://indahousehungary.hu/?furik_process_ipn=1


