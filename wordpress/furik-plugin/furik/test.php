<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

 //Import config data    
require_once 'config.php';

//Import SimplePayment class
require_once 'patched_SimplePayment.class.php';

//Set merchant account data by currency
$orderCurrency = 'HUF';
$testOrderId = str_replace(array('.', ':'), "", $_SERVER['SERVER_ADDR']) . @date("U", time()) . rand(1000, 9999); 
      
//Start LiveUpdate
$lu = new SimpleLiveUpdate($config, $orderCurrency);     

//Order global data (need to fill by YOUR order data)    	
$lu->setField("ORDER_REF", $testOrderId);
$lu->setField("LANGUAGE", "HU");						//DEFAULT: HU

//optional fields
//$lu->setField("ORDER_DATE", @date("Y-m-d H:i:s"));		//DEFAULT: current date
//$lu->setField("ORDER_TIMEOUT", 600);						//DEFAULT: 300
//$lu->setField("PAY_METHOD", 'WIRE');						//DEFAULT: CCVISAMC
//$lu->setField("DISCOUNT", 10); 							//DEFAULT: 0
//$lu->setField("ORDER_SHIPPING", 70);						//DEFAULT: 0
//$lu->setField("BACK_REF", $config['BACK_REF']);			//DEFAULT: $config['BACK_REF']
//$lu->setField("TIMEOUT_URL", $config['TIMEOUT_URL']);		//DEFAULT: $config['TIMEOUT_URL']
//$lu->setField("LU_ENABLE_TOKEN", true);					//Only case of uniq contract with OTP Mobil Kft.! DO NOT USE WITHOUT IT!

//Sample product with gross price
$lu->addProduct(array(
    'name' => 'Adomány',                            		//product name [ string ]
    'code' => 'sku0001',                            		//merchant systemwide unique product ID [ string ]
    'info' => 'A házra',     				//product description [ string ]
    'price' => 1207,                              			//product price [ HUF: integer | EUR, USD decimal 0.00 ]
    'vat' => 0,                                     		//product tax rate [ in case of gross price: 0 ] (percent)
    'qty' => 1                                      		//product quantity [ integer ] 
));

//Billing data
$lu->setField("BILL_FNAME", "Tester");
$lu->setField("BILL_LNAME", "SimplePay");
$lu->setField("BILL_EMAIL", "sdk_test@otpmobil.com"); 
$lu->setField("BILL_PHONE", "36201234567");
//$lu->setField("BILL_COMPANY", "Company name");          	//optional
//$lu->setField("BILL_FISCALCODE", " ");                  	//optional
$lu->setField("BILL_COUNTRYCODE", "HU");
$lu->setField("BILL_STATE", "State");
$lu->setField("BILL_CITY", "City"); 
$lu->setField("BILL_ADDRESS", 'First line address'); 
//$lu->setField("BILL_ADDRESS2", "Second line address");    //optional
$lu->setField("BILL_ZIPCODE", "1234"); 

$display = $lu->createHtmlForm('SimplePayForm', 'button', "Adományozz!");   // format: link, button, auto (auto is redirects to payment page immediately )
$lu->errorLogger(); 
if ($lu->debug_liveupdate_page) {
    print "<pre>";
	print $lu->getDebugMessage();
	print "</pre>";
	exit; 		
}
if (count($lu->errorMessage) > 0) {
    print "<pre>";
	print $lu->getErrorMessage();
	print "</pre>";
	exit; 
} 
echo $display;


