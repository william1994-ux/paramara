<html>
<head><title>JCC Response</title></head>
<body>
<!--
Show the response from JCC and the result of response signature verification.
Usually a merchant will need to manipulate this data and present it in a format
appropriate for his system
-->
<table>
<tr>
<td>Merchant ID:</td>
<td><?= $jccMerID?></td>
</tr>
<tr>
<td>Acquirer ID:</td>
<td><?= $jccAcqID?></td>
</tr>
<tr>
<td>Order ID:</td>
<td><?= $jccOrderID?></td>
</tr>
<tr>
<td>Response Code:</td>
<td><?= $jccResponseCode?></td>
</tr>
<tr>
<td>Reason Code:</td>
<td><?= $jccReasonCode?></td>
</tr>
<tr>
<td>Reason Code Description:</td>
<td><?= $jccReasonDescr?></td>
</tr>
<tr>
<td>Reference Number:</td>
<td><?= $jccRef?></td>
</tr>
<tr>
<td>Padded Card Number:</td>
<td><?= $jccPaddedCardNo?></td>
</tr>
<tr>
<td>Response Signature:</td>
<td><?= $jccSignature?></td>
</tr>
<tr>
<td>Signature Verified:</td>
<td><?= $verifyJCCSignature?></td>
</tr>
<!-- Show the authorization code only in case of successful transaction
--> <?php
if ($jccResponseCode==1 && $jccReasonCode==1){
?>

<tr>
<td> Success </td>
<td>Authorization Code:</td>
<td><?= $jccAuthNo?></td>
</tr>
<?php
}
?>
</table>
</body>
</html>
