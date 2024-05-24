<?php
/* SGadgetMAP_event.php
* Поддерживаемые команды и раздел команд:
* Панель сигнализации может отправлять на сервер команды для различных состояний сигнализации.
* Брокер MQTT прослушивает эти команды, чтобы обновить состояние тревоги.
* Тема команды по умолчанию - home/alarm/set, которую можно изменить в настройках.
* Вот список команд, отправляемых из приложения брокеру MQTT.
*   { "event": "invalid_code_provided" }
*   { "event": "arm_away" }
*   { "event": "arm_away","delay": 60 } # exit delay
*   { "state": "pending","delay": 60 } # disarm time
*/
$ot = $this->object_title;
$desc = $this->description;

if (!isset($params['NEW_VALUE'])) { 
    $payload = $params['VALUE'];
} else {
    $payload = $params['NEW_VALUE'];
}

switch ($payload) { // Принимаем SWM07E/home/alarm/event
    case 'invalid_code_provided': // Неверный код для отключения или постановки на охрану сигнализации
        $status = '{ "event": "invalid_code_provided" }' ;
        break;
    case 'no_code_provided': // Код для снятия или постановки на охрану сигнализации требуется, но отсутствует
        $status = '{ "event": "no_code_provided" }' ;
        break;
    case 'failed_to_arm': // Сигнализация не могла быть включена из-за разомкнутых датчиков
        $status = '{ "event": "failed_to_arm" }' ;
        break;
    case 'system_disabled': // The alarm state could not be changed, because the system was unresponsive or unavailable
        $status = '{ "event": "system_disabled" }' ;
        break;
    case 'unknown': // Состояние тревоги не удалось изменить из-за неизвестной ошибки, проверьте настройки
        $status = '{ "event": "unknown" }' ;
        break;
    case 'arm_away': // Состояние тревоги не удалось изменить из-за неизвестной ошибки, проверьте настройки
        $status = '{ "event": "arm_away" }' ;
        break;
    case 'pending': // Состояние тревоги не удалось изменить из-за неизвестной ошибки, проверьте настройки
        $status = '{ "event": "pending","delay": 60 }'; // # disarm time
        break;
    case 'ARM_AWAY': // Используется для синхронизации команды тревоги между несколькими устройствами или с удаленным сервером
        $status = '{ "event": "ARM_AWAY" }' ;
        break;
    case 'ARM_HOME': // Используется для синхронизации команды тревоги между несколькими устройствами или с удаленным сервером
        $status = '{ "event": "ARM_HOME" }' ;
        break;
    case 'ARM_NIGHT': // Используется для синхронизации команды тревоги между несколькими устройствами или с удаленным сервером
        $status = '{ "event": "ARM_NIGHT" }' ;
        break;
    case 'ARM_CUSTOM_BYPASS': // Используется для синхронизации команды тревоги между несколькими устройствами или с удаленным сервером
        $status = '{ "event": "ARM_CUSTOM_BYPASS" }' ;
        break;
    default: //say('Сообщение не распознано', 0);
        //$this->result = array('status' => 'не распознано', 'date' => date('Y/m/d H:i:s', time()));
        //DebMes('Сообщение - '.$state['event'].' - от - '.$state['deviceId'].' - '.$this->result['status'].' - '.$this->result['date']);
        //say('Сообщение не распознано о - '.$state['event'].' от - '.$state['deviceId'].' - '.$this->result['status'].' - в - '.$this->result['date'], $msl);
        break;
}

$name = $this->getProperty('name');
$topic = $name."/home/alarm/event";
mqttMob($topic, $status, 0, 0);  //Передаем в MQTT
