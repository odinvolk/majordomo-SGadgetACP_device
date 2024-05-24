<?php
/* SGadgetMAP_motionDetected.php
* $message = $params['VALUE']; // Принимаем данные
*
* Camera Motion, Face, and QR Codes Detections
* В дополнение к публикации данных датчиков устройства, приложение также может публиковать состояния для обнаружения движения и распознавания лиц,
* а также данные из QR-кодов, полученных с камеры устройства.
* In addition to device sensor data publishing, the application can also publish states for Motion detection and Face detection,
* as well as the data from QR Codes, derived from the device camera.
*/
$state = json_decode($params['VALUE'], true); // Принимаем и Декодируем в массив
//$this->setProperty('message_motion', $params['VALUE']); // Записываем сырые данные в свойство при необходимости

//------------------------------------------- Записываем основные данные в свойства из MQTT alarmpanel/sensor/motion
// {"value": false}    Published immediately when face detected Публикуется сразу же при обнаружении лица
if ($state['value']) {
    $this->setProperty('sensorMotion', '1');
    $json = '1';
} else {
    $this->setProperty('sensorMotion', '0');
    $json = '0';
}

// Передаем в сенсор через MQTT (создаём сенсор в панеле с топиком /home/alarm/sensor/motion)
$name = $this->getProperty('name');
$topic = $name. "/home/alarm/sensor/motion";
mqttMob($topic, $json, 0, 0);

$ot = $this->object_title;
if (!isset($params['statusUpdated'])) {
    setTimeout($ot . '_motion_timer_status', '', 3);
}

if (isset($params['VALUE']) && !$params['VALUE'] && !isset($params['statusUpdated'])) {
    $this->setProperty('status', 0);
    return;
}

$motion_timeout = $this->getProperty('timeout'); // seconds timeout
if (!$motion_timeout) {
    $motion_timeout = 20; // timeout by default
}

if (!isset($params['statusUpdated'])) {
    $this->setProperty('status', 1);
}

setTimeout($ot . '_motion_timer', 'setGlobal("' . $ot . '.status", 0);', $motion_timeout);
$is_blocked = (int)$this->getProperty('blocked');

if ($is_blocked) {
    return;
}
