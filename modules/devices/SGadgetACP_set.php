<?php
/*
Поддерживаемые команды и раздел команд:
Панель сигнализации может отправлять на сервер команды для различных состояний сигнализации. 
Брокер MQTT прослушивает эти команды, чтобы обновить состояние тревоги. 
Тема команды по умолчанию - home/alarm/set, которую можно изменить в настройках. 
Вот список команд, отправляемых из приложения брокеру MQTT.

ARM_AWAY	        Arm the alarm in mode armed_away.
ARM_HOME	        Arm the alarm in mode armed_home.
ARM_NIGHT	        Arm the alarm in mode armed_night.
ARM_CUSTOM_BYPASS	Arm the alarm in mode armed_custom_bypass.
DISARM	            Disarm the alarm.
PANIC	            Alarm panic button pressed.
{"command": "ARM_HOME","code": "1234"}
* -----------------------------------------------------------------------
Поддерживаемые Состояния и  Топик Состояний
Панель сигнализации подписывается на изменения состояния MQTT, публикуемые удаленной системой сигнализации. 
Тема состояния по умолчанию - home/alarm, и ее можно изменить в настройках. 
Вот список значений состояния и их описаний, которые может обрабатывать приложение.

disarmed	        The alarm is disabled/off. Сигнализация отключена/выкл.
arming	            The alarm is arming. The alarm will be armed after the delay. Сигнализация включена. Сигнализация будет включена после задержки.
armed_away
armed_home	        The alarm is armed in home mode.
armed_night	        The alarm is armed in night mode.
armed_custom_bypass	The alarm is armed in custom mode.
pending	            The alarm is pending. The alarm will be triggered after the delay.
triggered	        The alarm is triggered.
* -----------------------------------------------------------------------
Supported Event and Event Topic
Alarm Panel can subscribe to an event topic to receive additional information from the remote alarm system. 
For example, the event topic will notify the application of alarm errors, such as invalid codes and the inablity to activate the alarm due to open sensors. 
The default topic for event is home/alarm/event and can be changed in the settings. 
Here is a list of event payloads that Alarm Panel can handle, note that the payload is using JSON.

invalid_code_provided	Неверный код для снятия с охраны или постановки на охрану сигнализации.
no_code_provided	    Код для снятия или постановки на охрану сигнализации требуется, но отсутствует.
failed_to_arm	        Сигнализация не могла быть включена из-за разомкнутых датчиков.
system_disabled	        Состояние тревоги изменить не удалось, поскольку система не отвечала на запросы или была недоступна.
unknown	                Состояние тревоги не удалось изменить из-за неизвестной ошибки, проверьте настройки.
ARM_AWAY, ARM_HOME, ARM_NIGHT, ARM_CUSTOM_BYPASS Используется для синхронизации команд тревоги между несколькими устройствами или с удаленным сервером.

Если вы хотите отправить сообщение об ошибке, что был введен неверный код для снятия с охраны или постановки на охрану сигнализации,
вы должны отправить следующую полезную нагрузку в формате JSON по теме события:

{"event": "invalid_code_provided"}
* ------------------------------------------------------------------------
//$this->setProperty('message', $message); // Записываем сырые данные в свойство при необходимости
$l_fed_filing_status = isset($json["ROOT"]["APPLICANT"]) ? $json["ROOT"]["APPLICANT"] : '';
*/
//$ot = $this->object_title;
//$desc = $this->description;
$name = $this->getProperty('name'); //alarmPanel02.name alarmPanel02.code
//$payload = $params['VALUE']; // Принимаем данные
    $json = json_decode($params['VALUE'], true); // Декодируем в массив {"command": "ARM_HOME","code": "1234"}
if (!isset($json["command"])) { 
    $payload = $params['VALUE'];// из ThisComputer->onSwitch
} else {
    $payload = $json['command'];// из ThisComputer->onSwitch
}

