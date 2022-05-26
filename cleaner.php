<?php
	$timemax = 86400;
	$time = time();

	botSend([
		'<b>[CLEANER]</b> <i>Скрипт начал свою работу</i>',
	], chatAlerts());

	for ($i = 0; $i < 2; $i++) {
		$isnt = ($i == 0);
		foreach (glob(($isnt ? 'items' : 'tracks').'/*') as $t) {
			$item = explode('.', basename($t))[0];
			$itemd = getItemData($item, $isnt);
			if ($time - $itemd[4] > $timemax) {
				$id = $itemd[3];
				delUserItem($id, $item, $isnt);
				botSend([
					'❗️ Ваш'.($isnt ? 'е объявление' : ' трек номер').' <b>'.$item.'</b> удален'.($isnt ? 'о' : '').' автоматически из-за неактуальности',
				], $id);
				botSend([
					'🗑 '.($isnt ? 'Объявление' : 'Трек номер').' <b>'.$item.'</b> <b>'.userLogin($id, true, true).'</b> было удалено автоматически из-за неактуальности',
				], chatAlerts());
			}
		}
	}

	for ($i = 0; $i < 2; $i++) {
		$isnt = '2';
		foreach (glob(('rent').'/*') as $t) {
			$item = explode('.', basename($t))[0];
			$itemd = getItemData($item, $isnt);
			if ($time - $itemd[4] > $timemax) {
				$id = $itemd[3];
				delUserItem($id, $item, $isnt);
				botSend([
					'❗️ Ваша аренда <b>'.$item.'</b> удалена автоматически из-за неактуальности',
				], $id);
				botSend([
					'🗑 Аренда <b>'.$item.'</b> <b>'.userLogin($id, true, true).'</b> было удалено автоматически из-за неактуальности',
				], chatAlerts());
			}
		}
	}
	
	for ($i = 0; $i < 2; $i++) {
		$isnt = '3';
		foreach (glob(('taxi').'/*') as $t) {
			$item = explode('.', basename($t))[0];
			$itemd = getItemData($item, $isnt);
			if ($time - $itemd[4] > $timemax) {
				$id = $itemd[3];
				delUserItem($id, $item, $isnt);
				botSend([
					'❗️ Ваша поездка <b>'.$item.'</b> удалена автоматически из-за неактуальности',
				], $id);
				botSend([
					'🗑 Поездка <b>'.$item.'</b> <b>'.userLogin($id, true, true).'</b> было удалено автоматически из-за неактуальности',
				], chatAlerts());
			}
		}
	}

	for ($i = 0; $i < 2; $i++) {
		$isnt = '4';
		foreach (glob(('bank_item').'/*') as $t) {
			$item = explode('.', basename($t))[0];
			$itemd = getItemData($item, $isnt);
			if ($time - $itemd[4] > $timemax) {
				$id = $itemd[3];
				delUserItem($id, $item, $isnt);
				botSend([
					'❗️ Ваш чек <b>'.$item.'</b> удален автоматически из-за неактуальности',
				], $id);
				botSend([
					'🗑 Чек <b>'.$item.'</b> <b>'.userLogin($id, true, true).'</b> было удалено автоматически из-за неактуальности',
				], chatAlerts());
			}
		}
	}

	botSend([
		'<b>[CLEANER]</b> <i>Скрипт закончил свою работу</i>',
	], chatAlerts());
?>