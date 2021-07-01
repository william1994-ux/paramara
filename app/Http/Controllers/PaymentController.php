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
        
        $merchantID = "0095422010";
        $acquirerID = "402971";
        $purchaseAmt = "20.50";
        $formattedPurchaseAmt = substr($purchaseAmt,0,10).substr($purchaseAmt,11);
        $orderID = "TestOrder123456";
        $captureFlag = "A";
        $password = "gjN5s2m0";
        $currency = 978;
        $currencyExp = 2;
        $toEncrypt = $password.$merchantID.$acquirerID.$orderID.$formattedPurchaseAmt.$currency;
        $sha1Signature = sha1($toEncrypt);
        $base64Sha1Signature = base64_encode(pack("H*",$sha1Signature));
        $signatureMethod = "SHA1";
        $url = "https://tjccpg.jccsecure.com/EcomPayment/RedirectAuthLink";
        $data = array(
            'version' => "1.0.0",
            'merchantID' => "0095422010",
            'acquirerID' => "402971",
            'responseURL' => "https://df9daf247271.ngrok.io/paymentresult",
            'purchaseAmt' =>  str_pad($purchaseAmt, 13, "0", STR_PAD_LEFT),
            'formattedPurchaseAmt' => substr($purchaseAmt,0,10).substr($purchaseAmt,11),
            'currencyExp' => 2,
            'orderID' => 'TestOrder123456',
            'captureFlag' => 'A',
            'password' => "gjN5s2m0",
            'toEncrypt' => $password.$merchantID.$acquirerID.$orderID.$formattedPurchaseAmt.$currency,
            'sha1Signature' => sha1($toEncrypt),
            'base64Sha1Signature' => base64_encode(pack("H*",$sha1Signature)),
            'signatureMethod' => "SHA1",
            'billFirstName' => "Abdallah", 
            'billLastName' => "Ahmadi", 
            'billEmail' => "abdallah@pixel38.com"


        );
        $response = Http::asForm()->post($url, $data)->withOptions(['verify' => true]);
        // $curl = curl_init();
        // curl_setopt_array($curl, array(
        //     CURLOPT_URL => $url,
        //     CURLOPT_RETURNTRANSFER => true,
        //     CURLOPT_ENCODING => '',
        //     CURLOPT_MAXREDIRS => 10,
        //     CURLOPT_TIMEOUT => 0,
        //     CURLOPT_FOLLOWLOCATION => true,
        //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //     CURLOPT_CUSTOMREQUEST => 'POST',
        //     CURLOPT_FAILONERROR => true,
        //     CURLOPT_POSTFIELDS => $data,
        // ));
        // $response = curl_exec($curl);
        // if (curl_error($curl)) {
        //     Log::info(curl_error($curl));
        // } else {
        //     Log::info('no curl error');
        // }
        // curl_close($curl);
        // Log::info($response);
        // if (empty($response)) {
        //     Log::info('empty');
        // } else {
        //     Log::info('empty');
        // }
        return $response;
        
    }
}
