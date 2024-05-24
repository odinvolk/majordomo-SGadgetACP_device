<?php
/* SGadgetMAP_state.php
* 
* Application State Data
* Приложение также может публиковать данные о состоянии дисплея, такие как текущий URL-адрес, панели, состояние экрана.
* 
* {"currentUrl": "","screenOn": true,"brightness": 255,"presence": "true"}
* {"currentUrl": "http://192.168.10.26/popup/plans.html?id=2","screenOn": true,"brightness": 32,"presence": "true"}
* {"currentUrl": "http://192.168.10.26/menu/526.html","screenOn": true}
*/
$state = json_decode($params['VALUE'], true); // alarmpanel/state

foreach ($state as $k=>$v) 
{
$this->setProperty('state_'.$k, $v);
}
/* или
$this->setProperty('state_screenOn', $params['screenOn']);
$this->setProperty('state_currentUrl', $params['currentUrl']);
$this->setProperty('state_brightness', $params['brightness']);
$this->setProperty('state_presence', $params['presence']);
*/
