/** SGadgetMAP_alarmEvent.php
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

$state = json_decode($params['VALUE'], true); // Принимаем и Декодируем в массив

$payload = $state['event'];

switch ($payload) { // Принимаем SWM07E/home/alarm/event
    case 'invalid_code_provided': // Неверный код для отключения или постановки на охрану сигнализации
        setGlobal("SecurityArmedMode.event", $state['event']);
        $status = '{ "event": "invalid_code_provided" }' ;
        break;
    case 'no_code_provided': // Код для снятия или постановки на охрану сигнализации требуется, но отсутствует
        setGlobal("SecurityArmedMode.event", $state['event']);
        $status = '{ "event": "no_code_provided" }' ;
        break;
    case 'failed_to_arm': // Сигнализация не могла быть включена из-за разомкнутых датчиков
        setGlobal("SecurityArmedMode.event", $state['event']);
        $status = '{ "event": "failed_to_arm" }' ;
        break;
    case 'system_disabled': // The alarm state could not be changed, because the system was unresponsive or unavailable
        setGlobal("SecurityArmedMode.event", $state['event']);
        $status = '{ "event": "system_disabled" }' ;
        break;
    case 'unknown': // Состояние тревоги не удалось изменить из-за неизвестной ошибки, проверьте настройки
        setGlobal("SecurityArmedMode.event", $state['event']);
        $status = '{ "event": "unknown" }' ;
        break;
    case 'arm_home': // 
        if (isset($state['delay'])) $delay = " - ".$state['delay'];
        setGlobal("SecurityArmedMode.event", $state['event'].$delay);
        $status = '{ "event": "arm_home" }' ;
        break;
    case 'arm_away': // 
        if (isset($state['delay'])) $delay = " - ".$state['delay'];
        setGlobal("SecurityArmedMode.event", $state['event'].$delay);
        $status = '{ "event": "arm_away" }' ;
        break;
    case 'arm_night': // {"event": "arm_night", "delay": 10}
        if (isset($state['delay'])) $delay = " - ".$state['delay'];
        setGlobal("SecurityArmedMode.event", $state['event'].$delay);
        $status = '{ "event": "arm_night" }' ;
        break;
    case 'arm_custom_bypass': // {"event": "arm_custom_bypass", "delay": 10}
        if (isset($state['delay'])) $delay = " - ".$state['delay'];
        setGlobal("SecurityArmedMode.event", $state['event'].$delay);
        $status = '{ "event": "arm_custom_bypass" }' ;
        break;
    case 'pending': // 
        if (isset($state['delay'])) $delay = " - ".$state['delay'];
        setGlobal("SecurityArmedMode.event", $state['event'].$delay);
        $status = '{ "event": "pending","delay": 60 }'; // # disarm time
        break;
    case 'ARM_AWAY': // Используется для синхронизации команды тревоги между несколькими устройствами или с удаленным сервером
        if (isset($state['delay'])) $delay = " - ".$state['delay'];
        setGlobal("SecurityArmedMode.event", $state['event'].$delay);
        $status = '{ "event": "ARM_AWAY" }' ;
        break;
    case 'ARM_HOME': // Используется для синхронизации команды тревоги между несколькими устройствами или с удаленным сервером
        if (isset($state['delay'])) $delay = " - ".$state['delay'];
        setGlobal("SecurityArmedMode.event", $state['event'].$delay);
        $status = '{ "event": "ARM_HOME" }' ;
        break;
    case 'ARM_NIGHT': // Используется для синхронизации команды тревоги между несколькими устройствами или с удаленным сервером
        if (isset($state['delay'])) $delay = " - ".$state['delay'];
        setGlobal("SecurityArmedMode.event", $state['event'].$delay);
        $status = '{ "event": "ARM_NIGHT" }' ;
        break;
    case 'ARM_CUSTOM_BYPASS': // Используется для синхронизации команды тревоги между несколькими устройствами или с удаленным сервером
        if (isset($state['delay'])) $delay = " - ".$state['delay'];
        setGlobal("SecurityArmedMode.event", $state['event'].$delay);
        $status = '{ "event": "ARM_CUSTOM_BYPASS" }' ;
        break;
    default: //say('Сообщение не распознано', 0);
        setGlobal("SecurityArmedMode.event", $params['VALUE']);
        //$this->result = array('status' => 'не распознано', 'date' => date('Y/m/d H:i:s', time()));
        //DebMes('Сообщение - '.$state['event'].' - от - '.$state['deviceId'].' - '.$this->result['status'].' - '.$this->result['date']);
        //say('Сообщение не распознано о - '.$state['event'].' от - '.$state['deviceId'].' - '.$this->result['status'].' - в - '.$this->result['date'], $msl);
        break;
}

//$name = $this->getProperty('name');
//$topic = $name."/home/alarm/event";
//mqttMob($topic, $status, 0, 0);  //Передаем в MQTT







/*
$ot = $this->object_title;
$topic = $this->getProperty('name');
SQLExec("UPDATE mqtt SET LINKED_OBJECT='" . $ot . "' WHERE PATH='" . $topic . "/home/alarm/set'"); 
SQLExec("UPDATE mqtt SET LINKED_PROPERTY='' WHERE PATH='" . $topic . "/home/alarm/set'"); 
SQLExec("UPDATE mqtt SET LINKED_METHOD='alarmSet' WHERE PATH='" . $topic . "/home/alarm/set'"); 


SQLExec("UPDATE mqtt SET LINKED_OBJECT='MobileMAP01', LINKED_PROPERTY='', LINKED_METHOD='alarmSet' WHERE PATH='SWM07E/alarmpanel/state/currentUrl'"); 


LINKED_OBJECT      MobileMAP01
LINKED_PROPERTY    
LINKED_METHOD      alarmSet
TITLE   SWM07E/home/alarm/set
PATH    SWM07E/home/alarm/set

   $Record = SQLSelectOne("SELECT * FROM contacts WHERE ID='".$_POST['id']."'");
   $Record['FIRSTNAME'] = $_POST['firstname'];
   $Record['LASTNAME'] = $_POST['lastname'];
   $Record['EMAIL'] = $_POST['email'];
   SQLUpdateInsert('contacts', $Record);

SWM07E/alarmpanel
    SWM07E/alarmpanel/command    LINKED_OBJECT    MobileMAP01    LINKED_PROPERTY    command    LINKED_METHOD
            SQLExec("UPDATE mqtt SET LINKED_OBJECT='". $ot ."', LINKED_PROPERTY='command' WHERE PATH='" . $topic . "/alarmpanel/command'"); 
        SWM07E/alarmpanel/command/alert
        SWM07E/alarmpanel/command/speak
        SWM07E/alarmpanel/command/audio
        SWM07E/alarmpanel/command/notification
        SWM07E/alarmpanel/command/temperature
        SWM07E/alarmpanel/command/forecast
        SWM07E/alarmpanel/command/condition
        SWM07E/alarmpanel/command/dashboard
        SWM07E/alarmpanel/command/sun
        SWM07E/alarmpanel/command/clearCache
        SWM07E/alarmpanel/command/relaunch
        SWM07E/alarmpanel/command/wake
        SWM07E/alarmpanel/command/humidity
        SWM07E/alarmpanel/command/weather
            SWM07E/alarmpanel/command/weather/condition
            SWM07E/alarmpanel/command/weather/forecast
            SWM07E/alarmpanel/command/weather/humidity
            SWM07E/alarmpanel/command/weather/temperature
        SWM07E/alarmpanel/sensor/battery    LINKED_OBJECT    MobileMAP01    LINKED_PROPERTY    LINKED_METHOD    sensorsDetected
                SQLExec("UPDATE mqtt SET LINKED_OBJECT='". $ot ."', LINKED_METHOD='sensorsDetected' WHERE PATH='" . $topic . "/alarmpanel/sensor/battery'"); 
            SWM07E/alarmpanel/sensor/battery/acPlugged
            SWM07E/alarmpanel/sensor/battery/charging
            SWM07E/alarmpanel/sensor/battery/unit
            SWM07E/alarmpanel/sensor/battery/usbPlugged
            SWM07E/alarmpanel/sensor/battery/value
        SWM07E/alarmpanel/sensor/face    LINKED_OBJECT    MobileMAP01    LINKED_PROPERTY    LINKED_METHOD    faceDetected
                SQLExec("UPDATE mqtt SET LINKED_OBJECT='". $ot ."', LINKED_METHOD='faceDetected WHERE PATH='" . $topic . "/alarmpanel/sensor/face'"); 
            SWM07E/alarmpanel/sensor/face/value
        SWM07E/alarmpanel/sensor/motion    LINKED_OBJECT    MobileMAP01    LINKED_PROPERTY    LINKED_METHOD    motionDetected
                SQLExec("UPDATE mqtt SET LINKED_OBJECT='". $ot ."', LINKED_METHOD='motionDetected' WHERE PATH='" . $topic . "/alarmpanel/sensor/motion'"); 
            SWM07E/alarmpanel/sensor/motion/value
        SWM07E/alarmpanel/sensor/qrcode    LINKED_OBJECT    MobileMAP01    LINKED_PROPERTY    LINKED_METHOD    qrcodeDetected
                SQLExec("UPDATE mqtt SET LINKED_OBJECT='". $ot ."', LINKED_METHOD='qrcodeDetected' WHERE PATH='" . $topic . "/alarmpanel/sensor/qrcode'"); 
            SWM07E/alarmpanel/sensor/qrcode/value
        SWM07E/alarmpanel/state    LINKED_OBJECT    MobileMAP01    LINKED_PROPERTY    LINKED_METHOD    state
                SQLExec("UPDATE mqtt SET LINKED_OBJECT='". $ot ."', LINKED_METHOD='state' WHERE PATH='" . $topic . "/alarmpanel/state'"); 
            SWM07E/alarmpanel/state/brightness
            SWM07E/alarmpanel/state/currentUrl
            SWM07E/alarmpanel/state/presence
            SWM07E/alarmpanel/state/screenOn
    SWM07E/home/alarm
        SWM07E/home/alarm/event    LINKED_OBJECT    MobileMAP01    LINKED_PROPERTY    LINKED_METHOD    alarmEvent
            SQLExec("UPDATE mqtt SET LINKED_OBJECT='". $ot ."', LINKED_METHOD='alarmEvent' WHERE PATH='" . $topic . "/home/alarm/event'"); 
        SWM07E/home/alarm/event/delay
        SWM07E/home/alarm/event/event
        SWM07E/home/alarm/event/state
    SWM07E/home/alarm/sensor
        SWM07E/home/alarm/sensor/face
        SWM07E/home/alarm/sensor/motion
        SWM07E/home/alarm/sensor/spech
    SWM07E/home/alarm/set    LINKED_OBJECT    MobileMAP01    LINKED_PROPERTY    LINKED_METHOD    alarmSet
            SQLExec("UPDATE mqtt SET LINKED_OBJECT='". $ot ."', LINKED_METHOD='alarmSet' WHERE PATH='" . $topic . "/home/alarm/set'"); 
        SWM07E/home/alarm/set/code
        SWM07E/home/alarm/set/command
*/
