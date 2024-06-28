<?php
/* SGadgetMAP_batteryLevelUpdated.php
* $this->setProperty('batteryLevel',$this->getProperty('info_battery_level')); //передает значение из метода info_battery_level в метод batteryLevel
*/

/* Уже есть в родительском методе SDevices_batteryLevelUpdated.php 
$batteryOperated = (int)$this->getProperty('batteryOperated');
$batteryLevel = (int)$this->getProperty('batteryLevel');

if ($batteryOperated && $batteryLevel <= 20) {
    $batteryWarning = 1;
} elseif ($batteryOperated && $batteryLevel <= 50) {
    $batteryWarning = 2;
} else {
    $batteryWarning = 0;
}

$this->setProperty('batteryWarning', $batteryWarning);

if ($batteryOperated && $batteryLevel > 0) {
    $this->callMethod('keepAlive');
}
*/
//Добавляем трансляцию в registerEvent
if ($this->getProperty('groupRegisterEvent')) {
    $ot = $this->object_title;
    $desc = $this->description;
    $lr = $this->getProperty('linkedRoom');

    if ($this->getProperty('isPlugged') != 1) { // isPlugged Подключена зарядка или нет
        $isPlug = ' Разряжается'; 
    } else { 
        $isPlug = ' Заряжается'; 
    }
    $array = array('batteryLevel'=>$batteryLevel,'isPlugged'=>$isPlug,'title'=>$ot,'desc'=>$desc,'linkedRoom'=>$lr,);
    registerEvent($desc.'/battery/level', $array);
}

/*
if (!isset($params['NEW_VALUE'])) {
    $new_value=(float)$this->getProperty('batteryLevel');
} else {
    $new_value=(float)$params['NEW_VALUE'];
}

if ($new_value >= 80) {
    $this->setProperty('batteryIcon', 'battery-full');
    $this->setProperty('batteryColor', 'green');
}
else if ($new_value >= 60) {
    $this->setProperty('batteryIcon', 'battery-three-quarters');
    $this->setProperty('batteryColor', 'green');
}
else if ($new_value >= 40) {
    $this->setProperty('batteryIcon', 'battery-half');
    $this->setProperty('batteryColor', 'green');
}
else if ($new_value >= 20) {
    $this->setProperty('batteryIcon', 'battery-quarter');
    $this->setProperty('batteryColor', 'orange');
}
else if ($new_value >= 0) {
    $this->setProperty('batteryIcon', 'battery-empty');
    $this->setProperty('batteryColor', 'red');
}
*/
