<?php
/* SGadgetMAP_qrcodeDetected.php
* $state = $params['VALUE']; // Принимаем данные
*
* Camera Motion, Face, and QR Codes Detections
* В дополнение к публикации данных датчиков устройства, приложение также может публиковать состояния для обнаружения движения и распознавания лиц,
* а также данные из QR-кодов, полученных с камеры устройства.
*/
$state = json_decode($params['VALUE'], true); // Принимаем и Декодируем в массив
//$this->setProperty('message_qrcode', $params['VALUE']); // Записываем сырые данные в свойство при необходимости

//------------------------------------------- Записываем данные в свойства из MQTT alarmpanel/sensor/qrcode
  // {"value": data}   Публикуется сразу после сканирования QR-кода
$this->setProperty('sensorQrcode', $state['value']);

if ($state['value']) { 
 getURL('/popup/app_qrcodes.html?qr='.urlencode($state['value']));
}
