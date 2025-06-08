<?php
/** SGadgetMAP_alarm.php
* Поддерживаемые Состояния и Топик Состояний
* Панель сигнализации подписывается на изменения состояния MQTT, публикуемые удаленной системой сигнализации.
* Тема состояния по умолчанию - home/alarm, и ее можно изменить в настройках.
* Вот список значений состояния и их описаний, которые может обрабатывать приложение.
*
* disarmed              The alarm is disabled/off. Сигнализация отключена/выкл.
* arming                The alarm is arming. Сигнализация включена. Сигнализация будет включена после задержки. (The alarm will be armed after the delay.)
* armed_away            The alarm is armed in away mode.
* armed_home            The alarm is armed in home mode.
* armed_night           The alarm is armed in night mode.
* armed_custom_bypass   The alarm is armed in custom mode.
* pending               The alarm is pending. Сигнал тревоги сработает после задержки. (The alarm will be triggered after the delay.)
* triggered	            The alarm is triggered.
* -----------------------------------------------------------------------
* Поддерживаемое мероприятие и тема мероприятия (Supported Event and Event Topic)
* Alarm Panel может подписаться на тему события, чтобы получать дополнительную информацию от удаленной системы сигнализации.
* Например, топик евент будет уведомлять приложение об ошибках сигнализации,
* таких как неверные коды и невозможность активировать сигнализацию из-за открытых датчиков.
* Топик евент по умолчанию - home/alarm/event, и его можно изменить в настройках.
* Вот список полезных нагрузок событий, которые может обрабатывать панель сигнализации, полезная нагрузка используется в формате JSON.
*
* invalid_code_provided Неверный код для снятия с охраны или постановки на охрану сигнализации.
* no_code_provided      Код для снятия или постановки на охрану сигнализации требуется, но отсутствует.
* failed_to_arm	        Сигнализация не могла быть включена из-за разомкнутых датчиков.
* system_disabled       Состояние тревоги изменить не удалось, поскольку система не отвечала на запросы или была недоступна.
* unknown               Состояние тревоги не удалось изменить из-за неизвестной ошибки, проверьте настройки.
* ARM_AWAY,
* ARM_HOME,
* ARM_NIGHT,
* ARM_CUSTOM_BYPASS Используется для синхронизации команд тревоги между несколькими устройствами или с удаленным сервером.
*
* Если нужно отправить сообщение об ошибке, о том что был введен неверный код для снятия с охраны или постановки на охрану,
* нужно отправить сообщение в формате JSON по теме события:
* {"event": "invalid_code_provided"}
*/
$ot = $this->object_title;
$msl = getGlobal('ThisComputer.minMsgLevel');

$payload = $params['NEW_VALUE'];

if (!isset($params['NEW_VALUE'])) { 
    $payload = $params['VALUE']; 
} // Принимаем данные

//$this->setProperty('arms', $params['VALUE']); // Записываем сырые данные в свойство при необходимости

