<?php

$this->device_types['MobileMAP'] = array(
    'TITLE'=>'Gadget MAP',
    'PARENT_CLASS'=>'SDevices',
    'CLASS'=>'SGadgetMAP',
    'DESCRIPTION'=>'Gadget devices MQTT Alarm Panel',
    'PROPERTIES'=>array(
        'name'=>array('DESCRIPTION'=>'Имя устройства (топик))','_CONFIG_TYPE'=>'text'),
        'mjpegUrl'=>array('DESCRIPTION'=>'Alarm Panel Camera','_CONFIG_TYPE'=>'text','_CONFIG_HELP'=>'SdTher'),
        
        'code'=>array('DESCRIPTION'=>'Код постановки снятия с охраны','_CONFIG_TYPE'=>'text','_CONFIG_DEFAULT' => '1234'),
        
        'mqttWriteToProperties'=>array('DESCRIPTION'=>'Включить чтение и запись доп. параметров через MQTT','_CONFIG_TYPE'=>'yesno'),

        'batteryLevel'=>array('DESCRIPTION'=>'Урорвень заряда батареи','ONCHANGE'=>'batteryLevelUpdated','_CONFIG_TYPE'=>'readonly'),
        'batteryCharging'=>array('DESCRIPTION'=>'Состояние заряда батареи','_CONFIG_TYPE'=>'readonly'),
        
        //'motionDetectorStatus'=>array('DESCRIPTION'=>'Состояние датчика движения','_CONFIG_TYPE'=>'readonly'),
        
        'sensorMotion'=>array('DESCRIPTION'=>'Motion Detected','_CONFIG_TYPE'=>'readonly'),
        'sensorQrcode'=>array('DESCRIPTION'=>'QR Code','_CONFIG_TYPE'=>'readonly'),
        'sensorFace'=>array('DESCRIPTION'=>'Face Detected','_CONFIG_TYPE'=>'readonly'),
        'sensorMagneticField'=>array('DESCRIPTION'=>'Alarm Panel Magnetic Field "uT"','_CONFIG_TYPE'=>'readonly'),
        'sensorLight'=>array('DESCRIPTION'=>'Alarm Panel Light Level "lx"','_CONFIG_TYPE'=>'readonly'),
        'sensorPressure'=>array('DESCRIPTION'=>'Alarm Panel Pressure "hPa"','_CONFIG_TYPE'=>'readonly'),
        'sensorTemperature'=>array('DESCRIPTION'=>'WallPanel Temperature "°C"','_CONFIG_TYPE'=>'readonly'),
        'command'=>array('DESCRIPTION'=>'Команды управления','_CONFIG_TYPE'=>'readonly'),
        'alarmEvent'=>array('DESCRIPTION'=>'alarmEvent','_CONFIG_TYPE'=>'readonly'),
//        'alarmNotification'=>array('DESCRIPTION'=>'alarmNotification','_CONFIG_TYPE'=>'readonly'),
        
        'arms'=>array('DESCRIPTION'=>'Команды постановки снятия с охраны (disarmed, arming, armed_away, pending, triggered) ','ONCHANGE'=>'alarm','_CONFIG_TYPE'=>'readonly'),
        'speak'=>array('DESCRIPTION'=>'TTS для произнесения сообщения (/alarmpanel/command/speak)','ONCHANGE'=>'command','_CONFIG_TYPE'=>'readonly'),
        'sun'=>array('DESCRIPTION'=>'Дневной ночной режим (/alarmpanel/command/sun)','ONCHANGE'=>'command','_CONFIG_TYPE'=>'readonly'),
        'alert'=>array('DESCRIPTION'=>'Диалоговое окно предупреждения в приложении (/alarmpanel/command/alert)','ONCHANGE'=>'command','_CONFIG_TYPE'=>'readonly'),
        'wake'=>array('DESCRIPTION'=>'Пробуждает экран (/alarmpanel/command/wake)','ONCHANGE'=>'command','_CONFIG_TYPE'=>'readonly'),
        'notification'=>array('DESCRIPTION'=>'Отображает системное уведомление на устройстве (/alarmpanel/command/notification)','ONCHANGE'=>'command','_CONFIG_TYPE'=>'readonly'),
        'dashboard'=>array('DESCRIPTION'=>'Переход между панелями, 0 экран сигнализации. (/alarmpanel/command/dashboard)','ONCHANGE'=>'command','_CONFIG_TYPE'=>'readonly'),
        'audio'=>array('DESCRIPTION'=>'Воспроизвести аудио, указанное в URL-адресе (/alarmpanel/command/audio)','ONCHANGE'=>'command','_CONFIG_TYPE'=>'readonly'),
        'relaunch'=>array('DESCRIPTION'=>'Перезапуск (alarmpanel/command/relaunch)','ONCHANGE'=>'command','_CONFIG_TYPE'=>'readonly'),
        'clearCache'=>array('DESCRIPTION'=>'Очистить кэш (alarmpanel/command/clearCache)','ONCHANGE'=>'command','_CONFIG_TYPE'=>'readonly'),
        'weather'=>array('DESCRIPTION'=>'Погода','ONCHANGE'=>'command','_CONFIG_TYPE'=>'readonly'),
        
//        'usbPlugged'=>array('DESCRIPTION'=>'Подключена зарядка usb','_CONFIG_TYPE'=>'readonly'),
//        'acPlugged'=>array('DESCRIPTION'=>'Подключена зарядка','_CONFIG_TYPE'=>'readonly'),
        
        'online'=>array('DESCRIPTION'=>'На связи','_CONFIG_TYPE'=>'readonly'),
        'status'=>array('DESCRIPTION'=>'status','ONCHANGE'=>'statusUpdated','_CONFIG_TYPE'=>'readonly'),
        'state_presence'=>array('DESCRIPTION'=>'Состояние режима presence','_CONFIG_TYPE'=>'readonly'),
        'state_brightness'=>array('DESCRIPTION'=>'Яркость экрана','_CONFIG_TYPE'=>'readonly'),
        'state_screenOn'=>array('DESCRIPTION'=>'Включен ли экран?','_CONFIG_TYPE'=>'readonly'),
        'state_currentUrl'=>array('DESCRIPTION'=>'URL текущей страницы','_CONFIG_TYPE'=>'readonly'),
        'blocked'=>array('DESCRIPTION'=>'Отключить на время реакцию на движение'),
        ),
    'METHODS'=>array(
        'alarm'=>array('DESCRIPTION'=>'Локальные команды постановкой снятием (/home/alarm)','_CONFIG_SHOW'=>0),
        'alarmSet'=>array('DESCRIPTION'=>'Внешние команды постановкой снятием (/home/alarm/set)','_CONFIG_SHOW'=>0),
        'alarmEvent'=>array('DESCRIPTION'=>'События для обратной связи (/home/alarm/event)','_CONFIG_SHOW'=>0),
        'state'=>array('DESCRIPTION'=>'Состояние экрана (/alarmpanel/state)','_CONFIG_SHOW'=>1),
        'command'=>array('DESCRIPTION'=>'Команды для консоли (/alarmpanel/command)','_CONFIG_SHOW'=>1),
        'faceDetected'=>array('DESCRIPTION'=>'Детектор лица (/alarmpanel/sensor/face)','_CONFIG_SHOW'=>1),
        'qrcodeDetected'=>array('DESCRIPTION'=>'Детектор qrcode (/alarmpanel/sensor/qrcode)','_CONFIG_SHOW'=>1),
        'motionDetected'=>array('DESCRIPTION'=>'Детектор движения (/alarmpanel/sensor/motion)','_CONFIG_SHOW'=>1),
        'sensorsDetected'=>array('DESCRIPTION'=>'Принимаем состояния батареи и сенсоров (/alarmpanel/sensor/battery)','_CONFIG_SHOW'=>1),
        'statusUpdated'=>array('DESCRIPTION'=>'Обновление статуса','_CONFIG_SHOW'=>0),
        'batteryLevelUpdated'=>array('DESCRIPTION'=>'Обновление уровня заряда','_CONFIG_SHOW'=>0),
    ),
);
