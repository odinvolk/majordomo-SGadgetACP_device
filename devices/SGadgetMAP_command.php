<?php
/* SGadgetMAP_command.php
* {"payload": "alert": "Hello!", "topic": "alarmpanel/command"}
* {"payload": "{'sun':'below_horizon'}", "topic": "alarmpanel/command"}
* {"condition":"sunny","humidity":61,"temperature":27.2,"forecast":[
* {'condition': 'sunny', 'temperature': 19.8, 'templow': 13.5, 'datetime': '2020-11-30T15:00:00+00:00', 'wind_bearing': 101.4, 'wind_speed': 30.6}]}
* 
* https://github.com/thanksmister/android-mqtt-alarm-panel
* 
* $prop = $params['PROPERTY'];
* $new = $params['NEW_VALUE'];
* $old = $params['OLD_VALUE'];
* $oot = $params['ORIGINAL_OBJECT_TITLE'];
*/

$ot = $this->object_title;
$desc = $this->description;
$name = $this->getProperty('name');
//$msl = 0;
//$msl = $this->getProperty('minMsgLevel');

if ($params['ph']) { // callMethod('MobileMAP01.command', array('ph'=>$ph,'level'=>$level));
                     // вставить   Общие настройки -> Обработчики -> After SAY (code)
    $params['PROPERTY'] = 'spech';
    $params['NEW_VALUE'] = $params['ph'];
}

//$payload = $params['PROPERTY'];

switch ($params['PROPERTY']) { // SWM07E/alarmpanel/command
    case 'dashboard': // number   {"dashboard": 0} Переход к определенной панели (работает только 0)
        $array = array('dashboard' => $params['NEW_VALUE']);
        $status = json_encode($array, true, JSON_UNESCAPED_UNICODE);
        break;
    case 'audio': // URL {"audio": "http://<url>"} Немедленно воспроизводит аудио, указанное по URL-адресу
        $array = array('audio' => $params['NEW_VALUE']);
        $status = json_encode($array, JSON_UNESCAPED_UNICODE);
        // {"audio":"http://192.168.10.26/cms/sounds/10h.mp3"}
        break; 
    case 'wake': // true {"wake": true} Пробуждает экран, если он находится в спящем режиме
        $status = '{"wake": true}';
        break; 
    case 'speak': // data {"speak": "Hello!"} Использует устройства TTS для озвучивания сообщения
        $array = array('speak' => $params['NEW_VALUE']);
        $status = json_encode($array, true, JSON_UNESCAPED_UNICODE);
        break; 
    case 'spech': // data {"speak": "Hello!"} Использует устройства TTS для озвучивания сообщения и пишет в сенсор
        $array = array('speak' => $params['NEW_VALUE']);
        $status = json_encode($array, JSON_UNESCAPED_UNICODE);
        $topic2 = $name."/home/alarm/sensor/spech";
        mqttMob($topic2, $params['NEW_VALUE'], 0, 0);  //Передаем в MQTT
        break; 
    case 'alert': // data {"alert": "Hello!"} Отображает диалоговое окно предупреждения в приложении
        $array = array('alert' => $params['NEW_VALUE']);
        $status = json_encode($array, JSON_UNESCAPED_UNICODE);
        break; 
    case 'notification': // data {"notification": "Hello!"}  Отображает системное уведомление на устройстве
        $array = array('notification' => $params['NEW_VALUE']);
        $status = json_encode($array, JSON_UNESCAPED_UNICODE);
        break; 
    case 'sun': // data	{"sun": "above_horizon"} Changes the application day or night mode based on sun value (above_horizon, below_horizon)
        // Изменяет дневной или ночной режим приложения в зависимости от значения солнца (выше_горизонта, ниже_горизонта)
        $array = array('sun' => $params['NEW_VALUE']);
        $status = json_encode($array);
        break;
    case 'clearCache': // {"clearCache":true, "relaunch":true}
        $status = '{"clearCache": true, "relaunch": true}';
        break;  
    case 'relaunch': // {"clearCache":true, "relaunch":true}
        $status = '{"clearCache": true, "relaunch": true}';
        break; // RFC 3339   echo date(DATE_RFC3339); // 2023-11-21T23:09:00+03:00   echo date('Y-m-d\TH:i:sP'); // 2023-11-21T23:09:00+03:00
    case 'weather': $status = '{"weather":
        {"condition": "cloudy", "humidity": 41, "temperature": -7.2, "forecast": [
        {"condition": "sunny", "temperature": 19.8, "templow": 13.5, "datetime": "2020-11-30T15:00:00+00:00", "wind_bearing": 101.4, "wind_speed": 30.6}, 
        {"condition": "cloudy", "precipitation": 1.2, "temperature": 23.0, "templow": 16.8, "datetime": "2020-12-01T15:00:00+00:00", "wind_bearing": 17.1, "wind_speed": 24.5}, 
        {"condition": "partlycloudy", "temperature": 26.0, "templow": 20.5, "datetime": "2020-12-02T15:00:00+00:00", "wind_bearing": 48.8, "wind_speed": 14.0}, 
        {"condition": "partlycloudy", "precipitation": 0.3, "temperature": 25.2, "templow": 19.7, "datetime": "2020-12-03T15:00:00+00:00", "wind_bearing": 71.5, "wind_speed": 22.7}, 
        {"condition": "partlycloudy", "temperature": 22.7, "templow": 18.0, "datetime": "2020-12-04T15:00:00+00:00", "wind_bearing": 105.5, "wind_speed": 33.5}
                    ]}}';
        break; 
    default: //say('Сообщение не распознано', 0);
        $this->result = array('status' => 'не распознано', 'date' => date('Y/m/d H:i:s', time()));
        //DebMes('Сообщение - '.$state['event'].' - от - '.$state['deviceId'].' - '.$this->result['status'].' - '.$this->result['date']);
        say('Сообщение не распознано о - '.$state['event'].' от - '.$state['deviceId'].' - '.$this->result['status'].' - в - '.$this->result['date'], $msl);
        break;
}

