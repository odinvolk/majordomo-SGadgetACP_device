<?php
/* SGadgetMAP_sensorsDetected.php
* $state = $params['VALUE']; // Принимаем данные
*
* Сенсоры
* Приложение будет публиковать данные датчиков устройства в соответствии с описанием API и частотой считывания показаний датчиков.
* В настоящее время опубликованы датчики устройства для измерения давления, температуры, освещенности и уровня заряда батареи.
* The application will post device sensors data per the API description and Sensor Reading Frequency.
* Currently device sensors for Pressure, Temperature, Light and Battery Level are published.
*/
$state = json_decode($params['VALUE'], true); // Принимаем и Декодируем в массив

//------------------------------------------- Записываем данные в свойства из MQTT alarmpanel/sensor/battery

switch ($state['unit']) {
    case '%': // уровнь заряда батареи {"value": 84,"unit": "%","charging": true,"acPlugged": true,"usbPlugged": false}
        $this->setProperty('batteryLevel', $state['value']);
        $this->setProperty('batteryCharging', (int)$state['charging']);
        //$this->setProperty('acPlugged', $state['acPlugged']);
        //$this->setProperty('usbPlugged', $state['usbPlugged']);
        //$this->setProperty('batteryUnit', $state['unit']);
        break;
    case 'lx': // уровнь освещенности {"unit":"lx", "value":"920"}
        $this->setProperty('sensorLight', $state['value']);
        //$this->setProperty('lightUnit', $state['unit']);
        break;
    case 'uT': // уровнь magneticField {"unit":"uT", "value":"-1780.699951171875"}
        $this->setProperty('sensorMagneticField', $state['value']);
        //$this->setProperty('magneticFieldUnit', $state['unit']);
        break;
    case 'hPa': // уровнь давления {"unit":"hPa", "value":"1011.584716796875"}
        $this->setProperty('sensorPressure', $state['value']);
        //$this->setProperty('pressureUnit', $state['unit']);
        break;
    case '°C': // уровнь температуры {"unit":"°C", "value":"24"}
        $this->setProperty('sensorTemperature', $state['value']);
        //$this->setProperty('temperatureUnit', $state['unit']);
        break;
    default: //say('Сообщение не распознано', 0);
        $this->result = array('status' => 'не распознано', 'date' => date('Y/m/d H:i:s', time()));
        //DebMes('Сообщение - '.$state['event'].' - от - '.$state['deviceId'].' - '.$this->result['status'].' - '.$this->result['date']);
        say('Сообщение не распознано о - '.$state['event'].' - '.$this->result['status'].' - в - '.$this->result['date'], $msl);
        break;
}
