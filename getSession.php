<?php
$rawData = json_decode(file_get_contents('php://input'));

$url = "https://checkout-test.adyen.com/v68/sessions";

$curl = curl_init($url);
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

$headers = array(
   "content-type: application/json",
   "x-API-key: AQEyhmfxL4PJahZCw0m/n3Q5qf3VaY9UCJ1+XWZe9W27jmlZiv4PD4jhfNMofnLr2K5i8/0QwV1bDb7kfNy1WIxIIkxgBw==-lUKXT9IQ5GZ6d6RH4nnuOG4Bu//eJZxvoAOknIIddv4=-<anpTLkW{]ZgGy,7",
);

date_default_timezone_set('Asia/Tokyo');

$data = array(
   "reference"=>date("Ymt").'playground_sessionKlarna_'.time(),
   "merchantAccount"=>"KenjiW",
   //"shopperReference"=> "Shopper_Reference_0002",
   "amount"=>array("value"=>$rawData->amount,"currency"=>$rawData->currency),
   "countryCode"=>"DE",
   "shopperReference"=>"shopper001",
   "shopperEmail"=>"shopper@gmail.com",

   "lineItems"=> [
     [
       "quantity"=> "1",
     "taxPercentage"=> "2100",
     "description"=> "Shoes",
     "id"=> "Item #1",
     "amountIncludingTax"=> "400",
     "productUrl"=> "URL_TO_PURCHASED_ITEM",
     "imageUrl"=> "URL_TO_PICTURE_OF_PURCHASED_ITEM"
   ],
   [
     "quantity"=> "2",
     "taxPercentage"=> "2100",
     "description"=> "Socks",
     "id"=> "Item #2",
     "amountIncludingTax"=> "300",
     "productUrl"=> "URL_TO_PURCHASED_ITEM",
     "imageUrl"=> "URL_TO_PICTURE_OF_PURCHASED_ITEM"
   ],
   ],
   "additionalData"=>[
      "openinvoicedata.merchantData"=>"eyJjdXN0b21lcl9hY ..."
     ],
     "shopperIP"=>"103.57.72.0",

   "returnUrl"=>"http://localhost:8000/payments.php"
);
$postdata = json_encode($data);

curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
//for debug only!
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata);

$resp = curl_exec($curl);
curl_close($curl);

header("Content-Type: application/json");
print(($resp));
?>