$topic = $name."/alarmpanel/command";
mqttMob($topic, $status, 0, 0);  //Передаем в MQTT

/*  RFC 3339   echo date(DATE_RFC3339); // 2023-11-21T23:09:00+03:00   echo date('Y-m-d\TH:i:sP'); // 2023-11-21T23:09:00+03:00
$array = array(
    'weather' => array(
        'condition' => getGlobal('current_weather.icon'),                               // состояние
        'humidity' => getGlobal('current_weather.humidity'),                                   // влажность
        'temperature' => getGlobal('current_weather.temperature'),                              // температура
        'forecast' => array(                                  // прогноз
            '0' => array(
                'condition' => 'sunny',
                'temperature' => '19,8',
                'templow' => '13,5',                          // температура
                'datetime' => '2020-11-30T15:00:00+00:00',
                'wind_bearing' => '101,4',                    // направление ветра
                'wind_speed' => '30,6'                        // скорость ветра
            ),
            '1' => array(
                'condition' => 'cloudy',
                'precipitation' => '1,2',                     // выпадение осадков
                'temperature' => '23',
                'templow' => '16,8',
                'datetime' => '2020-12-01T15:00:00+00:00',
                'wind_bearing' => '17,1',
                'wind_speed' => '24,5'
            ),
            '2' => array(
                'condition' => 'partlycloudy',
                'temperature' => '26',
                'templow' => '20,5',
                'datetime' => '2020-12-02T15:00:00+00:00',
                'wind_bearing' => '48,8',
                'wind_speed' => '14'
            ),
            '3' => array(
                'condition' => 'partlycloudy',
                'precipitation' => '0,3',
                'temperature' => '25,2',
                'templow' => '19,7',
                'datetime' => '2020-12-03T15:00:00+00:00',
                'wind_bearing' => '71,5',
                'wind_speed' => '22,7'
            ),
            '4' => array(
                'condition' => 'partlycloudy',
                'temperature' => '22,7',
                'templow' => '18',
                'datetime' => '2020-12-04T15:00:00+00:00',
                'wind_bearing' => '105,5',
                'wind_speed' => '33,5'
            )
        )
    )
);
*/
