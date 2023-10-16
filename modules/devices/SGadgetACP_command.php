<?php
/*
{'audio':'http://192.168.10.26/cms/sounds/rh_18h.wav'} {"audio": "http://<url>"}
{"noti":"Павлик дома","title":"Сообщение 2","info":"Павлик","vibrate":true,"sound":true,"light":true,"id":2}
{'dashboard': '0'}{"notification": "Hello!"}

{"payload": "alert": "Hello!", "topic": "alarmpanel/command"}
{"payload": "{'sun':'below_horizon'}", "topic": "alarmpanel/command"}
{"condition":"sunny","humidity":61,"temperature":27.2,"forecast":[
     {'condition': 'sunny', 'temperature': 19.8, 'templow': 13.5, 'datetime': '2020-11-30T15:00:00+00:00', 'wind_bearing': 101.4, 'wind_speed': 30.6}
     ]
}

* https://github.com/thanksmister/android-mqtt-alarm-panel

//$prop = $params['PROPERTY'];
//$oldv = $params['OLD_VALUE'];
//$new = $params['NEW_VALUE'];
//$origot = $params['ORIGINAL_OBJECT_TITLE'];

$name=$this->getProperty('name');
$alarmp=$this->getProperty('alarmpanel_command_dashboard');
$status= "";

*/
$ot = $this->object_title;
$desc = $this->description;
$payload = $params['PROPERTY'];

//if (!isset($params['VALUE'])) $payload = $params['NEW_VALUE'];// return; // проверяем если $params['VALUE'] нет в сообщении

switch ($payload) { // SWM07E/alarmpanel/command
    case 'dashboard': // number	{"dashboard": 0} Переход к определенной панели мониторинга, отправив 0, отобразится экран тревоги.
                     //$value = $params['VALUE'];
                     $array = array('dashboard' => $params['NEW_VALUE']);
                     $status = json_encode($array, true, JSON_UNESCAPED_UNICODE);
                     //Передаем в MQTT
                     //$topic = $name. "/comm/notification/create";
                     //mqttMob($topic, $json, 0, 0);
                     //$status = '{"dashboard": 0}';
                     break;
    case 'audio': // URL {"audio": "http://<url>"} Немедленно воспроизводит аудио, указанное по URL-адресу
                     $status = '{"audio":"http://192.168.10.26/cms/sounds/10h.mp3"}'; //  .$params['NEW_VALUE'].
                     break; 
    case 'wake': // true {"wake": true} Пробуждает экран, если он находится в спящем режиме
                     $status = '{"wake": true}';
                     break; 
    case 'speak': // data {"speak": "Hello!"} Uses the devices TTS to speak the message
                     $status = '{"speak": "Hello!"}';
                     break; 
    case 'alert': // data {"alert": "Hello!"} Displays an alert dialog within the application
                     $status = '{"alert": "Hello!"}';
                     break; 
    case 'notification': // data {"notification": "Hello!"} Displays a system notification on the device
                     $status = '{"notification": "Hello!"}';
                     break; 
    case 'sun': // data	{"sun": "above_horizon"} Changes the application day or night mode based on sun value (above_horizon, below_horizon)
                     $status = '{"sun":"below_horizon"}';
                     break;  
    case 'clearCache': // {"clearCache":true, "relaunch":true}
                     $status = '{"clearCache":true, "relaunch":true}';
                     break;  
    case 'relaunch': // {"clearCache":true, "relaunch":true}
                     $status = '{"clearCache":true, "relaunch":true}';
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
    default: //say('Сообщение не распознано', 0);
              $this->result = array('status' => 'не распознано', 'date' => date('Y/m/d H:i:s', time()));
              DebMes('Сообщение - '.$state['event'].' - от - '.$state['deviceId'].' - '.$this->result['status'].' - '.$this->result['date']);
              say('Сообщение не распознано о - '.$state['event'].' от - '.$state['deviceId'].' - '.$this->result['status'].' - в - '.$this->result['date'], $msl);
              break;
}
$name=$this->getProperty('name');
include_once(DIR_MODULES . 'mqtt/mqtt.class.php');
$mqtt = new mqtt();
//$topic = "alarmpanel/".$name."/command";
$topic = $name."/alarmpanel/command";
$rezult = $mqtt->mqttPublish($topic, $status, 0, 0);
