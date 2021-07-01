<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class PaymentController extends Controller
{
    public function create()
    {
        return view('payments.create_payment');
    }
    public function result(Request $request) {
        $jccMerID = $request['MerID'];
        $jccAcqID = $request['AcqID'];
        $jccOrderID = $request['OrderID'];
        $jccResponseCode = intval($request['ResponseCode']);
        $jccReasonCode = intval($request['ReasonCode']);
        $jccReasonDescr =$request['ReasonCodeDesc'];
        $jccRef = $request['ReferenceNo'];
        $jccPaddedCardNo =$request['PaddedCardNo'];
        $jccSignature = $request['ResponseSignature'];
        if ($jccResponseCode==1 && $jccReasonCode==1){
            $jccAuthNo = $request['AuthCode'];
        }
        $password = "gjN5s2m0";
        $merchantID = "0095422010";
        $acquirerID = "402971";
        $orderID = "TestOrder123456";
        $toEncrypt = $password.$merchantID.$acquirerID.$orderID.$ResponseCode.$ReasonCode;
        $sha1Signature = sha1($toEncrypt);
        $expectedBase64Sha1Signature = base64_encode(pack("H*",$sha1Signature));
        $verifyJCCSignature = ($expectedBase64Sha1Signature == $jccSignature);
        return view('payments.payment_response', compact('jccMerID', 'jccAcqID', 'jccOrderID',
         'jccResponseCode', 'jccReasonCode', 'jccReasonDescr', 'jccRef', 'jccPaddedCardNo', 
         'jccSignature', 'verifyJCCSignature','jccAuthNo'));
    }

    public function pay() {
        
        //Version
        $version = "1.0.0";
        //Merchant ID
        $merchantID = "0095422010";
        //Acquirer ID
        $acquirerID = "402971";
        //The SSL secured URL of the merchant to which JCC will send the transaction
        //result //This should be SSL enabled – note https:// NOT http://
        $responseURL = "https://6fa27215cd7b.ngrok.io/paymentresult";
        //Purchase Amount
        $purchaseAmt = "20.50";
        //Pad the purchase amount with 0's so that the total length is 13 characters, i.e. 20.50 will
        //become 0000000020.50
        $purchaseAmt = str_pad($purchaseAmt, 13, "0", STR_PAD_LEFT);
        //Remove the dot (.) from the padded purchase amount(JCC will know from currency how many digits
        //to consider as decimal)
        //0000000020.50 will become 000000002050 (notice there is no dot)
        $formattedPurchaseAmt = substr($purchaseAmt,0,10).substr($purchaseAmt,11);
        //Euro currency ISO Code; see relevant appendix for ISO codes of other
        //currencies 
        $currency = 978;
        //The number of decimal points
        $currencyExp = 2;
        //Order number
        $orderID = "TestOrder12345";
        //Specify we want not only to authorize the amount but also capture at the same time. Alternative
        //value could be M (for capturing later)
        $captureFlag = "A";
        //Password
        $password = "gjN5s2m0";
        //Form the plaintext string to encrypt by concatenating Password, Merchant ID, Acquirer ID, Order
        //ID, Formatter Purchase Amount and Currency
        //This will give 1234abcd | 0011223344 | 402971 | TestOrder12345 | 000000002050 | 978 (spaces
        //and | introduced here for clarity)
        $toEncrypt = $password.$merchantID.$acquirerID.$orderID.$formattedPurchaseAmt.$currency;
        Log::info($toEncrypt);
        //Produce the hash using SHA1
        //This will give b14dcc7842a53f1ec7a621e77c106dfbe8283779
        $sha1Signature = sha1($toEncrypt);
        //Encode the signature using Base64 before transmitting to JCC
        //This will give sU3MeEKlPx7HpiHnfBBt++goN3k=
        $base64Sha1Signature = base64_encode(pack("H*",$sha1Signature));
        Log::info($base64Sha1Signature);
        //The name of the hash algorithm use to create the signature; can be MD5 or SHA1; the latter is
        //preffered and is what we used in this example
        $signatureMethod = "SHA1";
        $first_name = 'abdallah'; 
        $last_name = 'ahmadi'; 
        $url = "https://tjccpg.jccsecure.com/EcomPayment/RedirectAuthLink";
        $data = array(
            'Version' => $version,
            'MerID' => $merchantID,
            'AcqID' => $acquirerID,
            'MerRespURL' => $responseURL,
            'PurchaseAmt' =>  $formattedPurchaseAmt,
            'PurchaseCurrency' => $currency,
            'PurchaseCurrencyExponent' => $currencyExp,
            'OrderID' => $orderID,
            'CaptureFlag' => $captureFlag,
            'Signature' => $base64Sha1Signature,
            'SignatureMethod' => $signatureMethod,
            'billFirstName' => $first_name, 
            'billLastName' => $last_name, 

        );
        // $response = Http::asForm()->post($url, $data)->withOptions(['verify'=> false]);
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_SSL_VERIFYPEER => 1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_FAILONERROR => true,
            CURLOPT_POSTFIELDS => $data,
        ));
        $response = curl_exec($curl);
        if (curl_error($curl)) {
            Log::info(curl_error($curl));
        } else {
            Log::info('no curl error');
        }
        curl_close($curl);
        Log::info($response);
        if (empty($response)) {
            Log::info('empty');
        } else {
            Log::info('empty');
        }
        return $response;
        
    }
}
