<?php
/*
//$message = $params['VALUE']; // Принимаем данные
*/
$state = json_decode($params['VALUE'], true); // Принимаем и Декодируем в массив
//$this->setProperty('message_battery', $params['VALUE']); // Записываем сырые данные в свойство при необходимости

//этот цикл раскидает всё по свойствам
//foreach ($state as $k=>$v) { // Записываем циклом
//$this->setProperty('battery_'.$k, $v);
//}

//  {"value": 84,"unit": "%","charging": true,"acPlugged": true,"usbPlugged": false}
//------------------------------------------- Записываем основные данные в свойства из MQTT
$this->setProperty('batteryLevel', $state['value']);
$this->setProperty('batteryCharging', $state['charging']);
$this->setProperty('acPlugged', $state['acPlugged']);
$this->setProperty('usbPlugged', $state['usbPlugged']);
//$this->setProperty('batteryUnit', $state['unit']);