switch ($payload) {
    case 'arming': //say('Запрос на взятие', $msl); // Сигнализация включена. Сигнализация будет включена после задержки.
        setGlobal("SecurityArmedMode.arm", "arming");
        $status = 'arming';
        break;
    case 'armed_away': say('Сигнализация включена', $msl); //say('Запрос на установку armed away', $msl); // The alarm is armed in away mode. (Полностью)
        callMethodSafe('SecurityArmedMode.activate');
        setGlobal("SecurityArmedMode.arm", "armed_away");
        clearTimeOut("armed_away");
        $status = 'armed_away';
        break;
    case 'armed_home': say('Сигнализация включена', $msl); //say('Запрос на установку armed home', $msl); // The alarm is armed in home mode. (Только дом)
        callMethodSafe('SecurityArmedMode.activate');
        setGlobal("SecurityArmedMode.arm", "armed_home");
        $status = 'armed_home';
        break;
    case 'armed_night': say('Сигнализация включена', $msl); //say('Запрос на установку ночной охраны', $msl); // The alarm is armed in night mode. (Частично)
        callMethodSafe('SecurityArmedMode.activate');
        setGlobal("SecurityArmedMode.arm", "armed_night");
        $status = 'armed_night';
        break;
    case 'armed_custom_bypass': say('Сигнализация включена', $msl); //say('Запрос на установку armed custom bypass', $msl); // The alarm is armed in custom mode. (Выборочно)
        callMethodSafe('SecurityArmedMode.activate');
        setGlobal("SecurityArmedMode.arm", "armed_custom_bypass");
        $status = 'armed_custom_bypass';
        break;
    case 'pending': say('Задержка снятия', $msl); // Сигнал тревоги находится в ожидании. Сигнал тревоги сработает после задержки.
        if (getGlobal('SecurityArmedMode.active') == 1) {
            setTimeOut("pending", "callMethodSafe('".$ot.".alarm', array('VALUE'=>'triggered'));", 60);
            setGlobal("SecurityArmedMode.arm", "pending");
            $status = 'pending';
        } else {
            clearTimeOut("pending");
            //$this->callMethod('alarm', array('NEW_VALUE'=>'disarmed','arm'=>'disarmed'));
        }
        break;
    case 'pending10': //say('Задержка снятия10', $msl); // Сигнал тревоги находится в ожидании. Сигнал тревоги сработает после задержки.SecurityArmedMode.arm
        if (getGlobal('SecurityArmedMode.active') == 1 && getGlobal('SecurityArmedMode.arm') != 'triggered') {
            say('Задержка снятия 10 секунд', $msl);
            setTimeOut("pending", "callMethodSafe('".$ot.".alarm', array('VALUE'=>'triggered'));", 20);
            setGlobal("SecurityArmedMode.arm", "pending");
            $status = '{"event": "pending", "delay": 20}';
        } else {
            clearTimeOut("pending");
            //$this->callMethod('alarm', array('NEW_VALUE'=>'disarmed','arm'=>'disarmed'));
            //$status = '{"event": "pending", "delay": 10}'; //{ "state": "pending","delay": 60 }
        }
        break;
    case 'triggered':  // The alarm is triggered. Срабатывает сигнализация
        if (getGlobal('SecurityArmedMode.active') == 1) {
            say('Сработала сигнализация', $msl);
            $name = $this->getProperty('name');
            $topic = $name."/alarmpanel/command";
            $alarm = $this->getProperty('alarmTriggered');
            $status = '{"audio":"'.$alarm.'"}';
            mqttMob($topic, $status, 0, 0);
            setGlobal("SecurityArmedMode.arm", "triggered");
            $status = 'triggered';
        } else {
            clearTimeOut("pending");
            //$this->callMethod('alarm', array('NEW_VALUE'=>'disarmed','arm'=>'disarmed'));
        }
        break;
    case 'disarmed': say('Сигнализация отключена', $msl); // Сигнализация отключена/выкл
        $this->callMethod('command', array('PROPERTY'=>'audio','NEW_VALUE'=>''));
        callMethodSafe('SecurityArmedMode.deactivate');
        setGlobal("SecurityArmedMode.arm", "disarmed");
        clearTimeOut("pending");
        $status = 'disarmed';
        break;
    case 'open': //say('Сообщение disarmed', $msl); // Заглушка для меню
        break;
    default: //say('Сообщение alarm не распознаноо', 0);
        if (isset($params['arms'])) {
            $arms = $params['arms'];
            $delay = $params['delay'];
            $mes = '{"event": "'.$arms.'", "delay": '.$delay.'}'; //$status = '{"event": "arm_away", "delay": '.$delay.'}';
            $this->callMethod('alarm', array('NEW_VALUE'=>'arming'));
            $this->callMethod('alarmEvent', array('VALUE'=>$mes));
            //$this->callMethod('alarm', array('NEW_VALUE'=>'arming','arm'=>'armed_away','delay'=>$delay));
            //$this->callMethodSafe('alarmEvent', array('VALUE'=>'{"event": "arm_away", "delay": "'.$delay.'"}'));
        } else {
            $arm = $params['arm'];
            setTimeOut("arming", "callMethod('".$ot.".alarm', array('VALUE'=> ".$arm."));", 5);
        }
        $status = 'arming';
        //$this->result = array('status' => 'не распознано', 'date' => date('Y/m/d H:i:s', time()));
        //DebMes('Сообщение - '.$state['event'].' - от - '.$state['deviceId'].' - '.$this->result['status'].' - '.$this->result['date']);
        //say('Сообщение не распознано о - '.$state['event'].' от - '.$state['deviceId'].' - '.$this->result['status'].' - в - '.$this->result['date'], $msl);
        break;
}
$name = $this->getProperty('name');
$topic = $name."/home/alarm";
mqttMob($topic, $status, 0, 0);  //Передаем в MQTT
