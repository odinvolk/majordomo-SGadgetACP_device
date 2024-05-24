<?php
/* SGadgetMAP_alarmSet.php
* Поддерживаемые команды и раздел команд:
* Панель сигнализации может отправлять на сервер команды для различных состояний сигнализации. 
* Брокер MQTT прослушивает эти команды, чтобы обновить состояние тревоги. 
* Тема команды по умолчанию - home/alarm/set, которую можно изменить в настройках. 
* Вот список команд, отправляемых из приложения брокеру MQTT.
* 
* ARM_AWAY          Arm the alarm in mode armed_away.
* ARM_HOME          Arm the alarm in mode armed_home.
* ARM_NIGHT         Arm the alarm in mode armed_night.
* ARM_CUSTOM_BYPASS Arm the alarm in mode armed_custom_bypass.
* DISARM            Disarm the alarm.
* PANIC             Alarm panic button pressed.
* {"command": "ARM_HOME","code": "1234"}
* -----------------------------------------------------------------------
* Поддерживаемые Состояния и  Топик Состояний
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
* triggered             The alarm is triggered. Тревога проникновения
* -----------------------------------------------------------------------
* Поддерживаемые События и Топик Событий (Supported Event and Event Topic)
* Alarm Panel может подписаться на тему события, чтобы получать дополнительную информацию от удаленной системы сигнализации.
* Например, топик евент будет уведомлять приложение об ошибках сигнализации, 
* таких как неверные коды и невозможность активировать сигнализацию из-за открытых датчиков.
* Топик евент по умолчанию - home/alarm/event, и его можно изменить в настройках.
* Вот список полезных нагрузок событий, которые может обрабатывать панель сигнализации, полезная нагрузка используется в формате JSON.
* 
* invalid_code_provided Неверный код для снятия с охраны или постановки на охрану сигнализации.
* no_code_provided      Код для снятия или постановки на охрану сигнализации требуется, но отсутствует.
* failed_to_arm         Сигнализация не могла быть включена из-за разомкнутых датчиков.
* system_disabled       Состояние тревоги изменить не удалось, поскольку система не отвечала на запросы или была недоступна.
* unknown               Состояние тревоги не удалось изменить из-за неизвестной ошибки, проверьте настройки.
* ARM_AWAY, ARM_HOME, ARM_NIGHT, ARM_CUSTOM_BYPASS Используется для синхронизации команд тревоги между несколькими устройствами или с удаленным сервером.
* 
* Если вы хотите отправить сообщение об ошибке, что был введен неверный код для снятия с охраны или постановки на охрану сигнализации,
* вы должны отправить следующую полезную нагрузку в формате JSON по теме события:
* {"event": "invalid_code_provided"}
* ------------------------------------------------------------------------
* $this->setProperty('messageSet', $params['VALUE']); // Записываем сырые данные в свойство при необходимости
*/

$name = $this->getProperty('name');
//$payload = $params['VALUE']; // Принимаем данные
$json = json_decode($params['VALUE'], true); // Декодируем в массив {"command": "ARM_HOME","code": "1234"}

if (!isset($json["command"])) {                                       //{"command": "ARM_HOME","code": "1234"}
    $payload = $params['VALUE'];                                      //DISARM
} else {
    $payload = $json['command'];
}

