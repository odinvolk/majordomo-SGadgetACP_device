<?php
/* SGadgetMAP_faceDetected.php
* $message = $params['VALUE']; // Принимаем данные
*
* Camera Motion, Face, and QR Codes Detections
* В дополнение к публикации данных датчиков устройства, приложение также может публиковать состояния для обнаружения движения и распознавания лиц,
* а также данные из QR-кодов, полученных с камеры устройства.
* In addition to device sensor data publishing, the application can also publish states for Motion detection and Face detection,
* as well as the data from QR Codes, derived from the device camera.
*/
$state = json_decode($params['VALUE'], true); // Принимаем и Декодируем в массив
//$this->setProperty('message_face', $params['VALUE']); // Записываем сырые данные в свойство при необходимости

//------------------------------------------- Записываем данные в свойства из MQTT (alarmpanel/sensor/face)
// {"value": false}  {"value": true}  Публикуется сразу же при обнаружении лица
if ($state['value']) {
    $this->setProperty('sensorFace', '1');
} else {
    $this->setProperty('sensorFace', '0');
}

//Передаем в сенсор через MQTT (создаём сенсор в панеле с топиком /home/alarm/sensor/face)
$name = $this->getProperty('name');
if ($state['value']) { 
    $json = '1'; 
} else { 
    $json = '0'; 
}
$topic = $name. "/home/alarm/sensor/face";
mqttMob($topic, $json, 0, 0);
