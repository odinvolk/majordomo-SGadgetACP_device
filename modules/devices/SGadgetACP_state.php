<?php
/*
{"currentUrl": "","screenOn": true,"brightness": 255,"presence": "true"}
//пишите свой скрипт, по всем топикам вызывая функцию
//function mqttPublish($topic, $value, $qos = 0, $retain = 0);
//{"payload": "{'sun':'below_horizon'}", "topic": "alarmpanel/command"}{"dashboard": 0} //alarmpanel/state

$name=$this->getProperty('name');
//$alarmp=$this->getProperty('alarmpanel_command_dashboard');
//$status='{"noti":"Павлик дома","title":"Сообщение 2","info":"Павлик","vibrate":true,"sound":true,"light":true,"id":2}';
$status='{"currentUrl":"http://192.168.10.26/menu/526.html","screenOn":true}';

include_once(DIR_MODULES . 'mqtt/mqtt.class.php');
$mqtt = new mqtt();
//$topic = "alarmpanel/".$name."/state";
$topic = $name."/alarmpanel/state";
$rezult = $mqtt->mqttPublish($topic, $status, 0, 0);
*/
$state = json_decode($params['VALUE'], true);

foreach ($state as $k=>$v) {
//sg("mqtt_obj.$k", $v);
$this->setProperty('state_'.$k, $v);
}