switch ($payload) { 
    case 'ARM_AWAY': say('Полная постановка на охрану', $msl); // Arm the alarm in mode armed_away (Полностью)
        if (!isset($json["command"])) { 
            $topic = $name."/home/alarm"; 
            $status = "armed_away"; 
            callMethodSafe('SecurityArmedMode.activate');
        } else { 
            if (!isset($json["code"])) { 
                $topic = $name."/home/alarm/event"; 
                $status = '{"event": "no_code_provided"}';
                say('Нужен код', $msl);
            }
            if ($json['code'] == $this->getProperty('code')) { 
                $topic = $name."/home/alarm"; 
                $status = "armed_away";
                
                $topic = $name."/home/alarm/event"; 
                $status = '{"event": "arm_away", "delay": 15}';
                say('Код принят, включена задержка', $msl);
            } else { 
                $topic = $name."/home/alarm/event";
                $status = '{"event": "invalid_code_provided"}';
                say('Неверный код', $msl); 
            }
        }
        break; 
    case 'ARM_HOME': say('Установка дома на охрану', $msl); // Arm the alarm in mode armed_home (Только дом)
        if (!isset($json["command"])) { 
            $topic = $name."/home/alarm"; 
            $status = "armed_home"; 
            callMethodSafe('SecurityArmedMode.activate');
        } else { 
            if (!isset($json["code"])) { 
                $topic = $name."/home/alarm/event"; 
                $status = '{"event": "no_code_provided"}';
            } else {
                if ($json['code'] == $this->getProperty('code')) { 
                    $topic = $name."/home/alarm"; 
                    $status = "armed_home";
                    $topic = $name."/home/alarm/event"; 
                    $status = '{"event": "arm_home", "delay": 10}';
                    callMethodSafe('SecurityArmedMode.activate');
                } else { 
                    $topic = $name."/home/alarm/event";
                    $status = '{"event": "invalid_code_provided"}';
                    say('Неверный код', $msl); 
                }
            }
        }
        break; 
    case 'ARM_NIGHT': say('Установка ночной охраны', $msl); // Arm the alarm in mode armed_night (Частично)
        if (!isset($json["command"])) {
            $topic = $name."/home/alarm"; 
            $status = "armed_night"; 
            callMethodSafe('SecurityArmedMode.activate');
        } else { 
            if (!isset($json["code"])) {
                $topic = $name."/home/alarm/event"; 
                $status = '{"event": "no_code_provided"}';
            } else {
                if ($json['code'] == $this->getProperty('code')) { 
                    $topic = $name."/home/alarm"; 
                    $status = "armed_night";
                    
                    $topic = $name."/home/alarm/event"; 
                    $status = '{"event": "arm_night", "delay": 10}';
                    setTimeOut('armed_night', 'callMethodSafe("SecurityArmedMode.activate");', 10); //15*60
                } else { 
                    $topic = $name."/home/alarm/event";
                    $status = '{"event": "invalid_code_provided"}';
                    say('Неверный код', $msl);
                }
            }
        }
        break; 
    case 'ARM_CUSTOM_BYPASS': say('Установка охраны с проходом', $msl); // Arm the alarm in mode armed_custom_bypass (Выборочно)
        if (!isset($json["command"])) {
            $topic = $name."/home/alarm"; 
            $status = "armed_custom_bypass"; 
            callMethodSafe('SecurityArmedMode.activate');
        } else  { 
            if (!isset($json["code"])) {
                $topic = $name."/home/alarm/event"; 
                $status = '{"event": "no_code_provided"}';
            } else {
                if ($json['code'] == $this->getProperty('code')) { 
                    $topic = $name."/home/alarm"; 
                    $status = "armed_custom_bypass";
                    
                    $topic = $name."/home/alarm/event"; 
                    $status = '{"event": "arm_custom_bypass", "delay": 30}';
                    setTimeOut('armed_custom_bypass', 'callMethodSafe("SecurityArmedMode.activate");', 30); //15*60
                } else { 
                    $topic = $name."/home/alarm/event";
                    $status = '{"event": "invalid_code_provided"}';
                    say('Неверный код', $msl);
                }
            }
        }
        break; 
    case 'DISARM': say('Снятие охраны', $msl); // Disarm the alarm (Выключить)
        if (!isset($json["command"])) {
            $topic = $name."/home/alarm"; 
            $status = "disarmed"; 
            callMethodSafe('SecurityArmedMode.deactivate');
        } else { 
            if (!isset($json["code"])) {
                $topic = $name."/home/alarm/event"; 
                $status = '{"event": "no_code_provided"}';
                say('Нужен код', $msl);
            } else  { 
                if ($json['code'] == $this->getProperty('code')) { 
                    $topic = $name."/home/alarm"; 
                    $status = "disarmed";
                    say('Код принят', $msl);
                    callMethodSafe('SecurityArmedMode.deactivate');
                } else { 
                    $topic = $name."/home/alarm/event";
                    $status = '{"event": "invalid_code_provided"}';
                    say('Неверный код', $msl);
                }
            }
        }
        break; 
    case 'PANIC': say('ПАНИКА', $msl);//  Alarm panic button pressed
        $topic = $name."/alarmpanel/command";
        $status = '{"audio":"http://192.168.10.26/cms/sounds/10h.mp3"}';
        break; 
    default: say('Сообщение alarm set не распознаноо', 0);
        //$this->result = array('status' => 'не распознано', 'date' => date('Y/m/d H:i:s', time()));
        //DebMes('Сообщение - '.$state['event'].' - от - '.$state['deviceId'].' - '.$this->result['status'].' - '.$this->result['date']);
        //say('Сообщение не распознано о - '.$state['event'].' от - '.$state['deviceId'].' - '.$this->result['status'].' - в - '.$this->result['date'], $msl);
        break;
}

mqttMob($topic, $status, 0, 0);  //Передаем в MQTT