switch ($payload) { // SWM07E/alarmpanel/command // SWM07E/alarmpanel/sensor/motion
    case 'ARM_AWAY': say('Сообщение ARM AWAY', $msl); // Arm the alarm in mode armed_away
                     if (!isset($json["code"])) { $topic = $name."/home/alarm"; $status = "armed_away";
                     } else {
                     if ($json['code'] == $this->getProperty('code')) { $topic = $name."/home/alarm"; $status = "armed_away"; 
                     } else {                     
                     $topic = $name."/home/alarm/event";
                     $status = '{"event": "invalid_code_provided"}';} }
                     break; 
    case 'ARM_HOME': say('Сообщение ARM HOME', $msl);// 	Arm the alarm in mode armed_home {"event": "invalid_code_provided"}
                     if (!isset($json["code"])) { $topic = $name."/home/alarm"; $status = "armed_home";
                     } else {
                     if ($json['code'] == $this->getProperty('code')) { $topic = $name."/home/alarm"; $status = "armed_home"; 
                     } else {                     
                     $topic = $name."/home/alarm/event";
                     $status = '{"event": "invalid_code_provided"}';} }
                     break; 
    case 'ARM_NIGHT': say('Запрос на установку ночной охраны', $msl);// ARM_NIGHT	Arm the alarm in mode armed_night
                     if (!isset($json["code"])) { $topic = $name."/home/alarm"; $status = "armed_night";
                     } else {
                     if ($json['code'] == $this->getProperty('code')) { $topic = $name."/home/alarm"; $status = "armed_night"; 
                     } else {                     
                     $topic = $name."/home/alarm/event";
                     $status = '{"event": "invalid_code_provided"}';} }
                     break; 
    case 'ARM_CUSTOM_BYPASS': say('Сообщение ARM CUSTOM BYPASS', $msl);// 	Arm the alarm in mode armed_custom_bypass
                     if (!isset($json["code"])) { $topic = $name."/home/alarm"; $status = "armed_custom_bypass";
                     } else {
                     if ($json['code'] == $this->getProperty('code')) { $topic = $name."/home/alarm"; $status = "armed_custom_bypass"; 
                     } else {                     
                     $topic = $name."/home/alarm/event";
                     $status = '{"event": "invalid_code_provided"}';} }
                     break; 
    case 'DISARM': say('Сообщение DISARM', $msl);// 	Disarm the alarm
                     if (!isset($json["code"])) { $topic = $name."/home/alarm"; $status = "disarmed";
                     } else {
                     if ($json['code'] == $this->getProperty('code')) { $topic = $name."/home/alarm"; $status = "disarmed"; 
                     } else {                     
                     $topic = $name."/home/alarm/event";
                     $status = '{"event": "invalid_code_provided"}';} }
                     break; 
    case 'PANIC': say('Сообщение PANIC', $msl);// 	Alarm panic button pressed
                     $topic = $name."/alarmpanel/command";
                     $status = '{"audio":"http://192.168.10.26/cms/sounds/10h.mp3"}';
                     break; 
    case 'onMotion': //  
                     if($this->getProperty('private_mode') != 1) { //private_mode
                        $this->callmethodSafe('motionDetected');
                       //say('Есть - сообщение - '.$rezult.' - '.$params['VALUE'], $msl);
                     }
                     //DebMes('Обнаружено движение '.$ot);
                     //$command='Сколько время';
                     //callMethod('ThisComputer.commandReceived', array('command'=>$command));
                     break; 
    default: say('Сообщение не распознано', 0);
              //$this->result = array('status' => 'не распознано', 'date' => date('Y/m/d H:i:s', time()));
              //DebMes('Сообщение - '.$state['event'].' - от - '.$state['deviceId'].' - '.$this->result['status'].' - '.$this->result['date']);
              //say('Сообщение не распознано о - '.$state['event'].' от - '.$state['deviceId'].' - '.$this->result['status'].' - в - '.$this->result['date'], $msl);
              break;
}

include_once(DIR_MODULES . 'mqtt/mqtt.class.php');
$mqtt = new mqtt();
$rezult = $mqtt->mqttPublish($topic, $status, 0, 0);
/*//$topic = "alarmpanel/".$name."/command";  Принимаем SWM07E/home/alarm/set Отправляем SWM07E/home/alarm/event
$topic = $name."/home/alarm/event";
$rezult = $mqtt->mqttPublish($topic, $status, 0, 0);
*/
